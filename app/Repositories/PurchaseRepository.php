<?php

namespace App\Repositories;

use App\Models\Purchase;
use App\Models\PurchaseComment;
use App\Models\CompanyCommission;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Traits\PaginateRepository;
use Auth;
use App\Services\StripeService;

class PurchaseRepository
{
    use PaginateRepository;

    public function __construct(StripeService $StripeService)
    {
        $this->StripeService = $StripeService;
    }

    public function store($data)
    {
        $buyer_id = Auth::user()->id;
        $product = Product::find($data['product_id']);
        $variant = Variant::find($data['variant_id']);

        // if ($product->company_id) {
        //     $sellerId = $product->company_id;
        //     $company_id = $sellerId;
        // }else{
        //    $sellerId = $product->user_id;
        // }

        $total = $variant->price * $data['sold'];


        $dataBuy = [
            'product_id' => $data['product_id'] ,
            // 'company_id' => $company_id,
            'variant_id' => $data['variant_id'],
            'user_id' => $buyer_id,
            // 'seller_id' => $sellerId,
            'price' => $variant->price,
            'currency' => 'NST',
            'sold' => $data['sold'],
            'total' => ($total),
            'purchased_by' => Auth::user()->id,
            'status' => 'Pagado',
            'buyer_status' => 'Pagado',
            'payment' => $data['payment'],
        ];

        if ($data['payment'] == 'Paypal') {
            $data['product_price'] = $total;
            $data['product_name'] = 'test';

            $purchase = Purchase::create($dataBuy);

            $data['purchase_id']  = $purchase->id;
           return $this->PaypalService->link($data);
        }elseif ($data['payment'] == 'Stripe') {
            $purchase = Purchase::create($dataBuy);
            $data['price_id'] = $variant->stripe_price_id;
            return $this->StripeService->confirm($data);
        }elseif ($data['payment'] == 'Blockchain') {

            $transaction = Transactions::create([
                'user_id' => $user_id,
                'requested_user_id' => Auth::user()->id,
                'quantity' => $data['sold'] * $variant->price,
                'currency' => $variant->currency,
                'type_id' => TransactionType::getId('Purchase'),
                'transaction_hash' => $data['transactionHash'] ?? null,
                'transaction_index' => $data['transactionIndex'] ?? null,
            ]);

            $purchase = Purchase::create($dataBuy);
        }


        return ok('', $purchase);
    }

    public function update($data, Purchase $purchase)
    {
        foreach ($data as $key => $data) {
            $purchase->$key = $data;
        }

        $purchase->save();

        return ok('Actualizado correctamente', $purchase);
    }

    public function statusBuyer($data, Purchase $purchase)
    {
        $purchase->buyer_status = $data['status'];

        $purchase->save();

        return ok('Actualizado correctamente', $purchase);
    }

    public function comment($data, $purchase)
    {
        $data['user_id']= Auth::user()->id;
        $data['purchase_id']= $purchase->id;
        PurchaseComment::create($data);

        $purchase->comments;

        return ok('Comentario creado correctamente', $purchase);
    }
}
