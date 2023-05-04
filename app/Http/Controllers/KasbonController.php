<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KasbonController extends Controller
{
    public function index()
    {
        $data = DB::table('transaksi')->whereNull('deleted_at')->whereNull('no_spb')->get();
        return view('kasbon.index', compact('data'));
    }
    
}
