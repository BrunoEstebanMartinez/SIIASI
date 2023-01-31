<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

//use App\regHorasModel;
use App\regProgdAnualModel;


// class ExcelExportProgramavisitas implements FromQuery,  WithHeadings   ojo jala con el query************
class ExcelExportAvancespa01v2 implements FromCollection, /*FromQuery,*/ WithHeadings, WithTitle
{

    //********** Parámetros de filtro del query *******************//
    private $periodo;
    private $mes;
    private $depen;
    private $tipo;
 
    public function __construct(int $periodo, int $mes, string $depen, string $tipo)
    {
        $this->periodo = $periodo;
        $this->mes     = $mes;
        $this->depen   = $depen;
        $this->tipo    = $tipo;
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
            'FICHA_JUSTIFICACION'
        ];
    }


    public function collection()
    {
        //dd($id);
        //$arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');    
        return  regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA'     ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                   'SIEVAD_DPA.UMED_ID')
                                  ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                   'SIEVAD_DPA.DEPEN_ID2')
                       ->select(    
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    //'SIEVAD_DPA.MES_ID2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.TOTP_01' ,'SIEVAD_DPA.TOTC_01' ,
                                    'SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAFA_01',

                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESC_01',
                                    'SIEVAD_DPA.MES_P01',
                                    'SIEVAD_DPA.SEMAF_01',
                                    'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESC_02',  
                                    'SIEVAD_DPA.MES_P02',
                                    'SIEVAD_DPA.SEMAF_02',
                                    'SIEVAD_DPA.MESP_03', 'SIEVAD_DPA.MESC_03',
                                    'SIEVAD_DPA.MES_P03',
                                    'SIEVAD_DPA.SEMAF_03',
                                    'SIEVAD_DPA.TRIMP_01', 'SIEVAD_DPA.TRIMC_01',
                                    'SIEVAD_DPA.TRIM_P01',
                                    'SIEVAD_DPA.SEMAFT_01',

                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESC_04',
                                    'SIEVAD_DPA.MES_P04',
                                    'SIEVAD_DPA.SEMAF_04',
                                    'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESC_05',  
                                    'SIEVAD_DPA.MES_P05',
                                    'SIEVAD_DPA.SEMAF_05',
                                    'SIEVAD_DPA.MESP_06', 'SIEVAD_DPA.MESC_06',
                                    'SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.TRIMP_02', 'SIEVAD_DPA.TRIMC_02',
                                    'SIEVAD_DPA.TRIM_P02',
                                    'SIEVAD_DPA.SEMAFT_02',
                                    'SIEVAD_DPA.TSEMP_01', 'SIEVAD_DPA.TSEMC_01',
                                    'SIEVAD_DPA.SEM_P01',
                                    'SIEVAD_DPA.SEMAFS_01',

                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESC_07',   
                                    'SIEVAD_DPA.MES_P07',
                                    'SIEVAD_DPA.SEMAF_07',
                                    'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESC_08',
                                    'SIEVAD_DPA.MES_P08',
                                    'SIEVAD_DPA.SEMAF_08',
                                    'SIEVAD_DPA.MESP_09', 'SIEVAD_DPA.MESC_09',
                                    'SIEVAD_DPA.MES_P09',
                                    'SIEVAD_DPA.SEMAF_09',
                                    'SIEVAD_DPA.TRIMP_03', 'SIEVAD_DPA.TRIMC_03',
                                    'SIEVAD_DPA.TRIM_P03',
                                    'SIEVAD_DPA.SEMAFT_03',

                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESC_10', 
                                    'SIEVAD_DPA.MES_P10',
                                    'SIEVAD_DPA.SEMAF_10',
                                    'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESC_11', 
                                    'SIEVAD_DPA.MES_P11',
                                    'SIEVAD_DPA.SEMAF_11',
                                    'SIEVAD_DPA.MESP_12', 'SIEVAD_DPA.MESC_12', 
                                    'SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.TRIMP_04','SIEVAD_DPA.TRIMC_04',
                                    'SIEVAD_DPA.TRIM_P04',
                                    'SIEVAD_DPA.SEMAFT_04',
                                    'SIEVAD_DPA.TSEMP_02','SIEVAD_DPA.TSEMC_02',
                                    'SIEVAD_DPA.SEM_P02',
                                    'SIEVAD_DPA.SEMAFS_02',

                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01'
                               )
                       ->selectRaw('(SIEVAD_DPA.MESP_01+SIEVAD_DPA.MESP_02+SIEVAD_DPA.MESP_03+
                                     SIEVAD_DPA.MESP_04+SIEVAD_DPA.MESP_05+SIEVAD_DPA.MESP_06+
                                     SIEVAD_DPA.MESP_07+SIEVAD_DPA.MESP_08+SIEVAD_DPA.MESP_09+
                                     SIEVAD_DPA.MESP_10+SIEVAD_DPA.MESP_11+SIEVAD_DPA.MESP_12)
                                     META_PROGRAMADA')
                       ->selectRaw('(SIEVAD_DPA.MESC_01+SIEVAD_DPA.MESC_02+SIEVAD_DPA.MESC_03+
                                     SIEVAD_DPA.MESC_04+SIEVAD_DPA.MESC_05+SIEVAD_DPA.MESC_06+
                                     SIEVAD_DPA.MESC_07+SIEVAD_DPA.MESC_08+SIEVAD_DPA.MESC_09+
                                     SIEVAD_DPA.MESC_10+SIEVAD_DPA.MESC_11+SIEVAD_DPA.MESC_12)
                                     META_REALIZADA')                       
                          ->where( ['SIEVAD_DPA.PERIODO_ID'   => $this->periodo 
                                    //,'SIEVAD_DPA.DEPEN_ID2'    => $this->depen_id //,
                                      //'VISITA_TIPO1' => $this->tipo
                                   ])
                          ->orderBy('SIEVAD_DPA.PERIODO_ID','ASC')
                          ->orderBy('SIEVAD_DPA.FOLIO'     ,'ASC')    
                          ->orderBy('SIEVAD_DPA.PARTIDA'   ,'ASC')    
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
