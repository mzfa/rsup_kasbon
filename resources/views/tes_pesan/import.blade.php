@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Tes Pesan &nbsp;  <a href="{{ asset('tamplate.xlsx') }}" class="btn btn-primary">Download Tamplate</a></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            <div class="iq-alert-icon">
                                <i class="ri-alert-line"></i>
                            </div>
                            <div class="iq-alert-text">{{ Session::get('error') }}</div>
                        </div>
                        @endif
                        @if (Session::has('succ'))
                        <div class="alert alert-primary" role="alert">
                            <div class="iq-alert-icon">
                                <i class="ri-information-line"></i>
                            </div>
                            <div class="iq-alert-text">{{ Session::get('succ') }}</div>
                        </div>
                        @endif
                        <form action="{{ url('import_pesan') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="staticEmail" class="form-label">Judul Pesan</label>
                                <select class="form-control" name="pesan_id" id="pesan_id">
                                    <!-- <option value="custom">Custom</option> -->
                                    @foreach($data as $item)
                                        <option value="{{ $item->pesan_id }}">{{ $item->judul_pesan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                   <span class="input-group-text">Gambar (opsional)</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01" name="gambar">
                                   <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                   <span class="input-group-text">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01" name="file" required>
                                   <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="staticEmail" class="form-label">Isi Pesan</label>
                                <textarea name="isi_pesan" class="form-control"  id="isi_pesan" cols="30" rows="10"></textarea>
                            </div> -->
                            <button type="submit" class="btn btn-primary w-100 mt-3">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        // $(document).ready(function() {
        //     $(".add-more").click(function(){ 
        //         var html = $(".copy").html();
        //         // console.log('ok')
        //         $(".copy").after(html);
        //     });

        //     // saat tombol remove dklik control group akan dihapus 
        //     $("body").on("click",".remove",function(){ 
        //         $(this).parents(".control-group").remove();
        //     });
        // });
        
    </script>
@endpush
