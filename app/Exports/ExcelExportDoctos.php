<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPerModel;
use App\regFormatosModel;
use App\regDoctosModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportDoctos implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_DOCUMENTO',
            'DOCUMENTO',            
            'ARCHIVO',
            'ID_FORMATO',
            'FORMATO',
            'ID_PER',
            'PERIODICIDAD',            
            'FREC_ENTREGA_ANUAL',
            'ESTADO',
            'CONTROL',
            'TIPO',
            'OBSERVACIONES'
        ];
    }

    public function collection()
    {
         return regDoctosModel::join('PE_CAT_FORMATOS'    ,'PE_CAT_FORMATOS.FORMATO_ID','=','PE_CAT_DOCUMENTOS.FORMATO_ID')
                              ->join('PE_CAT_PERIODICIDAD','PE_CAT_PERIODICIDAD.PER_ID','=','PE_CAT_DOCUMENTOS.PER_ID')                               
                ->select( 'PE_CAT_DOCUMENTOS.DOC_ID'      ,'PE_CAT_DOCUMENTOS.DOC_DESC'   ,'PE_CAT_DOCUMENTOS.DOC_FILE',
                          'PE_CAT_DOCUMENTOS.FORMATO_ID'  ,'PE_CAT_FORMATOS.FORMATO_DESC' ,
                          'PE_CAT_DOCUMENTOS.PER_ID'      ,'PE_CAT_PERIODICIDAD.PER_DESC' ,'PE_CAT_DOCUMENTOS.PER_FREC',
                          'PE_CAT_DOCUMENTOS.DOC_STATUS'  ,'PE_CAT_DOCUMENTOS.DOC_STATUS2','PE_CAT_DOCUMENTOS.DOC_STATUS3',
                          'PE_CAT_DOCUMENTOS.DOC_OBS')
                ->orderBy('PE_CAT_DOCUMENTOS.DOC_ID'      ,'ASC')
                ->get();                               
    }
}
