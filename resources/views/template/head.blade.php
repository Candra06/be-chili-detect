<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">

    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />

    <!-- Title -->
    <title> Admin Panel </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('/') }}/assets/img/brand/favicon.jpeg" type="image/x-icon" />

    <!-- Icons css -->
    <link href="{{ url('/') }}/assets/css/icons.css" rel="stylesheet">

    <!-- Bootstrap css -->
    <link href="{{ url('/') }}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ url('/') }}/assets/css/icons.css" rel="stylesheet">

    <!-- P-scroll bar css-->
    <link href="{{ url('/') }}/assets/plugins/perfect-scrollbar/p-scrollbar.css" rel="stylesheet" />

    <!-- Internal Select2 css -->
    <link href="{{ url('/') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

    <!-- Sidemenu css -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/sidemenu.css">

    <!-- style css -->
    <link href="{{ url('/') }}/assets/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/css/style-dark.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/css/boxed.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/css/dark-boxed.css" rel="stylesheet">

    <!---Skinmodes css-->
    <link href="{{ url('/') }}/assets/css/skin-modes.css" rel="stylesheet" />
    <style>
        .file-input-wrapper {
            position: relative;
            --content: "Tidak ada file yang dipilih";
        }

        .file-input-wrapper::after {
            content: "Pilih File";
            position: absolute;
            left: 0;
            top: 0;
            background: #e9ecef;
            height: 100%;
            width: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-input-wrapper::before {
            content: var(--content);
            position: absolute;
            left: 110px;
            top: 0;
            padding-left: 5px;
            background: white;
            height: 100%;
            width: calc(100% - 110px);
            display: flex;
            align-items: center;

        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* For Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .panel-heading1 a:not(.no-child):before {
            left: 10px;
            content: "\e92d";
        }

        .panel-heading1.last a:not(.no-child):before {
            left: 10px;
            content: "";
        }

        .panel-heading1 a.collapsed:not(.no-child):before {
            content: "\e92f";
            left: 10px;
        }

        .capitalize-first::first-letter {
            text-transform: capitalize !important;
        }

        select{
            appearance: none; /* Remove default browser arrow */
            -webkit-appearance: none; /* Safari & Chrome */
            -moz-appearance: none; /* Firefox */
            background: url('data:image/svg+xml;utf8,<svg fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 35px;
        }
        /* .select::after {
        font-family: "Font Awesome 5 Free";
        content: "\f078";
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        } */
    </style>
    @yield('css')

</head>
