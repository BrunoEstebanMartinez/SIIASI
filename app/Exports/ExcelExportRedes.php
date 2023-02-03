<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regRedsocialModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportRedes implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'REDSOCIAL_ID',
            'RED_SOCIAL',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regredes=regRedsocialModel::select('RS_ID','RS_DESC','FECREG')
                         ->orderBy('RS_DESC','ASC')
                         ->get();                                
    }
}
