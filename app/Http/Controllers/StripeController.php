<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;

class StripeController extends Controller
{
    public function __construct(StripeService $StripeService)
    {
        $this->StripeService = $this->StripeService;
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required',
            'product_price' => 'required',
            'product_name' => 'required',
            'source'=>'required'
        ]);
        return $this->StripeService->confirm($data);
    }
}
