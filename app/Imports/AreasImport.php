<?php

namespace App\Imports;

use App\Models\Area;
use Maatwebsite\Excel\Concerns\ToModel;

class AreasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Area([
            'state_id' => $row[0],
            'name'    => $row[1],
            'status' => $row[2],
            'updated_at'=>$row[3],
            'created_at'=>$row[4]

        ]);

    }
}
