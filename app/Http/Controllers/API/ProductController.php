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
        $products = Product::latest()->with('category')->paginate(10);

        return ProductResource::collection($products);
    }

    public function products_by_category(Category $category){
        $products = Product::where('category_id', $category->id)->paginate(10);

        if($products->count() > 0){
            return ProductResource::collection($products);
        }
        return response()->json(['message' => 'No products found in this category'], 404);
    }

    public function sort_price($sort_by){
        $products = Product::orderBy('price', $sort_by)->paginate(10);

        return ProductResource::collection($products);
    }

    public function product_detail(Product $product){
        return new ProductDetailResource($product);
    }
}
