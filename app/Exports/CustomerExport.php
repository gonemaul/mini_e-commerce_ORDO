<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CustomerExport implements FromQuery, WithHeadings, WithCustomStartCell, ShouldAutoSize, WithStyles
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
            'Email',
            'Last Login',
            'Profile Path',
        ];
    }
    public function query()
    {
        return User::where('is_admin', false)->select(['name', 'email', 'last_login', 'profile_image']);
    }
    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->last_login,
            $user->profile_image ? url('storage/' . $user->profile_image)
            : 'No Image',
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
        $sheet->getStyle('B2:E2')->applyFromArray([
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
    }
}