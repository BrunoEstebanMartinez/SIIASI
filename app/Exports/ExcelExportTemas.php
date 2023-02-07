<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
//use App\regSeccionesModel;
use App\regtemaModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportTemas implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'NO',
            'ID',
            'TEMÁTICA',
            'TEMÁTICA_CORTA',
            'ID_SECCION',
            'SECCION',
            'SECCION_TIPO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regtema = regTemaModel::join('OFIPA_CAT_FD_SECCIONES as A','A.SECCION_ID','=','OFIPA_CAT_TEMAS.SECCION_ID')
                          ->select('OFIPA_CAT_TEMAS.TEMA_NO',    
                                   'OFIPA_CAT_TEMAS.TEMA_ID',    
                                   'OFIPA_CAT_TEMAS.TEMA_DESC', 
                                   'OFIPA_CAT_TEMAS.TEMA_DESC_CORTA', 
                                   'A.SECCION_ID',
                                   'A.SECCION_DESC',     
                                   'A.SECCION_TIPO',                              
                                   'OFIPA_CAT_TEMAS.TEMA_STATUS',
                                   'OFIPA_CAT_TEMAS.TEMA_FECREG')
                         ->orderBy('OFIPA_CAT_TEMAS.TEMA_ID','ASC')
                         ->get();                                
    }
}
