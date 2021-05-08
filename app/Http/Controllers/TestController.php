<?php

namespace App\Http\Controllers;

use App\Exports\TableExport;
use App\Http\Requests\TestSetRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\DataService;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index(Request $request, AuthService $authService)
    {
        $tplData = [
            'auth' => $authService->check(), // флаг авторизации на сайте
        ];
        return view('index', $tplData);
    }

    public function auth(Request $request, AuthService $authService)
    {
        $authService->auth();
        return (redirect(route('index')));
    }

    public function logout(Request $request, AuthService $authService)
    {
        $authService->logout();
        return (redirect(route('index')));
    }

    public function export(Request $request, DataService $dataService, AuthService $authService)
    {
        $data = $dataService->getData($authService, true);
        $export = new TableExport($data);
        return Excel::download($export, 'table.xlsx');
    }

    public function get(Request $request, DataService $dataService, AuthService $authService)
    {
        $data = $dataService->getData($authService);
        return response()->json($data);
    }

    public function set(TestSetRequest $request, DataService $dataService)
    {
        $data = $request->validated();
        $dataService->set($data['data'] ?? []);
    }
}
