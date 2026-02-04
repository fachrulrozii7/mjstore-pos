<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'best_selling_products' => DashboardService::getBestSellingProducts(),
            'revenue_per_branch'    => DashboardService::getRevenuePerBranch(),
            'sales_daily'           => DashboardService::getSalesTrend(),
            'sales_monthly'         => DashboardService::getSalesTrend('monthly'),
        ]);
    }
}
