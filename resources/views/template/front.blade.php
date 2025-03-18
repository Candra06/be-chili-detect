<!DOCTYPE html>
<html lang="en">
@include('template.head')

<body class="main-body app sidebar-mini">

    <!-- Loader -->
    {{-- <div id="global-loader">
        <img src="../../assets/img/loader.svg" class="loader-img" alt="Loader">
    </div> --}}
    <!-- /Loader -->

    <!-- Page -->
    <div class="page">

        {{-- @include('template.sidemenu') --}}
        <!-- main-content -->
        <div class="w-full">

            {{-- @include('template.nav-front') --}}
            <div class="jumps-prevent" style="padding-top: 63.2px;"></div>
            <!-- container -->
            <div class="container-fluid">
                @yield('main')
            </div>
            <!-- /Container -->
        </div>
        <!-- /main-content -->

        @include('template.footer')

    </div>
    <!-- End Page -->

    @include('template.script')
</body>

</html>
