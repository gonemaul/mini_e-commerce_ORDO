<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure,SkipsEmptyRows
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $errors = [];
    private $row_count = 0;
    public function model(array $row)
    {
        $this->row_count++;
        try {
            $category = Category::where('name', Str::title($row['category']))->firstOrFail();
            if (!$category){
                throw new \Exception('Category not found');
            }

            $product = Product::where('name', Str::title($row['name']))->first();
            if ($product){
                throw new \Exception('Product already exists');
            }
            else{
                $product = Product::create([
                    'name' => Str::title($row['name']),
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'category_id' => $category->id,
                    'stock' => $row['stock'],
                    'sold' => $row['sold'],
                ]);
            }
            $this->processImage($row,$product);

            return $product;
        }catch (\Exception $e) {
            $this->errors[] = "Row {$this->row_count}  {$e->getMessage()}";
            return null;
        }
    }

    public function processImage($row,$product){
        if(!empty($row['image'])){
            $images = explode(', ', $row['image']);

            foreach ($images as $image){
                $image = trim($image);
                $newImage = $this->getImage($image);
                if($newImage){
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $newImage,
                    ]);
                }
            }
        }

    }

    public function getImage($path){
        try{
            $img = file_get_contents($path);
            $filename = Str::random(40);
            if($img){
                Storage::put('product_image/'. $filename, $img);
                return 'product_image/'.$filename;
            }
        }
        catch(\Exception $e){
            Log::error('Product image not found');
        }

        $this->errors[] = 'Failed to process product image';
        return null;
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
            '*.category' => 'required|exists:categories,name',
            'images' => 'nullable|string'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'price.max' => 'Price maximum 10.000.000',
            'stock.max' => 'Price maximum 1000',
            'name.regex' => 'Kolom hanya boleh mengandung huruf dan spasi...',
        ];
    }
}
