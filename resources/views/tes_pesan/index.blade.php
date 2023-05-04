@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Tes Pesan &nbsp;</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('tes_pesan/kirim') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="staticEmail" class="form-label">Judul Pesan</label>
                                <select class="form-control" name="pesan_id" id="pesan_id">
                                    @foreach($data as $item)
                                        <option value="{{ $item->pesan_id }}">{{ $item->judul_pesan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="staticEmail" class="form-label">No Telp Penerima (62)</label>
                                <input type="text" class="form-control" id="no_penerima" name="no_penerima" required>
                            </div>
                            <div class="mb-3 after-add-more">
                                <label for="staticEmail" class="form-label">Variabel</label>
                                <input type="text" class="form-control" id="var[]" name="var[]">
                            </div>
                            <div class="copy" style="display: none">
                                <div class="control-group" >
                                    <label for="staticEmail mt-3" class="form-label">Variabel</label>
                                    <input type="text" class="form-control" id="var[]" name="var[]">
                                </div>
                            </div>
                            <div class="selanjutnya"></div>
                            
                            
                            <div class="row mt-3">
                                <div class="col">
                                    <button class="btn btn-success add-more w-100" type="button">Add</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger remove w-100" type="button">Remove</button>
                                </div>
                            </div>
                            {{-- <div class="mb-3">
                                <label for="staticEmail" class="form-label">Isi Pesan</label>
                                <textarea name="isi_pesan" class="form-control"  id="isi_pesan" cols="30" rows="10"></textarea>
                            </div> --}}
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
        $(document).ready(function() {
            $(".add-more").click(function(){ 
                var html = $(".copy").html();
                // console.log('ok')
                $(".copy").after(html);
            });

            // saat tombol remove dklik control group akan dihapus 
            $("body").on("click",".remove",function(){ 
                $(this).parents(".control-group").remove();
            });
        });
        function edit(id) {
            $.ajax({
                type: 'get',
                url: "{{ url('pesan/edit') }}/" + id,
                // data:{'id':id}, 
                success: function(tampil) {

                    // console.log(tampil); 
                    $('#tampildata').html(tampil);
                    $('#editModal').modal('show');
                }
            })
        }
    </script>
@endpush
