@extends('layouts.main_layout')

@section('content')

<section>
        <div id='loading'style="display:none">
          <img src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif"/>
      </div>
      <div class="container">

        <div class="row">
          <div class="col-12 col-xl-10 offset-xl-1">
            <div class="form_widget">
              <div class="login_block">
                <div class="login_caption">
                  <h2>Lorem Ipsum is simply dummy printing</h2>
                  <p>
                    It is a long established fact that a reader will be
                    distracted by the readable content of a page when looking at
                    its layout.
                  </p>
                </div>
                <div class="login_form">
                  <div class="form_heading">
                    <h3>Welcome back</h3>
                    @if(session()->has('verifymessage'))
                        <div class="alert alert-success">
                            {{ session()->get('verifymessage') }}
                        </div>
                    @endif
                    @if(session()->has('passwordupdate'))
                        <div class="alert alert-success">
                            {{ session()->get('passwordupdate') }}
                        </div>
                    @endif

                    @if(session()->has('verifyalert'))
                        <div class="alert alert-danger">
                            <!-- {{ session()->get('verifyalert') }} -->

                             @if(session()->has('veryfyemail'))
                             Please verify your email first.If you want to receive the verification link again, please check <a href"#" class="resendlink" style="color: #6054f7;" onclick="sendemail('{{ session()->get('veryfyemail') }}')">here </a>


                              <!-- <p onclick="sendemail('{{ session()->get('veryfyemail') }}')"> Press here for send again email.</p> -->
                             @endif
                        </div>
                    @endif
                    @if(session()->has('loginerror'))
                        <div class="alert alert-danger">
                            {{ session()->get('loginerror') }}
                        </div>
                    @endif

                    <p>Login to manage your account.</p>
                  </div>
                  @if(session()->has('message'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div
                        class="alert alert-danger alert-dismissible alert_icon fade show"
                        role="alert"
                      >
                        <div class="d-flex align-items-center">
                          <div class="alert-icon-col">
                            <span class="fa fa-warning"></span>
                          </div>

                            <div class="alert_text">{{ session()->get('message') }}</div>

                          <a
                            href="#"
                            class="close alert_close"
                            data-dismiss="alert"
                            aria-label="close"
                            ><i class="fa fa-close"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  @if(session()->has('successmessage'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div
                        class="alert alert-success alert-dismissible alert_icon fade show"
                        role="alert"
                      >
                        <div class="d-flex align-items-center">
                          <div class="alert-icon-col">
                            <span class="fa fa-warning"></span>
                          </div>

                            <div class="alert_text">{{ session()->get('successmessage') }}</div>

                          <a
                            href="#"
                            class="close alert_close"
                            data-dismiss="alert"
                            aria-label="close"
                            ><i class="fa fa-close"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                      <label>Email address</label>

                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-remember">
                      <div
                        class="d-flex justify-content-between align-items-center"
                      >
                        <div class="custom_checkbox">

                          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="mute_link" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                      </div>
                    </div>
                    <div class="flexy-centered-sm">
                      <button
                        type="submit"
                        class="btn btn-primary btn-md xs-block"
                      >
                        Login
                      </button>
                      <div class="is_account">
                        Don't have an account?
                        <a href="{{ route('register') }}">Signup </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


@endsection
@section('extrascript')
<script>
function sendemail(email){

   $('#loader').css('display','show');
  let _token   = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
    type:'POST',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    url: '/resend-email',
    data:{
      email:email,
      _token: _token
    },
    success:function(data){
      $('#loader').css('display','none');
      alert(data.message);


    }
  });
}
</script>
@endsection
