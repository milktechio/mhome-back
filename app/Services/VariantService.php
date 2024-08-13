<?php

namespace App\Services;

use App\Models\Variant;
use App\Repositories\VariantRepository;

class VariantService
{
    protected $VariantRepository;

    public function __construct(VariantRepository $VariantRepository)
    {
        $this->VariantRepository = $VariantRepository;
    }

    public function query($query)
    {
        return $this->VariantRepository->query(Variant::class, $query);
    }

    public function store($data)
    {
        return $this->VariantRepository->store($data);
    }

    public function update($data, Variant $product)
    {
        return $this->VariantRepository->update($data, $product);
    }

    public function updateImage($data, Variant $variant)
    {
        return $this->VariantRepository->updateImage( $data, $variant);
    } 
}
