<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPadronModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPadronExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'FOLIO',
            'OSC',
            'FECHA_INGRESO',
            'APELLIDO_PATERNO',
            'APELLIDO_MATERNO',
            'NOMBRES',
            'FECHA_NACIMIENTO',
            'CURP',
            'SEXO',
            'DOMICILIO',
            'CP',            
            'COLONIA',
            'ENTIDAD_FEDERATIVA',
            'MUNICIPIO',
            'TELEFONO',
            'MOTIVO_INGRESO',
            'INTEG_FAMILIA',
            'SERVICIO',
            'CUOTA_RECUP',
            'QUIEN_CANALIZO',
            'STATUS',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //return regPadronModel::join('PE_CAT_MUNICIPIOS_SEDESEM','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=',
            //                                                        'PE_METADATO_PADRON.MUNICIPIO_ID') 
            //                ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'PE_METADATO_PADRON.ENTIDAD_FED_ID')
                            ->join('PE_CAT_SERVICIOS' ,'PE_CAT_SERVICIOS.SERVICIO_ID','=','PE_METADATO_PADRON.SERVICIO_ID')
                            ->join('PE_OSC'           ,'PE_OSC.OSC_ID'               ,'=','PE_METADATO_PADRON.OSC_ID')
                            ->select('PE_METADATO_PADRON.FOLIO',
                                     'PE_OSC.OSC_DESC',  
                                     'PE_METADATO_PADRON.FECHA_INGRESO2', 
                                     'PE_METADATO_PADRON.PRIMER_APELLIDO',
                                     'PE_METADATO_PADRON.SEGUNDO_APELLIDO',
                                     'PE_METADATO_PADRON.NOMBRES',
                                     'PE_METADATO_PADRON.FECHA_NACIMIENTO2',     
                                     'PE_METADATO_PADRON.CURP',     
                                     'PE_METADATO_PADRON.SEXO',     
                                     'PE_METADATO_PADRON.DOMICILIO',     
                                     'PE_METADATO_PADRON.CP', 
                                     'PE_METADATO_PADRON.COLONIA',
                                     'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'PE_METADATO_PADRON.LOCALIDAD',         
                                     'PE_METADATO_PADRON.TELEFONO', 
                                     'PE_METADATO_PADRON.MOTIVO_ING',
                                     'PE_METADATO_PADRON.INTEG_FAM', 
                                     'PE_CAT_SERVICIOS.SERVICIO_DESC', 
                                     'PE_METADATO_PADRON.CUOTA_RECUP',
                                     'PE_METADATO_PADRON.QUIEN_CANALIZO', 
                                     'PE_METADATO_PADRON.STATUS_1',  
                                     'PE_METADATO_PADRON.FECHA_REG'
                                    )
                            ->orderBy('PE_METADATO_PADRON.NOMBRE_COMPLETO','ASC')
                            ->get();                               
        }else{
            return regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'PE_METADATO_PADRON.ENTIDAD_FED_ID')
                            ->join('PE_CAT_SERVICIOS','PE_CAT_SERVICIOS.SERVICIO_ID','=','PE_METADATO_PADRON.SERVICIO_ID')
                            ->join('PE_OSC'          ,'PE_OSC.OSC_ID'               ,'=','PE_METADATO_PADRON.OSC_ID')
                            ->select('PE_METADATO_PADRON.FOLIO',
                                     'PE_OSC.OSC_DESC'        ,  
                                     'PE_METADATO_PADRON.FECHA_INGRESO2', 
                                     'PE_METADATO_PADRON.PRIMER_APELLIDO',
                                     'PE_METADATO_PADRON.SEGUNDO_APELLIDO',
                                     'PE_METADATO_PADRON.NOMBRES',
                                     'PE_METADATO_PADRON.FECHA_NACIMIENTO2',     
                                     'PE_METADATO_PADRON.CURP',     
                                     'PE_METADATO_PADRON.SEXO',     
                                     'PE_METADATO_PADRON.DOMICILIO',     
                                     'PE_METADATO_PADRON.CP', 
                                     'PE_METADATO_PADRON.COLONIA',
                                     'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'PE_METADATO_PADRON.LOCALIDAD',          
                                     'PE_METADATO_PADRON.MOTIVO_ING',
                                     'PE_METADATO_PADRON.INTEG_FAM', 
                                     'PE_CAT_SERVICIOS.SERVICIO_DESC', 
                                     'PE_METADATO_PADRON.CUOTA_RECUP',
                                     'PE_METADATO_PADRON.QUIEN_CANALIZO', 
                                     'PE_METADATO_PADRON.STATUS_1',
                                     'PE_METADATO_PADRON.FECHA_REG'
                                    )
                            ->where(  'PE_METADATO_PADRON.OSC_ID'         ,$arbol_id)
                            ->orderBy('PE_METADATO_PADRON.NOMBRE_COMPLETO','ASC')
                            ->get();               
        }                            
    }
}
