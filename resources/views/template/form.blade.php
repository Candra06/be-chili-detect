@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->subtitle }} </span><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>

            </div>

        </div>

    </div>

    <x-alert />

    <div class="card  box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $data->title }}</h4>
            {{-- <p class="mb-2">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
        </div>
        <div class="card-body pt-0">
            <form enctype="multipart/form-data"
                class="form-horizontal row {{ $errors->any() && 'needs-validation was-validated' }}"
                action="{{ $data->action }}" method="POST">
                @if ($data->type != 'add')
                    @method('PUT')
                @endif
                @csrf
                @php
                    $dataarray = $data->data;
                @endphp
                @foreach ($data->forms as $item)
                    @php
                        $currentValue =
                            $item['type'] == 'currency' && isset($dataarray[$item['name']])
                                ? App\Helper\Helpers::rupiah($dataarray[$item['name']], '')
                                : $dataarray[$item['name']] ?? old($item['name']);
                    @endphp
                    <div
                        class="form-group has-success {{ isset($item['custom-class-wrapper']) ? $item['custom-class-wrapper'] : 'col-md-6 col-12' }}">
                        @if (in_array($item['type'], ['text', 'number', 'email', 'currency', 'hidden', 'date', 'label', 'time', 'file']))
                            @if ($item['type'] != 'hidden')
                                <label for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            @endif
                            @if ($item['type'] == 'label')
                                <p lass="text-blacktext-sm font-medium inline-block mb-2">{{ $currentValue }}</p>
                            @else
                                <input type="{{ $item['type'] }}" class="form-control {{ $item['class_input'] ?? '' }}"
                                    id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                    {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                    value="{{ $currentValue }}" name="{{ $item['name'] }}"
                                    type="{{ $item['type'] == 'currency' ? 'text' : $item['type'] }}"
                                    placeholder="{{ $item['placeholder'] ?? '' }}" />
                            @endif
                        @elseif(in_array($item['type'], ['password']))
                            <label for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            <input type="password" class="form-control {{ $item['class_input'] ?? '' }}"
                                id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                {{ isset($item['other-attr']) ? $item['other-attr'] : '' }} value="{{ $currentValue }}"
                                name="{{ $item['name'] }}"
                                type="{{ $item['type'] == 'currency' ? 'text' : $item['type'] }}"
                                placeholder="{{ $item['placeholder'] ?? '' }}" />
                        @elseif (in_array($item['type'], ['textarea', 'editor']))
                            <label for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            <textarea class="form-control {{ $item['class_input'] ?? '' }}" id="{{ $item['name'] }}"
                                placeholder="{{ $item['placeholder'] ?? '' }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                {{ isset($item['other-attr']) ? $item['other-attr'] : '' }} type="{{ $item['type'] }}" name="{{ $item['name'] }}"
                                value="{{ $dataarray[$item['name']] ?? old($item['name']) }}">{{ $dataarray[$item['name']] ?? old($item['name']) }}</textarea>
                        @elseif (in_array($item['type'], ['select']))
                            @php
                                $value = $dataarray[$item['name']] ?? old($item['name']);
                            @endphp
                            <label for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            <select name="{{ $item['name'] }}" class="form-control {{ $item['class'] }}"
                                {{ $item['required'] ?? false ? ' required ' : '' }}
                                placeholder="{{ $item['placeholder'] }}" {{ $item['required'] ? ' required ' : '' }}
                                value="{{ $value }}">
                                <option value="">{{ $item['placeholder'] }}</option>
                                @foreach ($item['data'] as $it)
                                    <option value="{{ $it['id'] }}" {{ $it['id'] == $value ? 'selected' : '' }}>
                                        {{ $it['val'] }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($item['type'], ['checkbox']))
                            <div class="form-check">

                                <input type="checkbox" class="form-check-input {{ $item['class_input'] ?? '' }}"
                                    id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                    {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                    value="{{ $item['value'] }}" {{ $currentValue == $item['value'] ? 'checked' : '' }}
                                    name="{{ $item['name'] }}" />
                                <label class="form-check-label" for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            </div>
                        @endif

                        @error($item['name'])
                            <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endforeach
                @if (isset($data->custom_element))
                    {!! $data->custom_element !!}
                @endif
                <div class="form-group has-success col-12 mb-0 mt-3 justify-content-end">
                    <div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        @if (isset($data->custom_button))
                            {!! $data->custom_button !!}
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>

        window.addEventListener("load", function(event) {
            $(document).ready(function() {
                const selectName = $('.select2');
                const elmSelectWInnerCodeBs = ".winner-select-code-bs";
                initForm()
                // initializeSelect2($('.select2').attr('name'),$('.select2').attr('placeholder'))
            });
        });
        const elmSelectWInnerSupplier = ".winner-select-supplier";
        const elmSelectWInnerCodeBs = ".winner-select-code-bs";

        async function initForm() {
            const elmSelectYear = ".get-year-container";
            if ($(elmSelectYear).length > 0) {
                var currentYear = new Date().getFullYear(),
                    years = [];
                let endYear = currentYear + 100;
                while (currentYear <= endYear) {
                    years.push(currentYear);
                    currentYear++
                }
                const valueSelect = $(elmSelectYear).attr('value')
                $(`${elmSelectYear} option`).remove();
                $(elmSelectYear).append(`<option value="">Select Departure Year</option>`);
                years.forEach(element => {
                    var selected = valueSelect == element ? "selected" :
                        "";
                    $(elmSelectYear).append(
                        `<option value="${element}" ${selected}>${element}</option>`);
                });
            }
            $(document).on("change", elmSelectWInnerSupplier, async function() {
                dataCodeBSbySupplier($(this).val(), true)
            });
            if ($(elmSelectWInnerSupplier).length > 0) {
                dataCodeBSbySupplier($(elmSelectWInnerSupplier).val(), false)
            }

            $('.select2').each(function() {
                var placeholder = $(this).attr('placeholder').split(/\s+/);
                $('.select2').select2({
                    placeholder: placeholder,
                    searchInputPlaceholder: 'Search',
                    dropdownPosition: 'below'
                });
            });
            $('.select2-no-search').each(function() {
                var placeholder = $(this).attr('placeholder').split(/\s+/);
                $('.select2-no-search').select2({
                    minimumResultsForSearch: Infinity,
                    placeholder: placeholder
                });
            });
        }



        async function dataCodeBSbySupplier(suppliedId, resetValue){
            if (suppliedId.length > 0) {
                if (resetValue) {
                    $(elmSelectWInnerCodeBs).val("");
                }
                const response = await fetch(
                    `{{ url('/api/') }}/data-code-bs?id_supplier=${suppliedId}`
                );
                const data = await response.json();
                if (typeof data.data != "undefined") {
                    const valueSelect =$(elmSelectWInnerCodeBs).attr('value');

                    $(`${elmSelectWInnerCodeBs} option`).not(":first").remove();
                    data?.data.forEach(d => {

                        const selected = valueSelect == d.id ?
                            "selected" : "";
                        $(elmSelectWInnerCodeBs).append(
                            `<option value="${d?.id}" ${selected}>${d?.code_bs}</option>`);
                    });
                }
            }
        }

        function previewImage() {
            const image = document.querySelector('#avatar');
            const imgPreview = document.querySelector('#preview-image');

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        // Jquery Dependency

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            input_val = formatNumber(input_val);

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
