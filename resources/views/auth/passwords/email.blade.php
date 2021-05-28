@extends('layouts.main_layout')

@section('content')

<section>
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
                    <h3>Forgot Password</h3>
                    <p>Forgot your password.</p>
                  </div>
                  @if(session()->has('message'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div
                        class="alert alert-danger-outline alert-dismissible alert_icon fade show"
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
                  @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($errors) > 0)
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                      @if(session()->has('success'))
                           <span  role="alert">
                             <div class="alert alert-success">
                                 <ul>
                                   <li>  {{ session()->get('success') }}</li>
                                 </ul>
                             </div>
                          </span>
                      @endif


                    <form method="POST" action="{{ route('forgetpassword.email') }}">
                        @csrf

                        <div class="form-group">
                            <label>{{ __('E-Mail Address') }}</label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email">

                                @if(session()->has('notfoundalert'))
                                     <span  role="alert">
                                        {{ session()->get('notfoundalert') }}
                                    </span>
                                @endif
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                        </div>

                        <div class="flexy-centered-sm">
                             <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
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
