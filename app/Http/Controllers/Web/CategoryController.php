<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:category_view', only: ['load_data','show']),
            new Middleware('permission:category_create', only: ['create','store']),
            new Middleware('permission:category_edit', only: ['edit','update']),
            new Middleware('permission:category_delete', only: ['destroy']),
            new Middleware('permission:category_exim', only: ['templates','export','import']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index')->with([
            'title' => 'Category',
        ]);
    }

    public function load_data(){
        $categories = Category::select(['id','name']);
        return DataTables::of($categories)
        ->addIndexColumn()
        ->addColumn('count_product', function($categories){
            return count($categories->products);
        })
        ->addColumn('action', function($categories){
            return '<a href="'. route('categories.edit', $categories->id) .'" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                      <form action="'.route('categories.destroy', $categories->id) .'" method="post">
                          '.method_field('DELETE').'
                          '.csrf_field().'
                          <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\''.__('general.alert_delete').'\');" style="font-size:1rem"><i class="fa-solid fa-trash"></i>'. __('general.delete').' </button></form>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create')->with([
            'title' => 'Create Category'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:250','regex:/^[\pL\s]+$/u','unique:categories,name'],
        ], ['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);

        Category::create([
            'name' => Str::title($request->name)
        ]);

        return redirect()->route('categories.index')->with('success', __('category.create.success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit')->with([
            'title' => 'Edit Category',
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required','string','max:250','regex:/^[\pL\s]+$/u'],
        ], ['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);

        $category->update([
            'name' => Str::title($request->name)
        ]);

        return redirect()->route('categories.index')->with('success', __('category.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $products = Product::where('category_id', $category->id)->first();
        if($products){
            return back()->with(['error' => __('category.delete.error')]);
        }
        else{
            $category->delete();
            return redirect()->route('categories.index')->with('success', __('category.delete.success'));
        }
    }

    public function templates(){
        $path = '/template_import/category.xlsx';
        return Storage::download($path, 'Template_Import_Category.xlsx');
    }

    public function import(Request $request){
        $request->validate([
            'file_up' => ['required','mimes:xlsx,xls']
        ]);

        $path = $request->file('file_up')->store('imports/categories');
        $import = new CategoryImport();
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
            return redirect()->back()->with('success', __('category.import.success'));
    }

    public function export(){
        $name = 'Category_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new CategoryExport(), $name);
    }
}