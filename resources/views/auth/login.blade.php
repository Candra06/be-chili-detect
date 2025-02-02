<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Admin Panel | MASUK</title>
    <link
      rel="icon"
      href="{{ url('/') }}/assets/img/brand/favicon.png"
      type="image/x-icon"
    />

    <!-- Icons css -->
    {{--
    <link href="{{ url('/') }}/assets/css/icons.css" rel="stylesheet" />
    --}}

    <!-- Bootstrap css -->
    <link
      href="{{ url('/') }}/assets/plugins/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!--  Owl-carousel css-->
    {{--
    <link
      href="{{ url('/') }}/assets/plugins/owl-carousel/owl.carousel.css"
      rel="stylesheet"
    />
    --}}

    <!-- P-scroll bar css-->
    {{--
    <link
      href="{{ url('/') }}/assets/plugins/perfect-scrollbar/p-scrollbar.css"
      rel="stylesheet"
    />
    --}}

    <!--  Right-sidemenu css -->
    {{--
    <link
      href="{{ url('/') }}/assets/plugins/sidebar/sidebar.css"
      rel="stylesheet"
    />
    --}}

    <!-- Sidemenu css -->
    {{--
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/sidemenu.css" />
    --}}

    <!-- Maps css -->
    {{--
    <link
      href="{{ url('/') }}/assets/plugins/jqvmap/jqvmap.min.css"
      rel="stylesheet"
    />
    --}}

    <!-- style css -->
    <link href="{{ url('/') }}/assets/css/style.css" rel="stylesheet" />
    <link href="{{ url('/') }}/assets/css/style-dark.css" rel="stylesheet" />
    <link href="{{ url('/') }}/assets/css/boxed.css" rel="stylesheet" />
    <link href="{{ url('/') }}/assets/css/dark-boxed.css" rel="stylesheet" />
    <link href="{{ url('/') }}/assets/css/icons.css" rel="stylesheet" />

    <!---Skinmodes css-->
    <link href="{{ url('/') }}/assets/css/skin-modes.css" rel="stylesheet" />

    <style>
      .passwordIndikator {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 100%;
        width: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99;
      }
    </style>
  </head>

  <body>
    <div class="page">
      <div class="container-fluid">
        <div
          class="row justify-content-center no-gutter"
          style="
            background-image: linear-gradient(
              to right,
              rgb(255, 228, 230),
              rgb(204, 251, 241)
            );
          "
        >
          <!-- The image half -->
          {{--
          <div
            class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent"
          >
            <div class="row wd-100p mx-auto text-center">
              <div
                class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p"
              >
                <img
                  src="../../assets/img/media/login.png"
                  class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto"
                  alt="logo"
                />
              </div>
            </div>
          </div>
          --}}
          <!-- The content half -->
          <div class="col-md-6 col-lg-6 col-xl-5">
            <div class="login d-flex align-items-center py-2">
              <!-- Demo content-->
              <div class="card container p-0">
                <div class="row">
                  <div class="col-md-11 col-lg-11 col-xl-11 mx-auto">
                    <div class="card-sigin">

                      <div class="card-sigin mb-5 mt-5">
                        <div class="main-signup-header">
                          <x-alert />
                          <form action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                              <label>Username</label>
                              <input
                                value="{{ old('username') }}"
                                required
                                name="username"
                                class="form-control"
                                placeholder="Email"
                                type="text"
                              />
                            </div>
                            <div class="form-group">
                              <label>Kata Sandi</label>
                              <div class="input-group position-relative">
                                <input
                                  name="password"
                                  required
                                  class="form-control"
                                  placeholder="Kata Sandi"
                                  type="password"
                                />
                                <span class="passwordIndikator">
                                  <i class="fa fa-eye"></i
                                ></span>
                              </div>
                            </div>
                            <button
                              style="background: #3557bc; font-weight: bold"
                              type="submit"
                              class="btn btn-main-primary btn-block"
                            >
                              Masuk
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End -->
            </div>
          </div>
          <!-- End -->
        </div>
      </div>
    </div>

    <script src="{{ url('/') }}/assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Bundle js -->
    <script src="{{
        url('/')
      }}/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="{{
        url('/')
      }}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!--Internal  Chart.bundle js -->
    <script src="{{
        url('/')
      }}/assets/plugins/chart.js/Chart.bundle.min.js"></script>

    <!-- Ionicons js -->
    <script src="{{ url('/') }}/assets/plugins/ionicons/ionicons.js"></script>

    <!--Internal Sparkline js -->
    <script src="{{
        url('/')
      }}/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

    <!--Internal  Perfect-scrollbar js -->
    <script src="{{
        url('/')
      }}/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{
        url('/')
      }}/assets/plugins/perfect-scrollbar/p-scroll.js"></script>

    <!-- Eva-icons js -->
    <script src="{{ url('/') }}/assets/js/eva-icons.min.js"></script>

    <!-- Left-menu js-->
    <script src="{{ url('/') }}/assets/plugins/side-menu/sidemenu.js"></script>

    <!--Internal  index js -->
    <script src="{{ url('/') }}/assets/js/index.js"></script>

    <!-- custom js -->
    <script src="{{ url('/') }}/assets/js/custom.js"></script>

    <script>
      $('.passwordIndikator').click(function () {
        const password = $(this).parent().parent().find('[name=password]');
        if ($(password).attr('type') == 'password') {
          $(password).attr('type', 'text');
        } else {
          $(password).attr('type', 'password');
        }
      });
    </script>
  </body>
</html>
