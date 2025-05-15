<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CommonImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return Array|null
     */
    public function model(array $row)
    {
        return $row;
    }

}
