<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PesanController extends Controller
{
    public function index()
    {
        $data = DB::table('pesan')->whereNull('deleted_at')->get();
        return view('pesan.index', compact('data'));
    }
    public function store(Request $request){
        $request->validate([
            'judul_pesan' => ['required', 'string'],
            'isi_pesan' => ['required'],
        ]);
        $data = [
            'judul_pesan' => $request->judul_pesan,
            'isi_pesan' => $request->isi_pesan,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];
        DB::table('pesan')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('pesan')->where(['pesan_id' => $id])->first()){
            return view('modal_content.pesan', compact('data'));
        }
        return $text;
        // return view('admin.pesan.edit');
    }

    public function update(Request $request){
        $request->validate([
            'judul_pesan' => ['required'],
            'pesan_id' => ['required'],
            'isi_pesan' => ['required'],
        ]);
        $pesan_id = Crypt::decrypt($request->pesan_id);
        $data = [
            'judul_pesan' => $request->judul_pesan,
            'isi_pesan' => $request->isi_pesan,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        DB::table('pesan')->where(['pesan_id' => $pesan_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update!']);
    }

    public function delete($id){
        $id = Crypt::decrypt($id);
        // if($data = DB::select("SELECT * FROM pesan WHERE pesan_id='$id'")){
        //     DB::select("DELETE FROM pesan WHERE pesan_id='$id'");
        // }
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('pesan')->where(['pesan_id' => $id])->update($data);
        
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
