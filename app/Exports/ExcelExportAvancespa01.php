<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
//use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
//use Maatwebsite\Excel\Concerns\WithTitle;

// class ExcelExportProgramavisitas implements FromQuery,  WithHeadings   ojo jala con el query************
//class ExcelExportCobranzaFacturas implements FromCollection, /*FromQuery,*/ WithHeadings, WithTitle
//class ExcelExportCobranzaFacturas implements FromQuery, WithHeadings /*, WithTitle */
//class ExcelExportCobranzaFacturas implements FromArray 
class ExcelExportAvancespa01 implements FromCollection, WithHeadings
{

    use Exportable;

   //********** Parámetros de filtro del query *******************//
    private $periodo;
    private $mes;
    private $depen;
    private $tipo;
 
    public function __construct($regprogdanual, $periodo, $mes, $depen, $tipo)
    {
        $this->regprogdanual= $regprogdanual;
        $this->periodo      = $periodo;
        $this->mes          = $mes;
        $this->depen        = $depen;
        $this->tipo         = $tipo;
    }

    public function array(): array
    {
        return [ $this->regprogdanual ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    { 
        return [
            'UNID_EJEC_ID',
            'UNID_OP_ID',
            'UNID_OP',
            'FOLIO',  
            'NO_ACCION_META',
            'CIPREP',
            'LGOB',
            'FEC_ENTREGA',
            'ACCION_META',
            'UNIDAD_MED',
            'UNIDAD_MEDIDA',
            'META_ANUAL_PROGRAMADA',
            'META_ANUAL_CUMPLIDA',
            'META_ANUAL_%',
            'META_ANUAL_SEMAFORO',
            'ENE_P',
            'ENE_C',
            'ENE_%',
            'ENE_SEMAFORO',           
            'FEB_P',
            'FEB_C',
            'FEB_%',
            'FEB_SEMAFORO',
            'MAR_P',
            'MAR_C',
            'MAR_%',
            'MAR_SEMAFORO',
            'TRIM1_P',
            'TRIM1_C',
            'TRIM1_%',     
            'TRIM1_SEMAFORO',       
            'ABR_P',
            'ABR_C',  
            'ABR_%',          
            'ABR_SEMAFORO',
            'MAR_P',
            'MAR_C',
            'MAR_%',
            'MAR_SEMAFORO',
            'JUN_P',
            'JUN_C',
            'JUN_%',
            'JUN_SEMAFORO',
            'TRIM2_P',
            'TRIM2_C',
            'TRIM2_%',     
            'TRIM2_SEMAFORO', 
            'SEM1_P',
            'SEM1_C', 
            'SEM1_%',     
            'SEM1_SEMAFORO',
            'JUL_P',
            'JUL_C',
            'JUL_%',
            'JUL_SEMAFORO',
            'AGO_P',
            'AGO_C',
            'AGO_%',
            'AGO_SEMAFORO',
            'SEP_P',
            'SEP_C',
            'SEP_%',
            'SEP_SEMAFORO',
            'TRIM3_P',
            'TRIM3_C',   
            'TRIM3_%',     
            'TRIM3_SEMAFORO',    
            'OCT_P',
            'OCT_C',  
            'OCT_%',         
            'OCT_SEMAFORO', 
            'NOV_P',
            'NOV_C',
            'NOV_%',
            'NOV_SEMAFORO',
            'DIC_P',
            'DIC_C',
            'DIC_%',
            'DIC_SEMAFORO',
            'TRIM4_P',
            'TRIM4_C',
            'TRIM4_%',     
            'TRIM4_SEMAFORO', 
            'SEM2_P',
            'SEM2_C', 
            'SEM2_%',        
            'SEM2_SEMAFORO',
            'OBJETIVO',
            'DESCRIP_OPERACIONAL',
            'FICHA_JUSTIFICACION_PDF'
        ];
    }

    /**
     * @return string
     */
    public function collection()
    {
        //dd('reg:'.$this->regfactura, 'Periodo:'.$this->perr,'Mes:'.$this->mess,'Dia:'.$this->diaa,'Cliente:'.$this->cliee);
        //$arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');   
        return $this->regprogdanual;
        //return $this->regrecibos;
    }

    /**
     * @return string
     */
    //public function title(): string
    //{
    //    return 'Mes ' . $this->mess;
    //}

    public function query()
    {
        //return  regEfacturaModel::query()
        $regprogdanual = $this->regprogdanual;
        return  regProgdAnualModel::query()
                                    ->get();  
        //$regrecibos = $this->regrecibos;
        //return  regEfacturaModel::query()
        //                        ->get();                                                                                                
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
