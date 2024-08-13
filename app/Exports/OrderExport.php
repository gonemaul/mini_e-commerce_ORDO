<?php

namespace App\Exports;

use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromQuery , WithHeadings, WithCustomStartCell, ShouldAutoSize, WithStyles, WithMapping
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
            'Order ID',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'City',
            'Postal Code',
            'Total',
            'Status',
            'Order Items'
        ];
    }

    public function query()
    {
        return Order::query()->with('orderItems');
    }
    public function map($orders): array
    {
        return [
            $orders->order_id,
            $orders->user->name,
            $orders->user->email,
            $orders->phone,
            $orders->address,
            $orders->city,
            $orders->postal_code,
            $orders->total,
            $orders->status,
            $orders->orderItems->map(function($item) {
                return $item->product_name . ' ( ' . $item->quantity . ')';
            })->implode(', ')
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // Get the highest row number
        $highestColumn = $sheet->getHighestColumn(); // Get the highest column letter

        // Apply border style to the entire table range
        $sheet->getStyle('B2:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $sheet->getStyle('B2:K2')->applyFromArray([
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
            'B' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'H' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'J' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'I' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0', // Number format with 2 decimal places
                ],
            ]
        ];
    }
}