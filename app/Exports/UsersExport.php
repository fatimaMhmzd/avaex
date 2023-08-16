<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UsersExport implements FromArray,WithHeadings,WithColumnFormatting
{
    //use Exportable;
    protected $invoices;
    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
    public function array(): array
    {
        return $this->invoices;
    }

    public function collection()
    {
        return $this->invoices;
        /*collect(
            [
            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ]
        );*/
    }

    public function headings(): array
    {
        return [
            'کد ملی',
            'نام',
            'نام خانوادگی',
            'شماره موبایل',
            'ایمیل',
            'وضعیت',
            'ادمین',
            'نوع',

        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT ,
            'B' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'C' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'D' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'E' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL  ,
            'F' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'G' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,

        ];
    }
}
