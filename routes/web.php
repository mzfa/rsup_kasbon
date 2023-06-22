<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListAkunController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiKasMasukController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanKasMasukController;
use App\Http\Controllers\LaporanJenisPembayaranController;
use App\Http\Controllers\TestPesanController;
use App\Http\Controllers\SaldoController;
use App\Imports\PesanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::get('/login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login.store');
// Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'index'])->name('register');
// Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/');
})->name('logout');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // route::group()->middleware();
    Route::controller(HakAksesController::class)->middleware('cek_login:hakakses.index')->group(function () {
        Route::get('/hakakses', 'index')->name('hakakses.index');
        Route::get('/hakakses/edit/{id}', 'edit');
        Route::get('/hakakses/delete/{id}', 'delete');
        Route::get('/hakakses/modul_akses/{id}', 'modul_akses');
        Route::post('/hakakses/modul_akses', 'modul_akses_store');
        Route::post('/hakakses/store', 'store');
        Route::post('/hakakses/update', 'update');
    });
    Route::controller(MenuController::class)->middleware('cek_login:menu.index')->group(function () {
        Route::get('/menu', 'index')->name('menu.index');
        Route::get('/menu/edit/{id}', 'edit');
        Route::get('/menu/status/{id}', 'status');
        Route::get('/menu/delete/{id}', 'delete');
        Route::post('/menu/store', 'store');
        Route::post('/menu/update', 'update');
    });
    Route::controller(StrukturController::class)->middleware('cek_login:struktur.index')->group(function () {
        Route::get('/struktur', 'index')->name('struktur.index');
        Route::get('/struktur/edit/{id}', 'edit');
        Route::get('/struktur/status/{id}', 'status');
        Route::get('/struktur/delete/{id}', 'delete');
        Route::post('/struktur/store', 'store');
        Route::post('/struktur/update', 'update');
    });
    Route::controller(UserController::class)->middleware('cek_login:user.index')->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::get('/user/sync', 'sync');
        Route::get('/user/edit/{id}', 'edit');
        Route::post('/user/update', 'update');
    });
    Route::controller(ListAkunController::class)->middleware('cek_login:struktur.index')->group(function () {
        Route::get('/list_akun', 'index')->name('list_akun.index');
        Route::get('/list_akun/edit/{id}', 'edit');
        Route::get('/list_akun/qr/{id}', 'qr');
        Route::get('/list_akun/status/{id}', 'status');
        Route::get('/list_akun/delete/{id}', 'delete');
        Route::post('/list_akun/store', 'store');
        Route::post('/list_akun/update', 'update');
    });
    Route::controller(JenisPembayaranController::class)->middleware('cek_login:jenis_pembayaran.index')->group(function () {
        Route::get('/jenis_pembayaran', 'index')->name('jenis_pembayaran.index');
        Route::get('/jenis_pembayaran/edit/{id}', 'edit');
        Route::get('/jenis_pembayaran/delete/{id}', 'delete');
        Route::post('/jenis_pembayaran/store', 'store');
        Route::post('/jenis_pembayaran/update', 'update');
    });
    Route::controller(TransaksiController::class)->middleware('cek_login:transaksi.index')->group(function () {
        Route::get('/transaksi', 'index')->name('transaksi.index');
        Route::get('/transaksi/edit/{id}', 'edit');
        Route::get('/transaksi/delete/{id}', 'delete');
        Route::get('/transaksi/print/{id}', 'print');
        Route::post('/transaksi/store', 'store');
        Route::post('/transaksi/update', 'update');
    });
    Route::controller(TransaksiKasMasukController::class)->middleware('cek_login:transaksi_kas_masuk.index')->group(function () {
        Route::get('/transaksi_kas_masuk', 'index')->name('transaksi_kas_masuk.index');
        Route::get('/transaksi_kas_masuk/edit/{id}', 'edit');
        Route::get('/transaksi_kas_masuk/delete/{id}', 'delete');
        Route::get('/transaksi_kas_masuk/print/{id}', 'print');
        Route::post('/transaksi_kas_masuk/store', 'store');
        Route::post('/transaksi_kas_masuk/update', 'update');
    });
    Route::controller(KasbonController::class)->middleware('cek_login:kasbon.index')->group(function () {
        Route::get('/kasbon/index', 'index')->name('kasbon.index');
        Route::get('/kasbon/export_excel', 'export_excel');
    });
    Route::controller(PembayaranController::class)->middleware('cek_login:pembayaran.index')->group(function () {
        Route::get('/pembayaran/index', 'index')->name('pembayaran.index');
        Route::get('/pembayaran/export_excel', 'export_excel');
    });
    Route::controller(SaldoController::class)->middleware('cek_login:saldo.index')->group(function () {
        Route::get('/saldo/index', 'index')->name('saldo.index');
        Route::get('/saldo/export_excel', 'export_excel');
        Route::post('/saldo/detail', 'detail');
    });
    Route::controller(LaporanKasMasukController::class)->middleware('cek_login:laporan_kas_masuk.index')->group(function () {
        Route::get('/laporan_kas_masuk/index', 'index')->name('laporan_kas_masuk.index');
        Route::get('/laporan_kas_masuk/export_excel', 'export_excel');
    });
    Route::controller(LaporanJenisPembayaranController::class)->middleware('cek_login:laporan_jenis_pembayaran.index')->group(function () {
        Route::get('/laporan_jenis_pembayaran/index', 'index')->name('laporan_jenis_pembayaran.index');
        Route::get('/laporan_jenis_pembayaran/export_excel/{jenis_pembayaran_id}/{tanggal_awal}/{tanggal_akhir}/{pemid}','export_excel');
        Route::post('/laporan_jenis_pembayaran/detail', 'detail');
    });
    // Route::get('/tes_pesan', 'index')->name('tes_pesan.index');
    Route::get('/tes_pesan', [\App\Http\Controllers\TestPesanController::class, 'index'])->name('tes_pesan.index');
    Route::post('/tes_pesan/kirim', [\App\Http\Controllers\TestPesanController::class, 'kirim']);
    Route::get('/import_pesan', [\App\Http\Controllers\TestPesanController::class, 'import_pesan'])->name('import_pesan.index');
    
    Route::post('/import_pesan', function (Request $request) {
        $gambar = '';
        if($request->hasFile('gambar')){
            $gambar = request()->file('gambar');
            if(in_array($gambar->getClientMimeType(),['image/jpg','image/jpeg','image/png','image/svg'])){
                $gambar_name = round(microtime(true) * 1000).'-'.str_replace(' ','-',$gambar->getClientOriginalName());
                $gambar->move(public_path('gambar/promosi/'), $gambar_name);
                $gambar_name = url('/gambar/promosi/'.$gambar_name);
                Excel::import(new PesanImport($request->pesan_id,$gambar_name), request()->file('file'));
            }else{
                // return Redirect::back()->with(['error' => "File anda tidak dapat kami simpan cek kembali extensinya yaa pastiin jpg,jpeg,png"]);
            }
        }else{
            Excel::import(new PesanImport($request->pesan_id,$gambar), request()->file('file'));
        }
        return Redirect::back()->with(['succ' => "Mantull bangett....."]);
    });
    
    // Route::get('/kirim', [\App\Http\Controllers\WaBlastController::class, 'kirim'])->name('home');

});