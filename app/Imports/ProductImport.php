<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return dd($row);
        $category = Category::where('name', Str::title($row['category']))->firstOrFail();
        $product = Product::firstOrCreate([
            'name' => Str::title($row['name']),
        ],[
            'description' => $row['description'],
            'price' => $row['price'],
            'category_id' => $category->id,
            'stock' => $row['stock'],
            'sold' => $row['sold'],
        ]);

        $images = explode(', ', $row['image']);

        foreach ($images as $image){
            $path = parse_url($image);
            ProductImage::create([
                'product_id' => $product->id,
                'path' => ltrim($path['path'], '/storage/')
            ]);
        }

        return $product;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            '*.description' => 'required|max:300',
            '*.price' => 'required|numeric|max:10000000|min:1',
            '*.stock' => 'required|numeric|max:1000|min:1',
            '*.sold' => 'nullable|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'name.max' => 'Kolom nama maksimal 250 karakter.',
            'name.string' => 'Kolom nama harus berupa teks.',
            'price.max' => 'Price maximum 10.000.000',
            'stock.max' => 'Price maximum 1000',
            'name.regex' => 'Kolom hanya boleh mengandung huruf dan spasi...',
        ];
    }

    private function uploadImage($image)
    {
        if ($image) {
            // $imageName = time();
            // $image->storeAs('public/product_image', $imageName);
            // return 'product_image/' . $imageName;
            return $image;
        }

        return null;
    }
}