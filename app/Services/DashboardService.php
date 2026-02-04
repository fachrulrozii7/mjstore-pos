<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Inventory;
use App\Models\MasterBranch;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected static function applyFilters($query, $filters)
    {
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('transaction_date', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
        }

        return $query;
    }

    public static function getBestSellingProducts($filters = [], $limit = 10){
        $query = TransactionDetail::with('product')
            ->join('transaction', 'transaction.id', '=', 'transaction_detail.transaction_id')
            ->select(
                'product_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal) as total_sales')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit($limit);
        if (!empty($filters['branch_id'])) {
            $query->where('transaction.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('transaction.transaction_date', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
        }
        
        return $query->get();
    }

    public static function getRevenuePerBranch($filters = [])
    {
        $query = Transaction::with('branch')
            ->select(
                'branch_id',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(id) as total_transactions')
            )
            ->where('status', 'PAID')
            ->groupBy('branch_id')
            ->orderByDesc('total_revenue');

        return self::applyFilters($query, $filters)->get();
    }

    public static function getSalesTrend($filters = [])
    {
        $query = Transaction::select(
            DB::raw('DATE(transaction_date) as period'),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('COUNT(id) as total_transactions')
            )
            ->where('status', 'PAID')
            ->groupBy('period')
            ->orderBy('period','desc');
        if (!empty($filters['branch_id'])) {
            $query->where('transaction.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('transaction.transaction_date', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
        }    
        return $query->get();
    }

    public static function getTotalProfit($filters = [])
    {
        // Menghitung (Harga Jual - Harga Beli) * Qty
        $query = TransactionDetail::join('transaction', 'transaction.id', '=', 'transaction_detail.transaction_id')
            ->join('product', 'product.id', '=', 'transaction_detail.product_id')
            ->where('transaction.status', 'PAID')
            ->selectRaw('SUM((transaction_detail.price - product.purchase_price) * transaction_detail.qty) as total_profit');
        if (!empty($filters['branch_id'])) {
            $query->where('transaction.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('transaction.transaction_date', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
        }    
        return $query->first()->total_profit ?? 0;
    }

    public static function getProfitPerBranch($filters = [])
    {
        $query = \App\Models\TransactionDetail::join('transaction', 'transaction.id', '=', 'transaction_detail.transaction_id')
            ->join('product', 'product.id', '=', 'transaction_detail.product_id')
            ->select(
                'transaction.branch_id',
                \Illuminate\Support\Facades\DB::raw('SUM((transaction_detail.price - product.purchase_price) * transaction_detail.qty) as total_profit')
            )
            ->where('transaction.status', 'PAID')
            ->groupBy('transaction.branch_id');
        if (!empty($filters['branch_id'])) {
            $query->where('transaction.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('transaction.transaction_date', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
        }        

        return $query->get();
    }
}
