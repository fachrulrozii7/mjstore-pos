<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Inventory;
use App\Models\MasterBranch;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public static function getBestSellingProducts($limit = 10){
        return TransactionDetail::with('product')
        ->select(
            'product_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(subtotal) as total_sales')
        )
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->limit($limit)
        ->get();
    }

    public static function getRevenuePerBranch()
    {
        return Transaction::with('branch')
            ->select(
                'branch_id',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(id) as total_transactions')
            )
            ->where('status', 'PAID')
            ->groupBy('branch_id')
            ->orderByDesc('total_revenue')
            ->get();
    }

    public static function getSalesTrend($type = 'daily', $branch = '')
    {
        if ($branch != ''){ //USING BRANCH FILTER
            if ($type === 'monthly') {
                return Transaction::with('branch:id,branch_id,branch_name') // Eager load only needed columns
                    ->select('branch_id',
                    DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as period'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('COUNT(id) as total_transactions')
                    )
                    ->where('status', 'PAID')
                    ->where('branch_id', $branch)
                    ->groupBy('period','branch_id')
                    ->orderBy('period')
                    ->get();
            }
            // default: daily
            return Transaction::with('branch:id,branch_id,branch_name') // Eager load only needed columns
                ->select('branch_id',
                DB::raw('DATE(transaction_date) as period'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(id) as total_transactions')
                )
                ->where('status', 'PAID')
                ->where('branch_id', $branch)
                ->groupBy('period','branch_id')
                ->orderBy('period')
                ->get();
        }else{ //NOT USING BRANCH FILTER
            if ($type === 'monthly') {
                return Transaction::select(
                    DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as period'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('COUNT(id) as total_transactions')
                    )
                    ->where('status', 'PAID')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            }
            // default: daily
            return Transaction::select(
                DB::raw('DATE(transaction_date) as period'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(id) as total_transactions')
                )
                ->where('status', 'PAID')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }
        
    }

}
