<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromArray;
use Modules\Wallet\Entities\Wallet;

class MyPartExport implements FromArray,WithHeadings,WithColumnFormatting
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
    }

    public function headings(): array
    {
        return [
            'شماره بارنامه',
            'نوع پرداخت',
            'مبلغ فاکتور',
            'وضعیت',
            'مبلغ بیمه',
            'خدمات در مبدا',
            'هزینه بسته بندی',
            'وزن مرسوله',
            'وزن قابل پرداخت',
            'وزن حجمی',
            'تعداد پارت',
            'نام فرستنده',
            'شهر فرستنده',
            'نام گیرنده',
            'شهر گیرنده',
            'تاریخ',
            'ساعت',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'B' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'C' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'D' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'E' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL  ,
            'F' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'G' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'H' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'I' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'J' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'K' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'L' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'M' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'N' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'O' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'P' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,
            'Q' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL ,

        ];
    }
}
