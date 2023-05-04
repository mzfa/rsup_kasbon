<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ListAkunController extends Controller
{
    
    public function index()
    {
        $akun = DB::table('akun')->get();
        return view('list_akun.index', compact('akun'));
    }
    public function store(Request $request){
        $request->validate([
            'nama_akun' => ['required'],
            'no_telp' => ['required'],
        ]);
        $code_wa = str_replace(' ', '',$request->nama_akun);
        $data = [
            'nama_akun' => $request->nama_akun,
            'no_telp' => $request->no_telp,
            'code_wa' => $code_wa,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];
        DB::table('akun')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('akun')->where(['akun_id' => $id])->first()){
            return view('modal_content.akun', compact('data'));
            // dd($data);
        }
        return "Data tidak ditemukan";
    }

    public function hapus($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('akun')->where(['akun_id' => $id])->first()){
            return view('modal_content.akun', compact('data'));
            // dd($data);
        }
        return "Data tidak ditemukan";
    }

    public function update(Request $request){
        $request->validate([
            'nama_akun' => ['required'],
            'no_telp' => ['required'],
        ]);
        $akun_id = Crypt::decrypt($request->akun_id);
        $data = [
            'nama_akun' => $request->nama_akun,
            'no_telp' => $request->no_telp,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        DB::table('akun')->where(['akun_id' => $akun_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update!']);
    }

    public function qr($nama)
    {
        $data = [
            "id" => $nama, 
            "isLegacy" => false, 
            // "receiver" => , 
            // "message" => [
            //         "text" => "tes",
            //     ] 
        ];
        $url = "http://192.168.0.18:8060/sessions/add";
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
        $qr = substr($result,86,-3);
        // dd($result, $qr);
        // $view = '<img src="'.$qr.'" width="100%"><br>Dia tidak refresh jadi kalau sudah di scan dan berhasil di wanya tinggal di refresh lagi aja yaa';
        // return $qr;
        return view('qr', compact('qr'));
    }

    public function kirim()
    {
        $pesan = "*Pemberitahuan Penting*

Salam sehat ,Salam Sejahtera

Bapak / Ibu

Memperingati Hari Raya Nyepi 
Kami informasikan bahwa besok 22 Maret 2023 poliklinik RS Umum Pekerja tidak beroperasional. 
*Poliklinik Buka Kembali tanggal 23 Maret 2023*

*Layanan yang tetap Buka 24 Jam.*
• Unit IGD
• Unit Farmasi 
• Unit Radiologi
• Unit Rawat Inap

Sekian informasinya kami sampaikan, semoga berkenan untuk di maklumi
Mohon maaf konsultasi Bapak / Ibu tertunda

Salam sehat 

RS PEKERJA
Tempat Terbaik Teman Terpercaya

🌷🌷🌷";
        $data = [
            "receiver" => '6285884864980', 
            "message" => [
                    "text" => $pesan,
                ] 
        ];
        $url = "http://192.168.0.18:8060/chats/send?id=zul";
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
        dd($result);
    }
}
