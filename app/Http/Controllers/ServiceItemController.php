<?php

namespace App\Http\Controllers;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    public function store(Request $request)
{
    foreach ($request->item_name as $key => $name) {

        if (!empty($request->item_id[$key])) {
            // ✅ UPDATE
            ServiceItem::where('id', $request->item_id[$key])->update([
                'item_name' => $name,
                'default_price' => $request->price[$key] ?? null,
                'unit' => $request->unit[$key] ?? null,
            ]);
        } else {
            // ➕ CREATE
            ServiceItem::create([
                'service_id' => $request->service_id,
                'item_name' => $name,
                'default_price' => $request->price[$key] ?? null,
                'unit' => $request->unit[$key] ?? null,
            ]);
        }
    }

    return back()->with('success', 'Items saved successfully');
}
    public function destroy($id){
    $item = ServiceItem::findOrFail($id);
    $item->delete();

    return back()->with('success', 'Item deleted successfully');
}
public function update(Request $request, $id)
{
    $item = ServiceItem::findOrFail($id);

    $item->update([
        'item_name' => $request->item_name,
        'default_price' => $request->price,
        'unit' => $request->unit,
    ]);

    return back()->with('success', 'Item updated');
}
}
