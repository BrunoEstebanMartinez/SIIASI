<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regFuncionModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatFunciones implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_PROCESO',
            'PROCESO',            
            'ID_FUNCION',
            'FUNCION',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
         return regfuncionModel::join('OFIPA_CAT_PROCESOS','OFIPA_CAT_PROCESOS.PROCESO_ID','=','OFIPA_CAT_FUNCIONES.PROCESO_ID')
                            ->select('OFIPA_CAT_FUNCIONES.PROCESO_ID','OFIPA_CAT_PROCESOS.PROCESO_DESC','OFIPA_CAT_FUNCIONES.FUNCION_ID','OFIPA_CAT_FUNCIONES.FUNCION_DESC','OFIPA_CAT_FUNCIONES.FUNCION_STATUS','OFIPA_CAT_FUNCIONES.FUNCION_FECREG')
                            ->orderBy('OFIPA_CAT_FUNCIONES.PROCESO_ID','ASC')
                            ->orderBy('OFIPA_CAT_FUNCIONES.FUNCION_ID','ASC')
                            ->get();                               
    }
}
