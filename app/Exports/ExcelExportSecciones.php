<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regSeccionesModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportSecciones implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'SECCION_ID',
            'SECCION',
            'SECCION_TIPO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regseccion=regSeccionesModel::select('SECCION_ID','SECCION_DESC','SECCION_TIPO',
                                                     'SECCION_STATUS','SECCION_FECREG')
                           ->orderBy('SECCION_TIPO','DESC')
                           ->orderBy('SECCION_ID'  ,'ASC')
                           ->get();                                
    }
}
