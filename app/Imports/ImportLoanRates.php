<?php

namespace MillionsSaving\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MillionsSaving\Models\Loans\LoanSetting;

class ImportLoanRates implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LoanSetting([
            'min_loanamt' => $row['min_amount'],
            'max_loanamt' => $row['max_amount'],
            'interest_rate' => $row['interest_rate'],
        ]);
    }
}
