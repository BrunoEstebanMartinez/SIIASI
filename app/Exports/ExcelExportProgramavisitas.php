<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\regHorasModel;
use App\regIapModel;
use App\regAgendaModel;


// class ExcelExportProgramavisitas implements FromQuery,  WithHeadings   ojo jala con el query************
class ExcelExportProgramavisitas implements FromCollection, /*FromQuery,*/ WithHeadings, WithTitle
{

    //********** Parámetros de filtro del query *******************//
    private $periodo;
    private $mes;
    private $tipo;
 
    public function __construct(int $periodo, int $mes, string $tipo)
    {
        $this->periodo = $periodo;
        $this->mes     = $mes;
        $this->tipo    = $tipo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'PERIODO_FISCAL',
            'MES',
            'DIA',            
            'FOLIO',  
            'ID_IAP',
            'IAP',
            'REG_CONSTITUCION',
            'RFC',
            'DOMICILIO',
            'ENTIDAD',
            'MUNICIPIO',
            'FECHA_VISITA',
            'HORA',
            'CONTACTO',
            'OBJETO_VISITA_PARTE_1',
            'OBJETO_VISITA_PARTE_2',
            'TIPO_VISITA'
        ];
    }


    public function collection()
    {
        //dd($id);
        //$arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');    
        return  regAgendaModel::join('JP_CAT_HORAS','JP_CAT_HORAS.HORA_ID','=','JP_AGENDA.HORA_ID')
                              ->join('JP_IAPS'     ,'JP_IAPS.IAP_ID'      ,'=','JP_AGENDA.IAP_ID')
                            ->select('JP_AGENDA.PERIODO_ID',
                                     'JP_AGENDA.MES_ID','JP_AGENDA.DIA_ID',
                                     'JP_AGENDA.VISITA_FOLIO',
                                     'JP_AGENDA.IAP_ID', 
                                     'JP_IAPS.IAP_DESC',
                                     'JP_IAPS.IAP_REGCONS',
                                     'JP_IAPS.IAP_RFC',
                                     'JP_AGENDA.VISITA_DOM',
                                     'JP_AGENDA.ENTIDAD_ID',
                                     'JP_AGENDA.MUNICIPIO_ID',
                                     'JP_AGENDA.VISITA_FECREGP',
                                     'JP_CAT_HORAS.HORA_DESC',
                                     'JP_AGENDA.VISITA_CONTACTO',                         
                                     'JP_AGENDA.VISITA_OBJ','VISITA_OBS3',
                                     'JP_AGENDA.VISITA_TIPO1'
                                     )
                            ->where( ['PERIODO_ID'  => $this->periodo, 
                                     'MES_ID'       => $this->mes,
                                     'VISITA_TIPO1' => $this->tipo])
                            ->orderBy('JP_AGENDA.PERIODO_ID','ASC')
                            ->orderBy('JP_AGENDA.VISITA_FOLIO','ASC')    
                            ->get();                       

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Mes ' . $this->mes;
    }

    //public function query()
    //{
    //    return  regAgendaModel::query()
    //            ->where( ['PERIODO_ID'   => $this->periodo, 
    //                      'MES_ID'       => $this->mes,
    //                      'VISITA_TIPO1' => $this->tipo]);   
    //                        //->get();                                                           
    //}

}
