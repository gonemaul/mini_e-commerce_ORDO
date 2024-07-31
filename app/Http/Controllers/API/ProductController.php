<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;

class ProductController extends Controller
{
    public function products(){
        try {
            $products = Product::latest()->with('category')->paginate(10);

            return response()->json([
                'data' => ProductResource::collection($products),
                'meta' => [
                        'pagination' => [
                            'total' => $products->total(),
                            'per_page' => $products->perPage(),
                            'current_page' => $products->currentPage(),
                            'last_page' => $products->lastPage(),
                        ]
                ],
                'status' => 'success',
                'message' => 'Data retrieved successfully',
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'meta' => null,
                'status' => 'error',
                'message' => 'An error occurred',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function products_by_category(Category $category){
        try {
            $products = Product::where('category_id', $category->id)->paginate(10);
            return ProductResource::collection($products);
        } catch (\Exception $e){
            return response()->json([
                'data' => null,
                'meta' => null,
                'status' => 'error',
                'message' => 'An error occurred',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function sort_price(Request $request){
        $sort_by = $request->sort_by;
        try {
            $products = Product::orderBy('price', $sort_by)->paginate(10);

            return ProductResource::collection($products);
        } catch (\Exception $e){
            return response()->json([
                'data' => null,
                'meta' => null,
                'status' => 'error',
                'message' => 'An error occurred',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function product_detail(Product $product){
        if($product){
            return response()->json([
                'data' => new ProductDetailResource($product),
                'status' => 'success',
                'message' => 'Product retrieved successfully',
            ]);
        }
        return response()->json([
            'data' => null,
            'meta' => null,
            'status' => 'error',
            'message' => 'An error occurred',
        ], 500);
    }
}
