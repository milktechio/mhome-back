<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductController\StoreRequest;
use App\Http\Requests\ProductController\UpdateRequest;
use App\Http\Requests\Common\ImageRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(ProductService $ProductService)
    {
        $this->ProductService = $ProductService;
    }
    public function index(Request $request)
    {
        return $this->ProductService->query($request->query());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->ProductService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return ok('', $product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product)
    {
        return $this->ProductService->update($request->validated(), $product);
    }

    public function updateImage(ImageRequest $request, Product $product)
    {
        return $this->ProductService->updateImage($product, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return ok('Eliminado correctamente');
    }
}
