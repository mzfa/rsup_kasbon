<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class HomeController extends Controller
{
    public function index()
    {
        $bulan_awal = date('Y-m'). '-01 00:01:00.000';
        $bulan_akhir = date('Y-m'). '-31 23:59:59.000';
        $jenis_pembayaran = DB::table('jenis_pembayaran')->whereNull('jenis_pembayaran.deleted_at')->get();
        $saldo = [];
        foreach($jenis_pembayaran as $item){
            $jenis_pembayaran_id = '|'.$item->jenis_pembayaran_id.'|';
            $data1 = DB::table('transaksi_kas_masuk')
            ->select([
                'users.username',
                'transaksi_kas_masuk.*',
            ])->whereNull('transaksi_kas_masuk.deleted_at')
            ->where('transaksi_kas_masuk.jenis_pembayaran_id','LIKE','%'.$jenis_pembayaran_id.'%')
            ->sum('transaksi_kas_masuk.nominal');
            // $data2 = DB::table('transaksi_kas_masuk')
            // ->select([
            //     'users.username',
            //     'transaksi_kas_masuk.*',
            // ])->whereNull('transaksi_kas_masuk.deleted_at')
            // ->where('transaksi_kas_masuk.jenis_pembayaran_id','LIKE','%'.$jenis_pembayaran_id.'%')
            // ->sum('transaksi_kas_masuk.nominal');
            $total = $data1;
            array_push($saldo, [
                'jenis_pembayaran' => $item->nama_jenis_pembayaran,
                'total' => $total,
            ]);
        }
        $pengeluaran_bulan_ini = DB::table('transaksi')
        ->select([
            'transaksi.*',
        ])->whereNull('transaksi.deleted_at')
        ->whereBetween('transaksi.created_at',[$bulan_awal,$bulan_akhir])
        ->sum('transaksi.nominal');
        $pengeluaran_hari_ini = DB::table('transaksi')
        ->select([
            'transaksi.*',
        ])->whereNull('transaksi.deleted_at')
        ->whereDate('transaksi.created_at',Carbon::today())
        ->sum('transaksi.nominal');
        $pendapatan_bulan_ini = DB::table('transaksi_kas_masuk')
        ->select([
            'transaksi_kas_masuk.*',
        ])->whereNull('transaksi_kas_masuk.deleted_at')
        ->whereBetween('transaksi_kas_masuk.created_at',[$bulan_awal,$bulan_akhir])
        ->sum('transaksi_kas_masuk.nominal');
        $pendapatan_hari_ini = DB::table('transaksi_kas_masuk')
        ->select([
            'transaksi_kas_masuk.*',
        ])->whereNull('transaksi_kas_masuk.deleted_at')
        ->whereDate('transaksi_kas_masuk.created_at',Carbon::today())
        ->sum('transaksi_kas_masuk.nominal');
        // $pendapatan_bulan_ini = $data1+$data2;
        // dump($pengeluaran_hari_ini);


        return view('home', compact('saldo','pengeluaran_bulan_ini','pengeluaran_hari_ini','pendapatan_bulan_ini','pendapatan_hari_ini'));
    }
}
