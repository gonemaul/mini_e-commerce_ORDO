<?php

namespace App\Exports;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ProductExport implements FromQuery , WithHeadings, WithCustomStartCell, ShouldAutoSize, WithStyles, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function startCell(): string
    {
        return 'B2';
    }
    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Stock',
            'Sold',
            'Category',
            'Product Images'
        ];
    }
    public function query()
    {
        return Product::query()->with('productImage');
    }

    public function map($product): array
    {
        // Menentukan mapping data yang akan diekspor
        return [
            $product->name,
            $product->description = strip_tags($product->description),
            $product->price,
            $product->stock,
            $product->sold,
            $product->category->name ?? 'N/A',  // Mengakses relasi kategori
            $product->productImage->map(function($image){
                return url('storage/'. $image->path);
            })->implode(', ') // Menggabungkan path gambar ke dalam satu string
        ];
    }
    public function styles(Worksheet $sheet){
        $highestRow = $sheet->getHighestRow(); // Get the highest row number
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('B2:H2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'],
            ],
            'font' => [
                'bold' => true,
                'size' => 13,
            ]
        ]);
        return [
            'D' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0', // Number format with 2 decimal places
                ],
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'G' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}