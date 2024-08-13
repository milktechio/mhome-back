<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function query($query)
    {
        return $this->ProductRepository->query(Product::class, $query);
    }

    public function store($data)
    {
        return $this->ProductRepository->store($data);
    }

    public function update($data, Product $product)
    {
        return $this->ProductRepository->update($data, $product);
    }

    public function updateImage($product, $data)
    {
        return $this->ProductRepository->updateImage($product, $data);
    }
}
