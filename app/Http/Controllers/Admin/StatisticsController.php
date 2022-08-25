<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StatisticsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('statistic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $total = Customer::count();
        $weekCount = Customer::whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('-7 days')))->count();

        return view('admin.statistics.index', compact('total', 'weekCount'));
    }
}
