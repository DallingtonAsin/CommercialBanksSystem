<?php

namespace MillionsSaving\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MillionsSaving\Models\Benefits\Investment;

class ImportInvestments implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Investment([
            'asset' => $row['asset'],
            'details' => $row['details'],
            'capital' => $row['capital'],
            'returns' => $row['returns'],
            'approved_on' => date('Y-m-d', $row['approved_on']),
            'approved_by' => $row['approved_by'],
        ]);
    }
}
