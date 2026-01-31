<?php

namespace App\Http\Controllers;

use App\Models\SalesExecutive;
use App\Models\Designer;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
   public function store(Request $request)
{
    if ($request->type === 'marketing') {

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'email'      => 'required|email',
            'address'    => 'nullable|string',
        ]);

        SalesExecutive::create($data);

    } elseif ($request->type === 'designer') {

        $data = $request->validate([
            'designer_name'  => 'required|string|max:255',
            'designer_no'    => 'nullable|string|max:20',
            'designer_email' => 'required|email',
            'designer_address'        => 'nullable|string',
        ]);

        Designer::create($data);
    }

    return response()->json(['success' => true]);
}

}

