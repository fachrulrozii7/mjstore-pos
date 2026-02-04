<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Models\MasterBranch;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        // 1. Prepare Filters
        $filters = [
            'branch_id'  => $request->get('branch_id'),
            'start_date' => $request->get('start_date', now()->startOfMonth()->toDateString()),
            'end_date'   => $request->get('end_date', now()->toDateString()),
        ];

        // 2. Fetch Data using Service
        $bestSelling      = DashboardService::getBestSellingProducts($filters);
        $revenuePerBranch = DashboardService::getRevenuePerBranch($filters);
        $dailySales       = DashboardService::getSalesTrend($filters);
        $profitPerBranch  = DashboardService::getProfitPerBranch($filters);
        $branches         = MasterBranch::where('is_active', true)->get();

        // 3. Mapping data for Chart (Comparison Chart)
        $chartData = $revenuePerBranch->map(function ($rev) use ($profitPerBranch) {
            $profit = $profitPerBranch->where('branch_id', $rev->branch_id)->first();
            return [
                'label'   => $rev->branch->branch_name ?? 'Unknown',
                'revenue' => (float) $rev->total_revenue,
                'profit'  => (float) ($profit->total_profit ?? 0)
            ];
        });

        // 4. Return View with unified data
        return view('dashboard.index', [
            'summary' => [
                'total_revenue' => $revenuePerBranch->sum('total_revenue'),
                'total_profit'  => DashboardService::getTotalProfit($filters),
            ],
            'bestSelling'       => $bestSelling,
            'revenuePerBranch'  => $revenuePerBranch,
            'totalRevenue'      => $revenuePerBranch->sum('total_revenue'),
            'totalTransactions' => $revenuePerBranch->sum('total_transactions'),
            'dailySales'        => $dailySales,         
            'branches'          => $branches, 
            'filters'           => $filters,  
            'chartData'         => $chartData
        ]);
    }
}