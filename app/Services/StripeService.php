<?php

namespace App\Services;

use Auth;
use App\Models\Stripe;
use App\Repositories\StripeRepository;

class StripeService
{
    private $apiContext;

    public function __construct(StripeRepository $StripeRepository)
    {
        $this->StripeRepository = $StripeRepository;
    }

    public function confirm($data)
    {
        $response = $this->StripeRepository->charge($data);

        return ok($response);
        if(!$response->status) {
            return setResponse($response);
        }

        $pay =Stripe::create([
            'product_id'=>$data['product_id'],
            'user_id'=>Auth::user()->id,
            'price'=>$data['product_price'],
            'stripe_id'=>$response->data->id,
            'balance_transaction'=>$response->data->balance_transaction
        ]);

        return ok('Pago generado correctamente', $pay);
    }

    public function createPrice($variant)
    {
        return $this->StripeRepository->createPrice($variant);
    }
}
