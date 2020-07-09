<?php

namespace MillionsSaving\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MillionsSaving\Models\User\Role;

class ImportRoles implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Role([
            'role' => $row['role'],
            'is_admin' => $row['is_admin'],
            'isSuperAdmin' => $row['is_superadmin'],
        ]);
    }
}
