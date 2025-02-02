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
        <div class="d-flex my-xl-auto right-content">
            <div class="pe-1 mb-xl-0">
                @if ($data->createBtn)
                    <a href="{{ $data->routeAdd }}"><button class="btn btn-primary">
                            Tambah Aturan</button></a>
                @endif
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
            <div class="table-responsive">
                <table id="table" class="table mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            @foreach ($data->tableHead as $head)
                                <th>{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        {{-- modal delete --}}
        <x-modal-delete />

        <div class="modal fade" id="modal-detail">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title"></h6><button aria-label="Close" class="close" data-bs-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table mg-b-0 text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>Kode Gajala</th>
                                    <th>Gajala</th>
                                    <th>Densitas</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">

                            </tbody>
                        </table>
                        <div class="row mt-3">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#table").DataTable({
            ajax: '{{ $data->routeData }}',
            processing: true,
            serverSide: true,
            stateSave: true,
            columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`)
        });
    </script>


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }
        function viewDetail(url) {


            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var resp = response['data'];

                    let htmlData = '';
                    const gejala = response.data.gejala;

                    gejala.forEach(item => {
                        // htmlData += `<div class="col-md-12 mt-1"><label for="" style="display:block;">(${item.kode_gejala})${item.gejala} : ${item.densitas}</label></div>`;
                        htmlData += `<tr><td>${item.kode_gejala}</td><td>${item.gejala}</td><td>${item.densitas}</td></tr>`;
                    console.log(`ID: ${item.id}, Gejala: ${item.gejala}, Densitas: ${item.densitas}`);
                    });

                    $('#modal-detail').find('.tbody').html(htmlData);
                    $('#modal-detail').find('.modal-title').html(resp['penyakit']);
                },
                error: function() {
                    alert('There was an error loading the modal content.');
                }
            });


        }
    </script>
@endsection
