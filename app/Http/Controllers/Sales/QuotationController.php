<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Lead $lead)
{
    if (!$lead->siteVisit || $lead->siteVisit->approval_status !== 'Yes') {
        abort(403);
    }

    $pdf = Pdf::loadView('sales_executive.pdf.quotation', [
        'lead' => $lead
    ])->setPaper('a4');

    return $pdf->stream('Quotation-' . $lead->id . '.pdf');
}
}
