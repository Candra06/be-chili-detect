@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css">
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
@endsection

@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>
            </div>

        </div>

    </div>

    {{-- alert component untuk handle error dan success --}}
    <x-alert />

    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $data->title }}</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body p-0 mt-3">
                <form action="{{$data->action}}" enctype="multipart/form-data"
                    class="form-horizontal row" method="post">
                    @csrf
                    <div class="form-group has-success col-md-12">
                        <label for="reminder">Penyakit</label>
                        <select name="penyakit_id" id="" class="form-control select2" placeholder="Pilih Penyakit">
                            <option value="">Pilih Penyakit</option>
                            @foreach ($data->data->penyakit as $item)
                                <option value="{{$item->id}}">{{$item->penyakit.'('.$item->kode_penyakit.')'}}</option>

                            @endforeach
                        </select>
                        @error('reminder')
                            <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group has-success col-md-12 col-12">
                        <label for="reminder">Gejala</label><br />
                        @foreach ($data->data->gejala as $item)
                        <div class="form-check">
                            <input type="hidden" name="id_gejala[]" value="{{$item->id}}">
                            <input type="checkbox" class="form-check-input me-3"
                            value="0" onchange="handleCheckboxChange(this.id, this.checked, this)" data-densitas="{{$item->densitas}}"
                            id="gejala-{{$item->id}}" name="gejala-{{$item->id}}" />
                            <label class="form-check-label" for="gejala-{{ $item->id }}">{{ $item->gejala.'('.$item->kode_gejala.')' }}</label><br/>
                        </div>
                        @endforeach
                        @error('information')
                        <small class=" text-danger">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="form-group has-success col-12 mb-0 mt-3 justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        {{-- modal delete --}}
        <x-modal-delete />
    </div>
@endsection
@section('script')
    <script>
        window.addEventListener("load", function(event) {
            $(document).ready(function() {
                const selectName = $('.select2');

                // initializeSelect2($('.select2').attr('name'),$('.select2').attr('placeholder'))
            });
        });
        $('.select2').each(function() {
                var placeholder = $(this).attr('placeholder').split(/\s+/);
                $('.select2').select2({
                    placeholder: placeholder,
                    searchInputPlaceholder: 'Search',
                    dropdownPosition: 'below'
                });
            });
    </script>


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }
    </script>

    <script>
        function handleCheckboxChange(id, isChecked, densitas) {
            const val = densitas.getAttribute("data-densitas");
            // const val = element.getAttribute("data-densitas");
            const value = isChecked ? val : 0;
            console.log(`Checkbox ID: ${id}, Value: ${value}`);
            $('#'+id).val(value);
            if (value != 0) {

                $('#'+id).prop('checked', true);
            } else {
                $('#'+id).prop('checked', false);
            }
        // You can send this value to your server or process it further
        }
    </script>
@endsection
