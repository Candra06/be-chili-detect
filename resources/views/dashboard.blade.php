@extends('template.app')

@section('css')

    <style>


        .panel {
            width: 300px;
            padding: 10px;
            background-color: white;
            margin-bottom: 10px;
            position: absolute;
            z-index: 899;
            top: 10px;
            right: 10px;
        }

        .panel .row {
            max-height: calc(70vh - 100px);
            overflow: auto;
        }

        .panel.stakeholder {
            left: 10px;
        }

        .panel ul li {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter {
            position: absolute;
            z-index: 899;
            top: 10px;
            right: 10px;
        }



        .info-card {
            min-height: 85px;
        }
    </style>
@endsection
@section('main')

    <!-- breadcrumb -->
    <x-alert />
    <div class="container-fluid">
        <div class="row mt-3 mb-3 ">

            <div class="col-md-4 col-12">
                <div class="card info-card">
                    <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                        <div class="icon"
                            style="font-size: 30px; background-color: #187498; color: white; padding: 8px 16px; border-radius: 6px;">
                            <i class="fa fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold fs-6">{{$data->penyakit}}</p>
                            <p class="mb-0">Total Penyakit</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4 col-12">
                <div class="card info-card">
                    <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                        <div class="icon"
                            style="font-size: 30px; background-color: #187498; color: white; padding: 8px 16px; border-radius: 6px;">
                            <i class="fa fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold fs-6">{{$data->gejala}}</p>
                            <p class="mb-0">Total Gejala</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="card info-card">
                    <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                        <div class="icon"
                            style="font-size: 30px; background-color: #187498; color: white; padding: 8px 16px; border-radius: 6px;">
                            <i class="fa fa-coins"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold fs-6">{{$data->hasil}}</p>
                            <p class="mb-0">Total Data Set</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection



@section('script')
    <script>

        $('[name=start_date]').on('change', function() {
            if ($(this).val() !== '') {
                $('[name=end]').prop('required', true);
                $('[name=end]').prop('min', $(this).val());

            } else {
                $('[name=end]').prop('required', false);
            }
        });

        $('[name=end]').on('change', function() {
            if ($(this).val() !== '') {
                $('[name=start_date]').prop('required', true);
                $('[name=start_date]').prop('max', $(this).val());

            } else {
                $('[name=start_date]').prop('required', false);
            }
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> --}}

@endsection
