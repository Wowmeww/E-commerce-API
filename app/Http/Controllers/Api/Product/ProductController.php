<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
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

    public function show(Request $request, Product $product)
    {


        return ApiResponse::success(data: $product);
    }


}
