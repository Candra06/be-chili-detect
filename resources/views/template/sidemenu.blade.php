<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ url('/dashboard') }}"><img
                src="{{ asset('assets/img/brand/logo.png') }}"class="main-logo" alt="logo"></a>

        <a class="logo-icon mobile-logo icon-light active" href="{{ url('/dashboard') }}"><img
                src="../../assets/img/brand/favicon.png" class="logo-icon" alt="logo"></a>

    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround"
                        src="{{ asset('assets/img/avatar/default.png') }}"
                        onerror="this.onerror=null;this.src='{{ asset('assets/img/avatar/default.png') }}';"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="fw-semibold mt-3 mb-0">{{ Auth::user()->username }}</h4>

                    <span class="mb-0 text-muted">Admin</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">Main</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/dashboard') }}">
                    <span class="side-menu__icon "><i class="fe fe-grid"></i></span>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/penyakit') }}">
                    <span class="side-menu__icon "><i class="fe fe-grid"></i></span>
                    <span class="side-menu__label">Penyakit</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/gejala') }}">
                    <span class="side-menu__icon "><i class="fe fe-grid"></i></span>
                    <span class="side-menu__label">Gejala</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/rules') }}">
                    <span class="side-menu__icon "><i class="fe fe-grid"></i></span>
                    <span class="side-menu__label">Aturan</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{url('logout')}}">
                    <span class="side-menu__icon "><i class="bx bx-log-out"></i></span>
                    <span class="side-menu__label">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
