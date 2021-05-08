<?php

namespace App\Helpers;
use Illuminate\Support\Arr;

/**
 * Helper fo r work with arrays
 * Extends Illuminate\Support\Arr
 */
class ArrayHelper extends Arr
{
    /**
     * Hide values in data with choosen keys
     *
     * @param array $data
     * @param array $columns list of must be hidden columns
     * @return array
     */
    static public function hideColumns(array $data, array $columns): array
    {
        foreach($columns as $col){
            $data = array_map(function(&$row) use ($col) {
                return self::set($row, $col, '***');
            }, $data);
        }
        return $data;
    }
}
