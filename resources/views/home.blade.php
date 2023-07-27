@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Selamat Datang Di E-KASBON</h5>
                                <span class="badge badge-primary">Anda Login Sebagai {{ Auth::user()->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($saldo as $item)
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>{{ $item['jenis_pembayaran'] }}</h5>
                                {{-- <span class="badge badge-primary">Monthly</span> --}}
                            </div>
                            <h3>Rp. <span class="counter">{{ number_format($item['total']) }}</span></h3>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Total Pendapatan</h5>
                                <span class="badge badge-warning">Bulan Ini</span>
                            </div>
                            <h3>Rp. <span class="counter">{{ number_format($pendapatan_bulan_ini) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Total Pendapatan</h5>
                                <span class="badge badge-success">Hari Ini</span>
                            </div>
                            <h3>Rp. <span class="counter">{{ number_format($pendapatan_hari_ini) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Total Pengeluaran</h5>
                                <span class="badge badge-info">Bulan Ini</span>
                            </div>
                            <h3>Rp. <span class="counter">{{ number_format($pengeluaran_bulan_ini) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Total Pengeluaran</h5>
                                <span class="badge badge-info">Hari Ini</span>
                            </div>
                            <h3>Rp. <span class="counter">{{ number_format($pengeluaran_hari_ini) }}</span></h3>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
