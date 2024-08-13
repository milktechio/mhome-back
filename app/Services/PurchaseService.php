<?php

namespace App\Services;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use Auth;

class PurchaseService
{
    protected $PurchaseRepository;

    public function __construct(PurchaseRepository $PurchaseRepository)
    {
        $this->PurchaseRepository = $PurchaseRepository;
    }

    public function query($query)
    {
        return $this->PurchaseRepository->query(Purchase::class, $query, function ($query) {
            $query->where('user_id', Auth::user()->id)
            ->orWhere('seller_id', Auth::user()->id);

            return $query;
        });
    }

    public function store($data)
    {
        return $this->PurchaseRepository->store($data);
    }

    public function update($data, Purchase $purchase)
    {
        return $this->PurchaseRepository->update($data, $purchase);
    }

    public function statusBuyer($data, Purchase $purchase)
    {
        return $this->PurchaseRepository->statusBuyer($data, $purchase);
    }

    public function comment($data, $purchase)
    {
        return $this->PurchaseRepository->comment($data, $purchase);
    }
}
