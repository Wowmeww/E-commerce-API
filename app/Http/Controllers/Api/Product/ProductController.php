<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Api\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate($request->integer('per_page'));

        return ApiResponse::success(
            data: $products
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return ApiResponse::success(
            data: $product,
            message: 'Product created successfully.',
            status: 201
        );
    }

    public function show(Request $request, Product $product)
    {
        return ApiResponse::success(data: $product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return ApiResponse::success(
            data: $product->fresh(),
            message: 'Product updated successfully.'
        );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return ApiResponse::success(
            message: 'Product deleted successfully.'
        );
    }
}
