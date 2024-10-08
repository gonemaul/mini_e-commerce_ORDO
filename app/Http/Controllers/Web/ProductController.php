<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Support\Carbon;
use App\Notifications\NewProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NewProductImport;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:product_view|product_edit|product_delete', only: ['load_data','show']),
            new Middleware('permission:product_create', only: ['create','store']),
            new Middleware('permission:product_edit', only: ['edit','update']),
            new Middleware('permission:product_delete', only: ['destroy']),
            new Middleware('permission:product_exim', only: ['templates','import','export']),
            new Middleware('permission:product_exim|product_delete|product_edit|product_create|product_view', only: ['index']),
        ];
    }
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
            $detail = '';
            $edit = '';
            $delete = '';

            if(Gate::allows('product_view')){
                $detail = '<a href="'.route('products.show', $products->id) .'" class="btn btn-outline-primary" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-eye"></i> Detail</a>';
            }
            if(Gate::allows('product_edit')){
                $edit = '<a href="'.route('products.edit', $products->id) .'" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
            }
            if(Gate::allows('product_delete')){
                $delete = '<form action="'. route('products.destroy', $products->id) .'" method="post">
                    '.method_field('DELETE').'
                    '.csrf_field().'
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\''.__('general.alert_delete').'\');" style="font-size:1rem"><i class="fa-solid fa-trash"></i>'.__('general.delete').'</button></form>';
            }
            return $detail . $edit .$delete;
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

        $product_id = $product->orderBy('id', 'desc')->first();
        if($request->has('path_image') && $request->input('path_image') !== null) {
            $images = json_decode($request->path_image, true);
            foreach ($images as $path) {
                $product_image = ProductImage::where('path', $path)->first();
                $product_image->update(['product_id' => $product_id->id]);
            }
        }

        $user = User::where('is_admin', false)->get();
        if($user){
            Notification::send($user,new NewProduct($product_id,$request->category));
        }
        return redirect()->route('products.index')->with('success', __('product.create.success'));
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

        return redirect()->route('products.index')->with('success', __('product.update.success'));
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

        return redirect()->route('products.index')->with('success', __('product.delete.success'));
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
        $user = User::where('is_admin', false)->get();
        if($user){
            Notification::send($user,new NewProductImport());
        }
        return redirect()->back()->with('success', __('product.import.success'));
    }
    public function export(){
        $name = 'Product_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new ProductExport(), $name);
    }
}