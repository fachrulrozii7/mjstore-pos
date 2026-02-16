<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Inventory;
use App\Models\MasterBranch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Helper to apply common filters to queries
     */
    protected static function applyFilters($query, $filters, $tablePrefix = 'mj_transaction')
    {
        if (!empty($filters['branch_id'])) {
            $query->where($tablePrefix . '.branch_id', $filters['branch_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween($tablePrefix . '.transaction_date', [
                $filters['start_date'] . ' 00:00:00', 
                $filters['end_date'] . ' 23:59:59'
            ]);
        }

        return $query;
    }

    public static function getBestSellingProducts($filters = [], $limit = 10)
    {
        $query = TransactionDetail::with('product')
            ->join('mj_transaction', 'mj_transaction.id', '=', 'mj_transaction_detail.transaction_id')
            ->select(
                'mj_transaction_detail.product_id',
                DB::raw('SUM(mj_transaction_detail.qty) as total_qty'),
                DB::raw('SUM(mj_transaction_detail.subtotal) as total_sales')
            )
            ->groupBy('mj_transaction_detail.product_id')
            ->orderByDesc('total_qty')
            ->limit($limit);

        return self::applyFilters($query, $filters)->get();
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
            ->orderBy('period', 'desc');
            
        return self::applyFilters($query, $filters)->get();
    }

    public static function getTotalProfit($filters = [])
    {
        $query = TransactionDetail::join('mj_transaction', 'mj_transaction.id', '=', 'mj_transaction_detail.transaction_id')
            ->join('mj_master_product', 'mj_master_product.id', '=', 'mj_transaction_detail.product_id')
            ->where('mj_transaction.status', 'PAID')
            ->selectRaw('SUM((mj_transaction_detail.price - mj_master_product.purchase_price) * mj_transaction_detail.qty) as total_profit');

        $result = self::applyFilters($query, $filters)->first();
        return $result->total_profit ?? 0;
    }

    public static function getProfitPerBranch($filters = [])
    {
        $query = TransactionDetail::join('mj_transaction', 'mj_transaction.id', '=', 'mj_transaction_detail.transaction_id')
            ->join('mj_master_product', 'mj_master_product.id', '=', 'mj_transaction_detail.product_id')
            ->select(
                'mj_transaction.branch_id',
                DB::raw('SUM((mj_transaction_detail.price - mj_master_product.purchase_price) * mj_transaction_detail.qty) as total_profit')
            )
            ->where('mj_transaction.status', 'PAID')
            ->groupBy('mj_transaction.branch_id');

        return self::applyFilters($query, $filters)->get();
    }

    public static function getSlowMovingProducts($filters = [], $limit = 10)
    {
        // Mengambil semua produk dan menjumlahkan qty terjual dari tabel transaction_details
        $query = Product::leftJoin('mj_transaction_detail', 'mj_master_product.id', '=', 'mj_transaction_detail.product_id')
            ->leftJoin('mj_transaction', 'mj_transaction.id', '=', 'mj_transaction_detail.transaction_id')
            ->select(
                'mj_master_product.product_name',
                DB::raw('COALESCE(SUM(mj_transaction_detail.qty), 0) as total_qty'),
                DB::raw('COALESCE(SUM(mj_transaction_detail.subtotal), 0) as total_sales')
            )
            // Filter status PAID agar pembatalan tidak dihitung sebagai penjualan
            ->where(function($q) {
                $q->where('mj_transaction.status', 'PAID')
                ->orWhereNull('mj_transaction.status');
            });

        // Terapkan filter cabang jika ada
        if (!empty($filters['branch_id'])) {
            $query->where('mj_transaction.branch_id', $filters['branch_id']);
        }

        // Terapkan filter tanggal jika ada
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('mj_transaction.transaction_date', [$filters['start_date'].' 00:00:00', $filters['end_date'].' 23:59:59']);
        }

        return $query->groupBy('mj_master_product.id', 'mj_master_product.product_name')
            ->orderBy('total_qty', 'asc') // Urutkan dari yang paling sedikit
            ->limit($limit)
            ->get();
    }
}