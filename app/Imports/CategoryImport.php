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

class CategoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return $row['name'];
        return new Category([
            'name' => Str::title($row['name'])
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255|unique:categories,name|regex:/^[\pL\s]+$/u',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'name.max' => 'Kolom nama maksimal 250 karakter.',
            'name.string' => 'Kolom nama harus berupa teks.',
            'name.regex' => 'Kolom hanya boleh mengandung huruf dan spasi...',
            'name.unique' => 'Data sudah tersedia.',
        ];
    }
}