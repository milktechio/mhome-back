<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Commission;
use App\Models\CompanyCommission as Company;
use App\Traits\PaginateRepository;
use Auth;

class ProductRepository
{
    use PaginateRepository;

    public function store($data)
    {
        $data['user_id'] = Auth::user()->id;
        $product = Product::create($data);
        $product->storeImage($data['image'], 'image_url');

        return ok('', $product);
    }

    public function update($data, Product $product)
    {
        foreach ($data as $key => $data) {
            $product->$key = $data;
        }

        $product->save();

        return ok('Actualizado correctamente', $product);
    }


    public function updateImage(Product $product, $data)
    {
        $product->unlink('image_url');
        $product->storeImage($data['image'], 'image_url');

        return ok('Actualizado correctamente', $product);
    }


}
