<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToArray;


class MetEvaluateImport implements ToArray
{
    /**
    * @param Collection $collection
    */
    public function Array(Array $tables)
    {
        return $tables;
    }

}

// class MetEvaluateImport implements WithMultipleSheets
// {
//     /**
//     * @param Collection $collection
//     */
//     public function collection(Collection $collection)
//     {
//         //
//     }
// }

// class MetEvaluateImport implements ToCollection
// {
//     /**
//     * @param Collection $collection
//     */
//     public function collection(Collection $collection)
//     {
//         //
//     }
// }