<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;
use App\Http\Requests\VariantController\StoreRequest;
use App\Http\Requests\VariantController\UpdateRequest;
use App\Http\Requests\VariantController\UpdateImageRequest;
use App\Services\VariantService;

class VariantController extends Controller
{
    public function __construct(VariantService $VariantService)
    {
        $this->VariantService = $VariantService;
    }
    public function index(Request $request)
    {
        return $this->VariantService->query($request->query());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->VariantService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        return ok('', $variant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Variant $variant)
    {
        return $this->VariantService->update($request->validated(), $variant);
    }

  
    public function updateImage(UpdateImageRequest $request, Variant $variant)
    {
        return $this->VariantService->updateImage( $request->validated(), $variant);
    }  /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {
        $variant->delete();

        return ok('Producto eliminado correctamente');
    }
}
