<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TransaksiKasMasukController extends Controller
{
    public function index()
    {
        $data = DB::table('transaksi_kas_masuk')->leftJoin('users', 'transaksi_kas_masuk.created_by', '=', 'users.id')
        ->select([
            'users.username',
            'transaksi_kas_masuk.*',
        ])->whereNull('transaksi_kas_masuk.deleted_at')->get();
        return view('transaksi_kas_masuk.index', compact('data'));
    }

    public function store(Request $request){
        // dd($request);
        // $request->validate([
        //     'nama_transaksi_kas_masuk' => ['required', 'string'],
        // ]);
        $no_urut = DB::table('transaksi_kas_masuk')->max('no_transaksi_kas_masuk');
        $urutan = (int) substr($no_urut,0,3);
        $urutan++;
        $bt = substr($no_urut,-7);
        if($bt != date('m')."/".date('Y')){
            $urutan = 1;
        }
        $huruf = "/KM/".date('m')."/".date('Y');
        $no_transaksi_kas_masuk = sprintf("%03s",$urutan).$huruf;
        $keterangan = '';
        if($request->no_spb){
            $keterangan = 'SPB';
        }
        // dd($urutan);
        // $no_transaksi_kas_masuk = 1;
        $data = [
            'no_transaksi_kas_masuk' => $no_transaksi_kas_masuk,
            'uraian' => $request->uraian,
            'no_spb' => $request->no_spb,
            'keterangan' => $keterangan,
            'nominal' => $request->nominal,
            'diterima' => $request->diterima,
            'pj' => $request->pj,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];
        DB::table('transaksi_kas_masuk')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('transaksi_kas_masuk')->where(['transaksi_kas_masuk_id' => $id])->first()){
            // dd($data);
            $text = '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Uraian</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="uraian" name="uraian" value="'.$data->uraian.'" required>'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">No SPB</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="no_spb" name="no_spb" value="'.$data->no_spb.'">'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Nominal</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="nominal" name="nominal" value="'.$data->nominal.'" required readonly>'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Penerima</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="diterima" name="diterima" value="'.$data->diterima.'" required>'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Penanggung Jawab</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" value="Yoesi Febriansyah, SE." class="form-control" id="pj" name="pj" value="'.$data->pj.'" required>'.
                    '</div>'.
                '</div>'.
                '<input type="hidden" class="form-control" id="transaksi_kas_masuk_id" name="transaksi_kas_masuk_id" value="'.Crypt::encrypt($data->transaksi_kas_masuk_id) .'" required>';
        }
        return $text;
        // return view('admin.transaksi_kas_masuk.edit');
    }

    public function update(Request $request){
        $transaksi_kas_masuk_id = Crypt::decrypt($request->transaksi_kas_masuk_id);
        $keterangan = '';
        if($request->no_spb){
            $keterangan = 'SPB';
        }
        // $no_transaksi_kas_masuk = 1;
        $data = [
            'uraian' => $request->uraian,
            'no_spb' => $request->no_spb,
            // 'keterangan' => $keterangan,
            // 'nominal' => $request->nominal,
            'diterima' => $request->diterima,
            'pj' => $request->pj,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        DB::table('transaksi_kas_masuk')->where(['transaksi_kas_masuk_id' => $transaksi_kas_masuk_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update!']);
    }

    public function delete($id){
        $id = Crypt::decrypt($id);
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('transaksi_kas_masuk')->where(['transaksi_kas_masuk_id' => $id])->update($data);
        
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    public function print($id){
        $id = Crypt::decrypt($id);
        $data = DB::table('transaksi_kas_masuk')->leftJoin('users', 'transaksi_kas_masuk.created_by', '=', 'users.id')
        ->select([
            'users.username',
            'transaksi_kas_masuk.*',
        ])->whereNull('transaksi_kas_masuk.deleted_at')->where(['transaksi_kas_masuk.transaksi_kas_masuk_id' => $id])->first();

        return view('transaksi_kas_masuk.print', compact('data'));
    }
    
}
