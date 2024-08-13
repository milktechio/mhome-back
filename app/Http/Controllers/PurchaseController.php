<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseController\StoreRequest;
use App\Http\Requests\PurchaseController\StatusRequest;
use App\Http\Requests\PurchaseController\UpdateRequest;
use App\Http\Requests\PurchaseController\CommentRequest;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    public function __construct(PurchaseService $PurchaseService)
    {
        $this->PurchaseService = $PurchaseService;
    }

    public function index(Request $request)
    {
        
        return $this->PurchaseService->query($request->query());
    }

    public function create()
    {
        //
    }

    public function store(StoreRequest $request)
    {
        return $this->PurchaseService->store($request->validated());
    }

   
    public function show(Purchase $purchase)
    {
        $purchase->comments;
        return ok('',$purchase);
    }

    public function edit(Purchase $purchase)
    {
        //
    }

    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    public function destroy(Purchase $purchase)
    {
        //
    }

    public function statusBuyer(StatusRequest $request, Purchase $purchase)
    {
        return $this->PurchaseService->statusBuyer($request->validated(), $purchase);
    }

    public function comment(CommentRequest $request, Purchase $purchase)
    {
        return $this->PurchaseService->comment($request->validated(), $purchase);
    }
}
