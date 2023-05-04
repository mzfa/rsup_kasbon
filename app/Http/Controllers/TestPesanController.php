<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TestPesanController extends Controller
{
    public function index()
    {
        $data = DB::table('pesan')->whereNull('deleted_at')->get();
        return view('tes_pesan.index', compact('data'));
    }
    public function import_pesan()
    {
        $data = DB::table('pesan')->whereNull('deleted_at')->get();
        return view('tes_pesan.import', compact('data'));
    }
    public function import_pesan_store(Request $request)
    {
        dd($request);
        // $data = DB::table('pesan')->whereNull('deleted_at')->get();
        // return view('tes_pesan.import', compact('data'));
    }
    public function kirim(Request $request){
        $request->validate([
            'pesan_id' => ['required'],
            'no_penerima' => ['required'],
        ]);
        $var = $request->var;
        $pesan_id = $request->pesan_id;
        $nomor = $request->no_penerima;
        // $var1 = '';
        // $var2 = '';
        // $var3 = '';
        // $var4 = '';
        // $var5 = '';
        // $var6 = '';
        // $var7 = '';
        // $var8 = '';
        // $var9 = '';
        // $var10 = '';
        // $var11 = '';
        // $var12 = '';
        // $var13 = '';
        // $var14 = '';
        // $var15 = '';
        // dd($var);
        // foreach()

        // dd($var);

        if($data = DB::table('pesan')->where(['pesan_id' => $pesan_id])->first()){
            $pesan = $data->isi_pesan;
            for ($i=0; $i < 15; $i++) { 
                if(isset($var[$i])){
                    $no = $i+1;
                    $pesan = str_replace("[var".$no."]",$var[$i],$pesan);
                    // dump($no);
                }
                $no=0;
            }
            // dd($pesan);
            $data = [
                "receiver" => $nomor,
                "message" => [
                        "text" => $pesan,
                    ] 
            ];
            $url = "http://192.168.0.18:8060/chats/send?id=test";
            // API WA
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ));
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
    
            curl_close($curl);
            // dd($pesan);
        }
        
        // return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
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
