<?php

namespace App\Exports;

use App\Models\User;
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

class AdminExport implements FromQuery, WithHeadings, WithCustomStartCell, ShouldAutoSize, WithStyles, WithMapping
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
            'ID',
            'Name',
            'Email',
            'Last Login',
            'Profile Path',
        ];
    }
    public function query()
    {
        return User::where('is_admin', true);
    }
    public function map($user): array
    {
        return [
            $user->id,
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
        $sheet->getStyle('B2:F2')->applyFromArray([
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
