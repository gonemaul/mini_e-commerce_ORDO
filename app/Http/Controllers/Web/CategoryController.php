<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
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
        return view('categories.partials.item_tabel')->with([
            'category' => Category::orderBy('created_at', 'desc')->with('products')->get()
        ]);
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
            'name' => ['required','string','max:250','regex:/^[\pL\s]+$/u'],
        ], ['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);

        Category::create([
            'name' => Str::title($request->name)
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
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

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $products = Product::where('category_id', $category->id)->first();
        if($products){
            return back()->with(['error' => 'Category already used']);
        }
        else{
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        }
    }
}
