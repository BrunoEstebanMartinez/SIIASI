<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regMediosModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportMedios implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'MEDIO_ID',
            'MEDIO_INFORMATIVO',
            'LINK_URL',
            'DIRECTOR_REPRESENTANTE',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regmedio=regMediosModel::select('MEDIO_ID','MEDIO_DESC','MEDIO_LINK',
                                                     'MEDIO_OBS1','FECREG')
                         ->orderBy('MEDIO_DESC','ASC')
                         ->get();                                
    }
}
