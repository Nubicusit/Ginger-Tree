<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Estimation;
use App\Models\EstimationItem;
// use Spatie\LaravelPdf\Facades\Pdf;

class EstimationController extends Controller
{
    public function dashboard()
    {
        return view('Estimator.dashboard');
    }

    public function estimation()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', fn($q) => $q->where('approval_status', 'Yes'))
                ->latest()->get();
        } else {
            $userId = $user->id;
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', fn($q) => $q
                    ->where('assigned_staff', $userId)
                    ->where('approval_status', 'Yes'))
                ->latest()->get();
        }

        return view('Estimator.estimations', [
            'leads'         => $leads,
            'totalAssigned' => $leads->count(),
        ]);
    }



    public function createQuotation(Lead $lead)
    {
        $siteVisit         = $lead->siteVisit;
        $inventoryItems    = InventoryStock::select('id', 'item_name', 'price', 'category', 'gst_percentage')->get();
        $services          = \App\Models\Service::all();
        $estimation        = \App\Models\Estimation::where('lead_id', $lead->id)->first();
        $existingQuotation = \App\Models\Quotation::where('lead_id', $lead->id)->first();

        if ($estimation) {
            $estimationItems = \App\Models\EstimationItem::where('estimation_id', $estimation->id)
                ->orderBy('sort_order')->get();

            $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

            // General items (unchanged)
            $items = $estimationItems
                ->filter(fn($item) => ($item->section ?? 'General') === 'General')
                ->map(fn($item) => [
                    'item_id'        => $item->item_id,
                    'item_name'      => $item->name ?? '',
                    'custom_name'    => $item->item_id ? '' : ($item->name ?? ''),
                    'category'       => $item->category   ?? '',
                    'description'    => $item->description ?? '',
                    'quantity'       => $item->qty,
                    'unit'           => $item->unit,
                    'price'          => $item->unit_price,
                    'gst_percentage' => $item->gst ?? 0,
                ])->values()->toArray();

            /*
         * ✅ FIX: Build existingServices by walking estimation_items IN ORDER.
         *
         * Problem: two service rows can share the same service_id (e.g. two
         * "living room" services). Filtering accessories by service_id alone
         * makes BOTH services pick up ALL accessories for that service_id.
         *
         * Solution: iterate rows sequentially. When we hit a 'Service' row,
         * open a new service bucket. Collect 'ServiceItem' and 'Accessory'
         * rows into whichever bucket is currently open — determined by
         * sort_order position, not service_id.
         */
            $existingServices = [];
            $currentIndex     = null;   // index into $existingServices

            foreach ($estimationItems as $row) {
                $section = $row->section ?? 'General';

                if ($section === 'Service') {
                    // Start a new service bucket
                    $existingServices[] = [
                        'service_id'   => $row->service_id,
                        'service_name' => $row->name,
                        'note'         => $row->description ?? '',
                        'sub_items'    => [],
                        'accessories'  => [],
                    ];
                    $currentIndex = count($existingServices) - 1;
                } elseif ($section === 'ServiceItem' && $currentIndex !== null) {
                    $parts = explode('||', $row->description ?? '');
                    $existingServices[$currentIndex]['sub_items'][] = [
                        'name'        => $row->name,
                        'material'    => $parts[0] ?? '',
                        'unit'        => $row->unit ?? '',
                        'size'        => $row->size ?? ($parts[1] ?? ''),
                        'mrp'         => $row->unit_price ?? 0,
                        'offer_price' => $row->offer_price ?? 0,
                        'qty'         => $row->qty,
                    ];
                } elseif ($section === 'Accessory' && $currentIndex !== null) {
                    $existingServices[$currentIndex]['accessories'][] = [
                        'item_id' => $row->item_id,
                        'name'    => $row->name ?? '',
                        'size'    => $row->description ?? '',
                        'qty'         => $row->qty ?? 1,
                        'unit'    => $row->unit ?? '',
                        'price'   => $row->unit_price ?? 0,
                        'offer_price' => $row->offer_price ?? 0,
                        'image'   => $row->image ?? null,
                    ];
                }
                // 'General' section rows are handled separately above — skip here
            }
        } else {
            $last        = \App\Models\Estimation::latest()->first();
            $next        = $last ? (intval(substr($last->estimation_no, -4)) + 1) : 1;
            $quotationNo = 'QT-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            $items            = [];
            $existingServices = [];
        }
        // dd($existingServices);
        return view('Estimator.create-quotation', compact(
            'lead',
            'siteVisit',
            'inventoryItems',
            'quotationNo',
            'existingQuotation',
            'items',
            'estimation',
            'services',
            'existingServices'
        ));
    }

    public function storeQuotation(Request $request)
    {
        try {
            $leadId = $request->lead_id;
            if (!$leadId) return response()->json(['success' => false, 'message' => 'Lead ID missing']);

            $items    = $request->items    ?? [];
            $services = $request->services ?? [];

            if (empty($items) && empty($services)) {
                return response()->json(['success' => false, 'message' => 'No items or services added']);
            }

            $lead        = \App\Models\Lead::findOrFail($leadId);
            $itemsToSave = [];
            $subtotal    = 0;
            $gstAmount   = 0;

            /* ── Inventory Items ── */
            foreach ($items as $item) {
                $itemId        = $item['item_id'] ?? null;
                $itemName      = '';
                $gstPercentage = 0;
                $qty           = floatval($item['quantity'] ?? 0);
                $price         = floatval($item['price']    ?? 0);

                if (!$itemId && !empty($item['custom_name'])) {
                    $inv    = \App\Models\InventoryStock::create([
                        'item_name' => $item['custom_name'],
                        'category'  => $item['category'] ?? '',
                        'unit'      => $item['unit']      ?? '',
                        'price'     => $price,
                        'quantity'  => 0,
                    ]);
                    $itemId   = $inv->id;
                    $itemName = $inv->item_name;
                }

                if ($itemId) {
                    $inv = \App\Models\InventoryStock::find($itemId);
                    if ($inv) {
                        $itemName      = $inv->item_name;
                        $gstPercentage = $item['gst_percentage'] ?? ($inv->gst_percentage ?? 0);
                    }
                }

                $itemSubtotal = $qty * $price;
                $itemGst      = ($itemSubtotal * $gstPercentage) / 100;
                $subtotal    += $itemSubtotal;
                $gstAmount   += $itemGst;

                $itemsToSave[] = [
                    'item_id'     => $itemId,
                    'name'        => !empty($item['custom_name']) ? $item['custom_name'] : $itemName,
                    'section'     => 'General',
                    'description' => $item['description'] ?? '',
                    'category'    => $item['category']    ?? '',
                    'unit'        => $item['unit'],
                    'qty'         => $qty,
                    'unit_price'  => $price,
                    'amount'      => $itemSubtotal + $itemGst,
                    'length'      => $item['length']         ?? null,
                    'breadth'     => $item['breadth']        ?? null,
                    'area'        => $item['area']           ?? null,
                    'gst'         => $gstPercentage,
                    'gst_amount'  => $itemGst,
                    'service_id'  => null,
                    'service_tax' => 0,
                    'sub_items'   => [],
                ];
            }

            /* ── Service Items ── */
            $servicesToSave = [];

            // ✅ FIX: Use $i from THIS loop so $request->hasFile("services.$i.accessories.$j.image") works correctly
            foreach ($services as $i => $svc) {
                $service = \App\Models\Service::find($svc['service_id'] ?? null);
                if (!$service) continue;

                $svcPrice  = floatval($svc['price']      ?? 0);
                $svcGst    = floatval($svc['gst']        ?? 0);
                $svcGstAmt = floatval($svc['gst_amount'] ?? 0);
                $svcTax    = floatval($svc['tax']        ?? 0);
                $svcTotal  = floatval($svc['total']      ?? 0);
                $svcNote   = $svc['note'] ?? '';

                $subtotal  += $svcTotal;
                $gstAmount += $svcGstAmt;

                // Collect sub-items
                $subItems = [];
                foreach (($svc['sub_items'] ?? []) as $si) {
                    if (empty($si['name'])) continue;
                    $subItems[] = [
                        'name'        => $si['name'],
                        'material'    => $si['material']    ?? '',
                        'unit'        => $si['unit']        ?? '',
                        'size'        => $si['size']        ?? '',
                        'mrp'         => floatval($si['mrp'] ?? 0),
                        'offer_price' => floatval($si['offer_price'] ?? 0),
                        'qty'         => intval($si['qty']),
                    ];
                }

                // ✅ FIX: Handle accessory image upload HERE while $i is correct
                $accessories = [];
                foreach (($svc['accessories'] ?? []) as $j => $acc) {
                    if (empty($acc['item_id'])) continue;

                    $imagePath = null;

                    // ✅ $i is the correct service index from the outer loop


                    if ($request->hasFile("services.$i.accessories.$j.image")) {

                        $file = $request->file("services.$i.accessories.$j.image");

                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        $file->move(public_path('img/estimation_items'), $filename);

                        $imagePath = 'img/estimation_items/' . $filename; // ✅ store relative path
                    }


                    $accessories[] = [
                        'item_id' => $acc['item_id'],
                        'name'    => $acc['name']  ?? '',
                        'size'    => $acc['size']  ?? '',
                        'unit'    => $acc['unit']  ?? '',
                        'qty'   => $acc['qty']  ?? '',
                        'price'   => floatval($acc['price'] ?? 0),
                        'offer_price' => floatval($acc['offer_price'] ?? 0),
                        'image'   => $imagePath,  // ✅ image path resolved here
                    ];
                }

                $servicesToSave[] = [
                    'service_id'   => $service->id,
                    'service_name' => $service->service_name,
                    'section'      => 'Service',
                    'note'         => $svcNote,
                    'price'        => $svcPrice,
                    'gst'          => $svcGst,
                    'gst_amount'   => $svcGstAmt,
                    'service_tax'  => $svcTax,
                    'total'        => $svcTotal,
                    'sub_items'    => $subItems,
                    'accessories'  => $accessories,  // ✅ already has image paths
                ];
            }

            $grandTotal = $subtotal;

            /* ── Estimation number ── */
            $lastEst = \App\Models\Estimation::latest()->first();
            $next    = $lastEst ? intval(substr($lastEst->estimation_no, -4)) + 1 : 1;
            $estNo   = 'EST-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);

            /* ── Create / Update Estimation ── */
            $estimation = \App\Models\Estimation::where('lead_id', $leadId)->first();

            $estData = [
                'subtotal'    => $subtotal,
                'gst_pct'     => 18,
                'gst_amount'  => $gstAmount,
                'grand_total' => $grandTotal,
                'title'       => 'Quotation ' . $request->quotation_no,
                'status'      => 'Sent',
            ];

            if ($estimation) {
                $estimation->update($estData);
            } else {
                $estimation = \App\Models\Estimation::create(array_merge($estData, [
                    'lead_id'       => $leadId,
                    'estimation_no' => $estNo,
                    'client_name'   => $lead->client_name ?? '',
                    'client_email'  => $lead->email       ?? '',
                    'client_phone'  => $lead->phone       ?? '',
                    'site_address'  => $lead->site_address ?? '',
                    'discount'      => 0,
                    'created_by'    => Auth::id(),
                ]));
            }

            /* ── Save Estimation Items ── */
            \App\Models\EstimationItem::where('estimation_id', $estimation->id)->delete();
            $sortOrder = 1;

            // General items
            foreach ($itemsToSave as $index => $item) {
                \App\Models\EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'item_id'       => $item['item_id']    ?? null,
                    'name'          => $item['name'],
                    'section'       => 'General',
                    'description'   => $item['description'] ?? '',
                    'category'      => $item['category']   ?? null,
                    'unit'          => $item['unit'],
                    'qty'           => $item['qty'],
                    'unit_price'    => $item['unit_price'],
                    'amount'        => $item['amount'],
                    'sort_order'    => $sortOrder++,
                    'length'        => $item['length']     ?? null,
                    'breadth'       => $item['breadth']    ?? null,
                    'area'          => $item['area']       ?? null,
                    'gst'           => $item['gst']        ?? null,
                    'gst_amount'    => $item['gst_amount'] ?? null,
                    'service_id'    => null,
                    'service_tax'   => 0,
                    'image'         => $imagePaths[$index] ?? null,
                ]);
            }

            // Service items
            foreach ($servicesToSave as $svc) {
                \App\Models\EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'item_id'       => null,
                    'name'          => $svc['service_name'],
                    'section'       => 'Service',
                    'description'   => $svc['note'],
                    'category'      => \App\Models\Service::find($svc['service_id'])?->category_service ?? '',
                    'unit'          => 'Nos',
                    'qty'           => 1,
                    'unit_price'    => $svc['price'],
                    'amount'        => $svc['total'],
                    'sort_order'    => $sortOrder++,
                    'gst'           => $svc['gst'],
                    'gst_amount'    => $svc['gst_amount'],
                    'service_id'    => $svc['service_id'],
                    'service_tax'   => $svc['service_tax'],
                    'image'         => null,
                ]);

                // Sub-items
                foreach ($svc['sub_items'] as $si) {
                    \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'item_id'       => null,
                        'name'          => $si['name'],
                        'section'       => 'ServiceItem',
                        'description'   => ($si['material'] ?? '') . '||' . ($si['size'] ?? ''),
                        'category'      => $si['material'] ?? '',
                        'unit'          => $si['unit']     ?? '',
                        'qty' => intval($si['qty']),
                        'unit_price'    => floatval($si['mrp']         ?? 0),
                        'amount'        => floatval($si['offer_price'] ?? 0),
                        'offer_price'   => floatval($si['offer_price'] ?? 0),
                        'sort_order'    => $sortOrder++,
                        'size'          => $si['size'] ?? null,
                        'gst'           => 0,
                        'gst_amount'    => 0,
                        'service_id'    => $svc['service_id'],
                        'service_tax'   => 0,
                        'image'         => null,
                    ]);
                }

                // ✅ FIX: Accessories — image path already resolved above, just save it
                foreach (($svc['accessories'] ?? []) as $acc) {
                    if (empty($acc['item_id'])) continue;

                    \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'item_id'       => $acc['item_id'],
                        'name'          => $acc['name'],
                        'section'       => 'Accessory',
                        'description'   => $acc['size'] ?? '',
                        'category'      => 'Accessory',
                        'unit'          => $acc['unit'] ?? '',
                        'qty' => intval($acc['qty'] ?? 1),
                        'unit_price'    => floatval($acc['price'] ?? 0),
                        'offer_price'   => floatval($acc['offer_price'] ?? 0),
                        'amount'        => floatval($acc['price'] ?? 0),
                        'sort_order'    => $sortOrder++,
                        'gst'           => 0,
                        'gst_amount'    => 0,
                        'service_id'    => $svc['service_id'],
                        'service_tax'   => 0,
                        'image'         => $acc['image'],  // ✅ already stored above
                    ]);
                }
            }

            return response()->json([
                'success'       => true,
                'message'       => 'Estimation saved successfully',
                'estimation_id' => $estimation->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate PDF — groups items by section for the new PDF layout
     */

    public function estimationPdf($id)
    {

        $estimation = Estimation::findOrFail($id);
        $lead       = Lead::find($estimation->lead_id);

        $dbItems = EstimationItem::where('estimation_id', $id)
            ->orderBy('sort_order')
            ->get();

        // ✅ Convert EST → QT
        $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

        $items = [];

        // =========================================================
        // ✅ STEP 1: COLLECT ACCESSORIES (GROUP BY service_id)
        // =========================================================
        $accessoriesByService = [];

        foreach ($dbItems as $row) {
            if ($row->section === 'Accessory') {

                $accessoriesByService[$row->service_id][] = [
                    'name'  => $row->name,
                    'size'  => $row->description,
                    'unit'  => $row->unit,
                    'qty'  => $row->qty,
                    'price' => $row->unit_price,
                    'offer_price' => $row->offer_price > 0 ? $row->offer_price : $row->unit_price,
                    'image' => $row->image,
                ];
            }
        }

        // =========================================================
        // ✅ STEP 2: BUILD ITEMS ARRAY
        // =========================================================
        foreach ($dbItems as $row) {

            $section = $row->section ?? 'General';

            // ---------------- GENERAL ----------------
            if ($section === 'General') {

                $items[] = [
                    'section'        => 'General',
                    'item_name'      => $row->name ?? $row->description,
                    'category'       => $row->category ?? '',
                    'description'    => $row->description ?? '',
                    'unit'           => $row->unit ?? '',
                    'quantity'       => $row->qty,
                    'price'          => $row->unit_price,
                    'gst_percentage' => $row->gst ?? 0,
                    'service_tax'    => $row->service_tax ?? 0,
                    'gst_amount'     => $row->gst_amount ?? 0,
                ];
            }

            // ---------------- SERVICE HEADER ----------------
            elseif ($section === 'Service') {

                $items[] = [
                    'section'            => 'Service',
                    'item_name'          => $row->name,
                    'service_name'       => $row->name,
                    'service_id'         => $row->service_id,
                    'service_note'       => $row->description ?? '',
                    'service_base_price' => $row->unit_price,
                    'gst_percentage'     => $row->gst ?? 0,
                    'service_tax'        => $row->service_tax ?? 0,
                    'gst_amount'         => $row->gst_amount ?? 0,

                    // ✅ Attach accessories here
                    'accessories' => $accessoriesByService[$row->service_id] ?? [],
                ];
            }

            // ---------------- SERVICE ITEMS ----------------
            elseif ($section === 'ServiceItem') {

                // description format: material||size
                $parts    = explode('||', $row->description ?? '');
                $material = $parts[0] ?? '';
                $size     = $parts[1] ?? '';

                $items[] = [
                    'section'     => 'ServiceItem',
                    'service_id'  => $row->service_id,
                    'item_name'   => $row->name,
                    'material'    => $material,
                    'unit'        => $row->unit ?? '',
                    'qty'         => $row->qty ?? 1,
                    'size'        => $row->size ?? $size,
                    'unit_price'  => $row->unit_price,
                    'offer_price' => $row->offer_price > 0 ? $row->offer_price : $row->amount,
                    'mrp'         => $row->unit_price,
                    'description' => '',
                ];
            }
        }

        // =========================================================
        // ✅ STEP 3: FLATTEN ITEMS (VERY IMPORTANT)
        // =========================================================
        $flatItems = [];
        $currentService = null;

        foreach ($items as $item) {

            if ($item['section'] === 'Service') {

                $currentService = $item;
                $flatItems[] = $item;
            } elseif ($item['section'] === 'ServiceItem' && $currentService) {

                $flatItems[] = array_merge($item, [
                    'service_name'       => $currentService['service_name'],
                    'service_note'       => $currentService['service_note'],
                    'service_base_price' => $currentService['service_base_price'],
                    'gst_percentage'     => $currentService['gst_percentage'],
                    'service_tax'        => $currentService['service_tax'],

                    // ✅ KEEP accessories available in Blade
                    'accessories'        => $currentService['accessories'],
                ]);
            } else {

                $flatItems[] = $item;
            }
        }

        // =========================================================
        // ✅ STEP 4: LOAD PDF
        // =========================================================
        $pdf = Pdf::loadView('Estimator.quotationpdf', [
            'quotation'   => $estimation,
            'lead'        => $lead,
            'quotationNo' => $quotationNo,
            'items'       => $flatItems,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('quotation-' . $quotationNo . '.pdf');
    }

    public function getServiceItems($serviceId)
    {
        $items = \App\Models\ServiceItem::where('service_id', $serviceId)
            ->where('status', 1)
            ->orderBy('id')
            ->get(['id', 'service_id', 'item_name', 'default_price', 'unit', 'description']);

        return response()->json(['items' => $items]);
    }

    public function getItemDetails(Request $request)
    {
        if (!$request->item_name) return response()->json(['success' => false]);
        $item = InventoryStock::where('item_name', 'LIKE', '%' . $request->item_name . '%')->first();
        if ($item) {
            return response()->json([
                'success'        => true,
                'item_id'        => $item->id,
                'category'       => $item->category,
                'unit'           => $item->unit,
                'price'          => $item->price,
                'gst_percentage' => $item->gst_percentage,
            ]);
        }
        return response()->json(['success' => false]);
    }
    public function updateAdminStatus(Request $request, $id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $adminStatus = $request->admin_status; // 'Approved' | 'Rejected'

        $estimation->admin_status = $adminStatus;
        $estimation->save();

        // ─────────────────────────────────────────────────────────────────
        // AUTO-CREATE PROJECT when both status=Approved AND admin_status=Approved
        // ─────────────────────────────────────────────────────────────────
        if ($adminStatus === 'Approved' && $estimation->status === 'Approved') {
            $this->createProjectFromEstimation($estimation);
        }

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $estimation->status = $request->status;
        $estimation->save();

        if ($estimation->lead_id) {
            if ($request->status === 'Approved') {
                Lead::where('id', $estimation->lead_id)->update(['status' => 'Won']);
            }
            if ($request->status === 'Rejected') {
                Lead::where('id', $estimation->lead_id)->update(['status' => 'Lost']);
            }
        }

        // Auto-create project when BOTH are Approved
        if ($request->status === 'Approved' && $estimation->admin_status === 'Approved') {
            $this->createProjectFromEstimation($estimation);
        }

        return response()->json(['success' => true]);
    }

    private function createProjectFromEstimation(\App\Models\Estimation $estimation): void
    {
        // Avoid duplicate projects for the same lead
        $existing = \App\Models\Project::where('lead_id', $estimation->lead_id)->first();
        if ($existing) return;

        $lead = \App\Models\Lead::find($estimation->lead_id);

        \App\Models\Project::create([
            'name' => $lead->project_type
                ?? 'Project - ' . ($lead->client_name ?? $estimation->client_name),
            'client'        => $estimation->client_name,
            'company_gst'   => null,
            'client_gst'    => null,
            'sales_rep'     => null,
            'sales_phone'   => $estimation->client_phone ?? null,
            'scope'         => $estimation->title ?? null,
            'timeline'      => null,
            'start_date'    => now()->toDateString(),
            'end_date'      => null,
            'total_value'   => $estimation->grand_total,
            'gst_rate'      => $estimation->gst_pct ?? 18,
            'po_number'     => null,
            'payment_terms' => null,
            'notes'         => 'Auto-created from Estimation #' . $estimation->estimation_no,
            'status'        => 'Active',
            'lead_id'       => $estimation->lead_id,
        ]);
    }

    public function assignDesigner(Request $request, $id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $estimation->designer_id = $request->designer_id ?: null;
        $estimation->save();
        return response()->json(['success' => true]);
    }
}
