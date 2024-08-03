<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index')->with([
            'title' => 'Product',
            'products' => Product::with('category')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create')->with([
            'title' => 'Create Product',
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return dd($request);
        $request->validate([
            'name' => ['required','string','max:250','regex:/^[\pL\s]+$/u'],
            'category' => ['required'],
            'price' => ['required','numeric'],
            'stock' => ['required','numeric'],
            'description' => ['required','max:300'],
        ]);

        $product = new Product();
        $product->create([
            'name' => Str::title($request->name),
            'category_id' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        if($request->has('path_image') && $request->input('path_image') !== null) {
            $product_id = $product->orderBy('id', 'desc')->first();
            $images = json_decode($request->path_image, true);
            foreach ($images as $image) {
                $product_image = ProductImage::where('image', $image)->first();
                $product_image->update(['product_id' => $product_id->id]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.detail')->with([
            'title' => 'Product Details',
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit')->with([
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // return dd($request);

        $validatedData = $request->validate([
            'name' => ['required','max:250','string','regex:/^[\pL\s]+$/u'],
            'category' => ['required'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'description' => ['required','max:300'],
            'images' => ['array'],
            'images.*' => ['image','max:1024','mimes:jpeg,png,jpg,gif,svg',],
            'removed' => ['array']
        ]);
        $product->update([
            'name' => Str::title($request->name),
            'category_id' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        if($request->has('path_image') && $request->input('path_image') !== null) {
            $images = json_decode($request->path_image, true);
            foreach ($images as $image) {
                $product_image = ProductImage::where('image', $image)->first();
                $product_image->update(['product_id' => $product->id]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $image = $product->productImage;
        foreach ($image as $img) {
            Storage::delete($img->image);
            $img->delete();
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
