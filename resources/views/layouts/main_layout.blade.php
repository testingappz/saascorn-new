<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{asset('images/favicon.svg') }}" />
    <!-- Bootstrap CSS -->

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel="stylesheet"/>



   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}"/>

    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"
    ></script>
    <script src="{{ asset('js/bootstrap-notify.js') }}"></script>




      @if(Auth::check() && Auth::user()->user_type === 'owner')

      <script src="{{ asset('js/owner.js') }}"></script>

      @elseif(Auth::check() && Auth::user()->user_type === 'investor')

      <script src="{{ asset('js/investor.js') }}"></script>

      @endif

     @if(Route::currentRouteName()=="register")
     <title>Signup</title>
     @else
     <title>{{ucfirst(Route::currentRouteName())}}</title>
     @endif

  </head>
  <body class="@if(Auth::check()) inner_pages @endif" cz-shortcut-listen="true">
    <header class="navbar-custom-fixed">
      <div class="container">
        <nav class="navbar navbar-expand-lg navbar-custom">
          <a class="navbar-brand" href="javascript:void(0)">
            <img src="{{ asset('images/logo.svg') }}" alt="logo" />
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon">
              <span id="hamburgerTrigger" class="u-hamburger__box">
                <span class="u-hamburger__inner"></span>
              </span>
            </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item active">
                <a onclick="alert('In Progress');" class="nav-link" href="javascript:;"
                  >Home <span class="sr-only">(current)</span></a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:;" onclick="alert('In Progress');">Academy Center</a>
              </li>
              <li class="nav-item">

                <a class="nav-link" href="@if(Auth::check() && Auth::user()->user_type === 'owner') {{ route('dashboard') }} @elseif(Auth::check() && Auth::user()->user_type === 'investor') {{ route('investordashboard') }} @else javascript:; @endif" onclick="@if(!Auth::check()) alert('In Progress'); @endif">@if(Auth::check() && Auth::user()->user_type === 'owner') Manage Projects @else Browse Opportunities @endif</a>
              </li>
              <li class="nav-item">
                @if(Auth::check() && Auth::user()->user_type === 'owner')
                <a class="nav-link" href="{{ route('addNewInvestmentForm') }}">Fund My Startup</a>
                @else
                 <a class="nav-link" href="javascript:;" onclick="alert('In Progress');">Fund My Startup</a>
                @endif

              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:;" onclick="alert('In Progress');">Contact</a>
              </li>
                @if (Route::has('login'))
                  @auth
                  <li class="nav-item md-mt-2">
                    <a class="btn btn-primary btn-rounded btn-sm md-block" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </li>
                  @else
                  <li class="nav-item md-mt-2">
                    <a
                      class="btn @if(Route::current()->getName() == 'login') btn-primary @else btn-outline-secondary @endif  btn-rounded btn-sm md-block"
                      href="{{ route('login') }}"
                      >Login</a
                    >
                  </li>


                      @if (Route::has('register'))
                          <li class="px-0 nav-item md-mt-3">
                            <a
                              class="btn @if(Route::current()->getName() != 'login') btn-primary @else btn-outline-secondary @endif btn-rounded btn-sm md-block"
                              href="{{ route('register') }}"
                              >Signup</a
                            >
                        </li>
                      @endif
                  @endauth
                @endif

            </ul>
          </div>
        </nav>
      </div>
    </header>
      @yield('content')
    <!------------------------footer------------------->
    <footer>
      <div class="container">

        <div class="row">
          <div class="col-custom col-custom-4">
            <div class="footer_top">
              <div class="logo">

                <img src="{{asset('images/logo-white.svg') }}" alt="icon" />
              </div>
              <p>
                We’ve expanded well beyond our flagship technology research to
                provide senior leaders
              </p>
              <ul class="social_links">
                <li>
                  <a href="javascript:;">
                    <img src="{{asset('images/fb.svg') }}" alt="icon" />
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{asset('images/tw.svg') }}" alt="icon" />
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{asset('images/link.svg') }}" alt="icon" />
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{asset('images/youtube.svg') }}" alt="icon" />
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-custom col-custom-2_5">
            <div class="footer_top">
              <h4>Quick Links</h4>
              <ul class="links">
                <li>
                  <a href="javascript:;">About Us</a>
                </li>
                <li>
                  <a href="javascript:;">How it Works</a>
                </li>
                <li>
                  <a href="javascript:;">Privacy Policy</a>
                </li>
                <li>
                  <a href="javascript:;">Terms of Use</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-custom col-custom-2_5">
            <div class="footer_top">
              <h4>Other Links</h4>
              <ul class="links">
                <li>
                  <a href="javascript:;">Blog</a>
                </li>
                <li>
                  <a href="javascript:;">FAQ</a>
                </li>
                <li>
                  <a href="javascript:;">Acedemy</a>
                </li>
                <li>
                  <a href="javascript:;">Fund my Startup</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-custom col-custom-3">
            <div class="footer_top">
              <h4>Contact Us</h4>
              <ul class="links">
                <li>
                  <a href="javascript:;"
                    ><img src="{{asset('images/address.svg') }}" alt="icon" /> 1055 Arthur ave
                    Elk Grove Village, IL 60007</a
                  >
                </li>
                <li>
                  <a href="javascript:;"
                    ><img src="{{asset('images/phone.svg') }}" alt="icon" /> +1 800-433-730</a
                  >
                </li>
                <li>
                  <a href="javascript:;"
                    ><img
                      src="{{asset('images/mail.svg') }}"
                      alt="icon"
                    />saascorn@email.com</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="footer_bottom">© 2020 SaaScorn copyright reserved.</div>
          </div>
        </div>

      </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->


    <script>

      $(document).ready(function () {

          toggleClasses();

          $(window).scroll(function () {
            toggleClasses();
          });

          function toggleClasses() {
            let scroll = $(window).scrollTop();
            if (scroll >= 5) {
              $(".navbar-custom-fixed").addClass("ui_scrolled");
            } else {
              $(".navbar-custom-fixed").removeClass("ui_scrolled");
            }
          }

      });

      $('.datetimepicker').datetimepicker({
          format: 'YYYY-MM-DD',
          minDate:new Date(),
          icons: {
          previous: "fas fa-chevron-left",
          next: "fas fa-chevron-right",

        },
      });

       $('.datetimepickerdob').datetimepicker({
          format: 'YYYY-MM-DD',
          maxDate:new Date(),
          icons: {
          previous: "fas fa-chevron-left",
          next: "fas fa-chevron-right",

        },
      });

        $('.datetimepickerdobupdate').datetimepicker({
          format: 'YYYY-MM-DD',
          icons: {
          previous: "fas fa-chevron-left",
          next: "fas fa-chevron-right",
        },
         widgetPositioning: {
                        horizontal: 'left',
                        vertical: 'top'
                    }

      });


       // $('.datetimepickerdobupdate').data("DateTimePicker").show();


        $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
          $(".alert-success").slideUp(500);
        });

        $("alert-error").fadeTo(2000, 500).slideUp(500, function(){
          $("alert-error").slideUp(500);
        });

    </script>

      @yield('extrascript');

    <!------------------------footer------------------->
  </body>
</html>
