<?php
namespace App\Services;

use App\Helpers\ArrayHelper;
use App\Services\AuthService;

class DataService
{
    public function get(): array
    {
        return session('data', []);
    }
    public function set($data)
    {
        session(['data' => $data]);
    }

    /**
     * Get data from session with check permissions and calculate sums
     *
     * @param AuthService $authService
     * @param boolean $withSum flag to calculate sums
     * @return array
     */
    public function getData(AuthService $authService, bool $withSum = false): array
    {
        $data = $this->get();
        $sumCols = ['param1','param2','param3']; // list of sum/hide columns is better to set in model
        if(!$authService->check()) {
            $data = ArrayHelper::hideColumns($data, $sumCols);
        } elseif ($withSum) {
            $sums = [];
            array_map(function($d) use (&$sums, $sumCols) {
                foreach($d as $k=>$val){
                    if (!isset($sums[$k])) $sums[$k] = 0;
                    if (in_array($k, $sumCols)) $sums[$k] += (int)$val;
                }
            }, $data);
            if(!empty($sums)) $data[] = $sums;
        }
        return $data;
    }
}
