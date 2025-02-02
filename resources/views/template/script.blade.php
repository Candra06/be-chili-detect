<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>

<!-- JQuery min js -->
<script src="{{ url('/') }}/assets/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Bundle js -->
<script src="{{ url('/') }}/assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="{{ url('/') }}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!--Internal  Chart.bundle js -->
<script src="{{ url('/') }}/assets/plugins/chart.js/Chart.bundle.min.js"></script>

<!-- Ionicons js -->
<script src="{{ url('/') }}/assets/plugins/ionicons/ionicons.js"></script>

<!--Internal Sparkline js -->
<script src="{{ url('/') }}/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="{{ url('/') }}/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{ url('/') }}/assets/plugins/perfect-scrollbar/p-scroll.js"></script>

<!-- Eva-icons js -->
<script src="{{ url('/') }}/assets/js/eva-icons.min.js"></script>

<!-- Left-menu js-->
<script src="{{ url('/') }}/assets/plugins/side-menu/sidemenu.js"></script>
<!--Internal  index js -->
<script src="{{ url('/') }}/assets/js/index.js"></script>

<script src="{{ url('/') }}/assets/plugins/select2/js/select2.min.js"></script>
<!-- custom js -->
<script src="{{ url('/') }}/assets/js/custom.js"></script>
<script src="{{ asset('assets') }}/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatable/datatables.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="{{ asset('assets') }}/js/table-data.js"></script>

{{-- step js & parsley js --}}
<script src="{{ asset('assets') }}/plugins/jquery-steps/jquery.steps.min.js"></script>
<script src="{{ asset('assets') }}/plugins/parsleyjs/parsley.min.js"></script>
<script src="{{ asset('assets') }}/js/tooltip.js"></script>

{{-- alert auto close --}}
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        stateSave: true
    });
    $(document).ready(function() {
        //untuk menutup alert secara otomatis
        $(".alert").hide();
        $(".alert").fadeTo(5000, 500).slideUp(100, function() {
            $(".alert").slideUp(500);
        });
        $("table").on('draw.dt', function() {
            $('.dataTables_empty').html('Tidak ditemukan data yang cocok');
            $('.dataTables_processing').html('Memproses.....');
            // $("td").has("button:not(.no-action),a:not(.no-action)").addClass("d-flex")
        });

    });
</script>
<script>
    //handle dropdown close if click outside
    $(document).ready(function() {
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.dropdown > * ').length) {
                $('.dropdown').removeClass('show');
            }
        });
    });

    $('input[type="file"]').each(function() {
        $(this).change(function(e) {
            const fileInput = e.target;
            var fileName = '';
            if (fileInput.files.length > 0) {
                fileName = fileInput.files[0].name;
            }
            $(fileInput).parent().css("--content", '"' + fileName + '"')
        });
    })

    $('input[type="file"]:not(.d-none)').each(function() {
        $(this).wrap(`<label class='file-input-wrapper d-block border'></label>`);
    });
</script>

<script>

    $('input[type="file"]').each(function() {
        $(this).change(function(e) {
            const fileInput = e.target;
            var fileName = '';
            if (fileInput.files.length > 0) {
                fileName = fileInput.files[0].name;
            }
            $(fileInput).parent().css("--content", '"' + fileName + '"')
        });
    })

    $('input[type="file"]:not(.d-none)').each(function() {
        $(this).wrap(`<label class='file-input-wrapper d-block border'></label>`);
    });
</script>

{{-- handle html input --}}
<script>
    $(document).ready(function() {
        $("input[type='number']").attr("type", "text").on("input", function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
        $("input,textarea").on("input", function() {
            var cleaned = $(this).val().replace(/<[^>]+>/g, ''); // Menghapus semua tag HTML
            var output = cleaned.replace(/=[^=+\-/*^()A-Za-z0-9. ]+/g, '');
            $(this).val(output);
        });

        $("form").on("focus", "[type='date']", function() {
            this.showPicker();
        })
    });

    $(".accordion-toggle").click(function() {
        if ($(this).parent().parent().hasClass("bg-white")) {
            $(this).parent().parent().removeClass("bg-white");
            $(this).parent().parent().css("background", "#FBF8FD")

        } else {
            $(this).parent().parent().addClass("bg-white");
        }
    })
</script>
@yield('script')
