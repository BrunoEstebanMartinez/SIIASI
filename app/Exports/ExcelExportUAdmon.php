<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regUAdmonModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportUAdmon implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'UNIDAD_ADMON',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $reguadmon = regUAdmonModel::select('UADMON_ID','UADMON_DESC', 'UADMON_STATUS','UADMON_FECREG')
                            ->orderBy('UADMON_ID','desc')
                            ->get();                                
    }
}
