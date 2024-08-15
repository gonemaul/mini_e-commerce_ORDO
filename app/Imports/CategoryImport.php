<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CategoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
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
        try{
            $category = Category::where('name', $row['name'])->first();
            if(!$category){
                $category = Category::create([
                    'name' => Str::title($row['name'])
                ]);
                return $category;
            }
            else{
                throw new \Exception('Category already exists');
            }
        }
        catch(\Exception $e){
            $this->errors[] = "Row {$this->row_count}  {$e->getMessage()}";
            return null;
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'name.max' => 'Kolom nama maksimal 250 karakter.',
            'name.string' => 'Kolom nama harus berupa teks.',
            'name.regex' => 'Kolom hanya boleh mengandung huruf dan spasi...',
        ];
    }
}