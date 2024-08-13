<?php

namespace App\Repositories;

use App\Models\Variant;
use App\Models\Commission;
use App\Models\Product;
use App\Traits\PaginateRepository;
use Auth;
use App\Services\StripeService;

class VariantRepository
{
    use PaginateRepository;

    public function __construct(StripeService $StripeService)
    {
        $this->StripeService = $StripeService;
    }
    public function store($data)
    {
        $product = Product::find($data['product_id']);

        $data['created_by'] = Auth::user()->id;
        $data['product_id'] = $product->id;

        $variant = Variant::create($data);
        $variant->storeImage($data['image'], 'image_url');
        $variant = $this->StripeService->createPrice($variant);

        return ok('Creada correctamente', $variant);
    }

    public function updateImage($data, $variant)
    {
        $variant->unlink('image_url');
        $variant->storeImage($data['image'], 'image_url');

        return ok('Imagen cambiada correctamente', $variant);
    }

    public function update($data, Variant $variant)
    {
        foreach ($data as $key => $data) {
            $variant->$key = $data;
        }

        $variant->save();

        return ok('Actualizado correctamente', $variant);
    }
}
