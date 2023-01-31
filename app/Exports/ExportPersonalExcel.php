<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPersonalModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPersonalExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'U_ADMON',
            'FOLIO',
            'APELLIDO_PATERNO',
            'APELLIDO_MATERNO',
            'NOMBRES',
            'CURP',
            'SEXO',
            'DOMICILIO',
            'CP',            
            'COLONIA',
            'LOCALIDAD',
            'TELEFONO',            
            'CORREO_ELECTRÓNICO', 
            'PUESTO',
            'ACTIVIDADES',
            'STATUS',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //return regPadronModel::join('JP_CAT_MUNICIPIOS_SEDESEM','JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=',
            //                                                        'OFIPA_PERSONAL.MUNICIPIO_ID') 
            //                ->wherein('JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regPersonalModel::join('OFIPA_CAT_UADMON','OFIPA_CAT_UADMON.UADMON_ID' ,'=','OFIPA_PERSONAL.UADMON_ID')
                            ->select(
                                     'OFIPA_CAT_UADMON.UADMON_DESC',  
                                     'OFIPA_PERSONAL.FOLIO',
                                     'OFIPA_PERSONAL.PRIMER_APELLIDO',
                                     'OFIPA_PERSONAL.SEGUNDO_APELLIDO',
                                     'OFIPA_PERSONAL.NOMBRES',
                                     'OFIPA_PERSONAL.CURP',     
                                     'OFIPA_PERSONAL.SEXO',     
                                     'OFIPA_PERSONAL.DOMICILIO',     
                                     'OFIPA_PERSONAL.CP', 
                                     'OFIPA_PERSONAL.COLONIA',
                                     'OFIPA_PERSONAL.LOCALIDAD',         
                                     'OFIPA_PERSONAL.TELEFONO', 
                                     'OFIPA_PERSONAL.E_MAIL',  
                                     'OFIPA_PERSONAL.PUESTO',
                                     'OFIPA_PERSONAL.OBS_1', 
                                     'OFIPA_PERSONAL.STATUS_1',  
                                     'OFIPA_PERSONAL.FECHA_REG'
                                    )
                            ->orderBy('OFIPA_PERSONAL.NOMBRE_COMPLETO','ASC')
                            ->get();                               
        }else{
            return regPersonalModel::join('OFIPA_CAT_UADMON','OFIPA_CAT_UADMON.UADMON_ID' ,'=','OFIPA_PERSONAL.UADMON_ID')
                            ->select(
                                     'OFIPA_CAT_UADMON.UADMON_DESC',  
                                     'OFIPA_PERSONAL.FOLIO',
                                     'OFIPA_PERSONAL.PRIMER_APELLIDO',
                                     'OFIPA_PERSONAL.SEGUNDO_APELLIDO',
                                     'OFIPA_PERSONAL.NOMBRES',
                                     'OFIPA_PERSONAL.CURP',     
                                     'OFIPA_PERSONAL.SEXO',     
                                     'OFIPA_PERSONAL.DOMICILIO',     
                                     'OFIPA_PERSONAL.CP', 
                                     'OFIPA_PERSONAL.COLONIA',
                                     'OFIPA_PERSONAL.LOCALIDAD',   
                                     'OFIPA_PERSONAL.TELEFONO', 
                                     'OFIPA_PERSONAL.E_MAIL',  
                                     'OFIPA_PERSONAL.PUESTO',
                                     'OFIPA_PERSONAL.OBS_1', 
                                     'OFIPA_PERSONAL.STATUS_1',
                                     'OFIPA_PERSONAL.FECHA_REG'
                                    )
                            ->where(  'OFIPA_PERSONAL.UADMON_ID',$arbol_id)
                            ->orderBy('OFIPA_PERSONAL.NOMBRE_COMPLETO','ASC')
                            ->get();               
        }                            
    }
}
