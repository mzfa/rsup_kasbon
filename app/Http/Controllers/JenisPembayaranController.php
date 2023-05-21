<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JenisPembayaranController extends Controller
{
    public function index()
    {
        $data = DB::table('jenis_pembayaran')->whereNull('jenis_pembayaran.deleted_at')->get();
        return view('jenis_pembayaran.index', compact('data'));
    }
    public function store(Request $request){
        $request->validate([
            'nama_jenis_pembayaran' => ['required'],
            'keterangan_jenis_pembayaran' => ['required'],
        ]);
        $data = [
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'nama_jenis_pembayaran' => $request->nama_jenis_pembayaran,
            'keterangan_jenis_pembayaran' => $request->keterangan_jenis_pembayaran,
        ];
        DB::table('jenis_pembayaran')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::select("SELECT * FROM jenis_pembayaran WHERE jenis_pembayaran_id='$id'")){

            $text = '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Nama kompetensi</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="nama_jenis_pembayaran" name="nama_jenis_pembayaran" value="'.$data[0]->nama_jenis_pembayaran.'" required>'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Keterangan</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="keterangan_jenis_pembayaran" name="keterangan_jenis_pembayaran" value="'.$data[0]->keterangan_jenis_pembayaran.'" required>'.
                    '</div>'.
                '</div>'.
                '<input type="hidden" class="form-control" id="jenis_pembayaran_id" name="jenis_pembayaran_id" value="'.Crypt::encrypt($data[0]->jenis_pembayaran_id) .'" required>';
        }
        return $text;
        // return view('jenis_pembayaran.edit');
    }

    public function update(Request $request){
        $request->validate([
            'nama_jenis_pembayaran' => ['required'],
            'keterangan_jenis_pembayaran' => ['required'],
        ]);
        $data = [
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
            'nama_jenis_pembayaran' => $request->nama_jenis_pembayaran,
            'keterangan_jenis_pembayaran' => $request->keterangan_jenis_pembayaran,
        ];
        $jenis_pembayaran_id = Crypt::decrypt($request->jenis_pembayaran_id);
        $status_kompetensi = "Aktif";
        DB::table('jenis_pembayaran')->where(['jenis_pembayaran_id' => $jenis_pembayaran_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Ubah!']);
    }
    public function delete($id){
        $id = Crypt::decrypt($id);
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('jenis_pembayaran')->where(['jenis_pembayaran_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
