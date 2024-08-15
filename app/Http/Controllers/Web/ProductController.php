<?php

namespace App\Http\Controllers\Web;

use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loadData(){
        $products = Product::with('category')->select(['id','category_id','name','price','stock']);
        return DataTables::of($products)
        ->addIndexColumn()
        ->addColumn('price',function($products){
            return 'Rp. '.number_format($products->price, 0, ',', '.');
        })
        ->addColumn('category',function($products){
            return $products->category->name;
        })
        ->addColumn('action', function($products){
            return '<a href="'.route('products.show', $products->id) .'" class="btn btn-outline-primary" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-eye"></i> Detail</a>
                    <a href="'.route('products.edit', $products->id) .'" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                    <form action="'. route('products.destroy', $products->id) .'" method="post">
                    '.method_field('DELETE').'
                    '.csrf_field().'
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\'What are you sure? ..\');" style="font-size:1rem"><i class="fa-solid fa-trash"></i> Delete</button></form>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    public function index()
    {
        return view('products.index')->with([
            'title' => 'Product',
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
        $request->validate([
            'name' => ['required','string','max:250','regex:/^[\pL\s]+$/u','unique:products,name'],
            'category' => ['required'],
            'price' => ['required', 'numeric','max:10000000','min:1'],
            'stock' => ['required', 'numeric','max:1000','min:1'],
            'description' => ['required','max:300'],
        ],['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);

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
            foreach ($images as $path) {
                $product_image = ProductImage::where('path', $path)->first();
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

        $request->validate([
            'name' => ['required','max:250','string','regex:/^[\pL\s]+$/u'],
            'category' => ['required'],
            'price' => ['required', 'numeric','max:10000000','min:1'],
            'stock' => ['required', 'numeric','max:1000','min:1'],
            'description' => ['required','max:300'],
            'removed' => ['array']
        ],['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);
        $product->update([
            'name' => Str::title($request->name),
            'category_id' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        if($request->has('path_image') && $request->input('path_image') !== null) {
            $images = json_decode($request->path_image, true);
            foreach ($images as $path) {
                $product_image = ProductImage::where('path', $path)->first();
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
            Storage::delete($img->path);
            $img->delete();
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function templates()
    {
        $path = '/template_import/product.xlsx';
        return Storage::download($path, 'Template_Import_Product.xlsx');
    }

    public function import(Request $request){
        $request->validate([
            'file_up' => ['required','mimes:xlsx,xls']
        ]);

        $path = $request->file('file_up')->store('imports/products');
        $import = new ProductImport();
        $import->import($path);
        $errors = $import->errors;
        Storage::delete($path);
        if($import->failures()->isNotEmpty() || !empty($errors)){
            $alerts = [];
            collect($import->failures())->map(function($failure) use (&$alerts){
                $alerts[] = "Row {$failure->row()}  {$failure->errors()[0] }";
            });

            $allAlerts = array_merge($alerts, $errors);

            return redirect()->back()->with(['alerts' => $allAlerts]);
        }
        return redirect()->back()->with('success', 'Data berhasil diimport.');
    }
    public function export(){
        $name = 'Product_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new ProductExport(), $name);
    }
}
