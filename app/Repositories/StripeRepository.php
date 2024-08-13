<?php

namespace App\Repositories;

use Auth;

class StripeRepository
{

    public function charge($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));

        try {

            $paymentIntent = $stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $data['price_id'],
                        'quantity' => 1,
                    ],
                ],
            ]);

            return [
                'status' => true,
                'data' => $paymentIntent
            ];
        } catch (\Stripe\Exception\CardException $e) {
            return [
                'status' => false,
                'msg' => $e->getError()->message,
                'code' => $e->getError()->code,
                'data' => [
                    $e->getError()->param
                ]
            ];
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return [
                'status' => false,
                'code' => 500,
                'msg' => 'Hubo un error inesperado',
                'data' => $e
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'status' => false,
                'code' => 500,
                'msg' => 'Hubo un error inesperado',
                'data' => $e
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'code' => 500,
                'msg' => 'Hubo un error inesperado',
                'data' => $e
            ];
        }
    }

    public function createPrice($variant)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));

        if (isset($variant->recurring)) {
            $recurring = ['interval' => $variant->recurring];
        }
        $price = $stripe->prices->create([
            'currency' => $variant->currency,
            'unit_amount' => $variant->price * 100,
            'recurring' => $recurring ?? '',
            'product_data' => ['name' => $variant->name],
        ]);

        $variant->stripe_price_id = $price->id;
        $variant->save();

        return $variant;
    }
}
