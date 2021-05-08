<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

/**
 * Class for prepare data table to Excel export
 */
class TableExport implements FromArray
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }
}
