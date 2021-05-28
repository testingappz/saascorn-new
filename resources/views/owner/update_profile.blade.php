@extends('layouts.main_layout')

@section('content')
<style>
  .invalid
  {
    color:#dc3545;
  }
</style>

<section class="padding-70">
      <div class="container">
        <div class="row mb-50px">
          <div class="col-12">
            <div class="form_widget">
              <div class="overview">
                  <div class="profile_view">
                      <div class="profile_img">
                        <img id="profile_pic_data" class="responsive" src="@if (isset($userDetails->profile_image) && !empty($userDetails->profile_image)) {{env('AWS_BUCKET_PATH')}}images/{{$userDetails->profile_image }} @else images/image_placeholder.svg @endif" alt="image"/>
                      </div>
                      <div class="input_file">
                        <h3>@if (isset($userDetails->first_name) && !empty($userDetails->first_name)) {{$userDetails->first_name }} @endif @if (isset($userDetails->last_name) && !empty($userDetails->last_name)) {{$userDetails->last_name }} @endif</h3>
                        <form method="POST" id="upload_picture" enctype="multipart/form-data" action="{{ route('uploadpic') }}">
                          {{ csrf_field() }}
                          <div class="input_file">
                              <label for="file_upload" class="btn btn-mute btn-xs">Upload New Profile Picture</label>
                              <input id="file_upload" type="file" accept=".png, .jpg, .jpeg" name="profile_picture"/>
                          </div>
                        </form>
                      </div>
                  </div>
                  <div class="profile_budget">
                    <div class="budget">
                      <h2>@if(isset($countInvData[0])){{ $countInvData[0]}}@endif</h2>
                       <h5>No. of Investments</h5>
                    </div>
                    <div class="budget">
                      <h2>@if(isset($countInvData[1]))${{ $countInvData[1]}}@endif</h2>
                       <h5>Funding Goals</h5>
                    </div>
                    <div class="budget">
                      <h2>@if(isset($countInvData[2]))${{ $countInvData[2]}}@endif</h2>
                       <h5>Total Raised</h5>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form_widget">
              <div class="login_block">

               
                 
                <div class="inner_form innner_form_tabs">
                 <!-- Nav pills -->
                  <div class="form_tabs">

                    <ul class="nav nav-pills">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#profile">Profile</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#change_password">Change Password</a>
                      </li>
                    </ul>
                  </div>
                   @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                  @endif
                   @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                  @endif
                  
                  <div class="alert" id="message" style="display: none"></div>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div class="tab-pane  active" id="profile">   
                      <form id="full_profile" method="post" action="{{ route('updateProfile') }}">  
                       {{ csrf_field() }}                   
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>First Name</label> 
                               <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="@if(isset($userDetails->first_name) && !empty($userDetails->first_name)){{$userDetails->first_name}}@endif" required autocomplete="first_name" autofocus placeholder="Required"/>
                              @if ($errors->has('first_name'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('first_name') }}.</strong>
                              </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Last Name</label> 
                              <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="@if(isset($userDetails->last_name) && !empty($userDetails->last_name)){{$userDetails->last_name }}@endif" required autocomplete="last_name" autofocus placeholder="Required"/>
                              @if ($errors->has('last_name'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('last_name') }}.</strong>
                              </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Email</label> 
                               <input readonly id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="@if (isset($userDetails->email) && !empty($userDetails->email)){{$userDetails->email}}@endif" required autocomplete="email" autofocus placeholder="Required"/>
                              @if ($errors->has('email'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('email') }}.</strong>
                              </span>
                              @endif
                            </div> 
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Phone</label> 
                              <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="@if(isset($userDetails->userdetails->phone) && !empty($userDetails->userdetails->phone)) {{$userDetails->userdetails->phone}}@endif" required autocomplete="phone" autofocus placeholder="Required"/>
                              @if ($errors->has('phone'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('phone') }}.</strong>
                              </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Address Line 1</label> 
                              <input id="address_line_1" type="text" class="form-control @error('address_line_1') is-invalid @enderror" name="address_line_1" value="@if(isset($userDetails->userdetails->address_line_1) && !empty($userDetails->userdetails->address_line_1)){{$userDetails->userdetails->address_line_1}}@endif" required autocomplete="address_line_1" autofocus placeholder="Required"/>
                              @if ($errors->has('address_line_1'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('address_line_1') }}.</strong>
                              </span>
                              @endif
                            </div> 
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Address Line 2</label> 
                              <input id="address_line_2" type="text" class="form-control @error('address_line_2') is-invalid @enderror" name="address_line_2" value="@if (isset($userDetails->userdetails->address_line_2) && !empty($userDetails->userdetails->address_line_2)){{$userDetails->userdetails->address_line_2 }}@endif" required autocomplete="address_line_2" autofocus placeholder="Required"/>
                              @if ($errors->has('address_line_2'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('address_line_2') }}.</strong>
                              </span>
                              @endif
                            </div> 
                          </div>
   
                          <div class="col-12 col-md-4">
                              <div class="form-group">
                                <label>Country</label> 
                                <div class="select_box">
                                  <select required class="form-control" id="country" name="country">
                                    <option value="0">Choose Country</option>
                                    @if(isset($countries) && !empty($countries))
                                      @foreach($countries as $country)
                                        <option @if($userDetails->userdetails->country ==$country->id) selected @endif value="{{$country->id}}" >{{ucfirst($country->name)}}</option>
                                      @endforeach
                                    @endif
                                </select>
                                 @if ($errors->has('country'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('country') }}.</strong>
                              </span>
                              @endif
                                </div>
                              </div> 
                            </div>
                            <div class="col-12 col-md-4">
                              <div class="form-group">
                                <label>State</label> 
                                <div class="select_box">
                                  <select required class="form-control" id="state" name="state">
                                   <option value="0">Choose State</option>
                                    @if(isset($states) && !empty($states))
                                      @foreach($states as $state)
                                        <option @if($userDetails->userdetails->state ==$state->id) selected @endif value="{{$state->id}}" >{{ucfirst($state->name)}}</option>
                                      @endforeach
                                    @endif
                                  </select>
                                  @if ($errors->has('state'))
                                  <span class="invalid feedback"role="alert">
                                      <strong>{{ $errors->first('state') }}.</strong>
                                  </span>
                                  @endif
                                </div>
                              </div> 
                            </div>
                            <div class="col-12 col-md-4">
                              <div class="form-group">
                                <label>City</label> 
                                <div class="select_box">
                                 <select required class="form-control" id="city" name="city">
                                   <option value="0">Choose City</option>
                                    @if(isset($cities) && !empty($cities))
                                      @foreach($cities as $city)
                                        <option @if($userDetails->userdetails->city ==$city->id) selected @endif value="{{$city->id}}" >{{ucfirst($city->name)}}</option>
                                      @endforeach
                                    @endif
                                  </select>
                                  @if ($errors->has('city'))
                                  <span class="invalid feedback"role="alert">
                                      <strong>{{ $errors->first('city') }}.</strong>
                                  </span>
                                  @endif
                                </div>
                              </div> 
                            </div>
                          </div>
                            <div class="row">
                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Postal</label> 
                                  <input id="postal" type="text" class="form-control @error('postal') is-invalid @enderror" name="postal" value="@if (isset($userDetails->userdetails->zipcode) && !empty($userDetails->userdetails->zipcode)){{$userDetails->userdetails->zipcode}}@endif" required autocomplete="postal" autofocus placeholder="Required"/>
                                  @if ($errors->has('postal'))
                                  <span class="invalid feedback"role="alert">
                                      <strong>{{ $errors->first('postal') }}.</strong>
                                  </span>
                                  @endif
                                </div>
                              </div>
                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Contact Name</label> 
                                  <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="@if (isset($userDetails->userdetails->executive_name) && !empty($userDetails->userdetails->executive_name)){{$userDetails->userdetails->executive_name}}@endif" required autocomplete="contact_name" autofocus placeholder="Required"/>
                                  @if ($errors->has('contact_name'))
                                  <span class="invalid feedback"role="alert">
                                      <strong>{{ $errors->first('contact_name') }}.</strong>
                                  </span>
                                  @endif
                                </div>
                              </div>
                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Company Region/Established</label> 
                                  <div class="select_box">
                                    <select class="form-control" id="company_region" name="company_region">
                                       <option value="0">Choose State</option>
                                        @if(isset($states) && !empty($states))
                                          @foreach($states as $state)
                                            <option @if($userDetails->userdetails->region_formed_in ==$state->id) selected @endif value="{{$state->id}}" >{{ucfirst($state->name)}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                       @if ($errors->has('company_region'))
                                      <span class="invalid feedback"role="alert">
                                          <strong>{{ $errors->first('company_region') }}.</strong>
                                      </span>
                                      @endif
                                  </div>
                                </div> 
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Tax SSN/FEIN Number</label> 
                                  <input id="tax_number" type="text" class="form-control @error('tax_number') is-invalid @enderror" name="tax_number" value="@if (isset($userDetails->userdetails->tax_id_number) && !empty($userDetails->userdetails->tax_id_number)){{$userDetails->userdetails->tax_id_number}}@endif" required autocomplete="tax_number" autofocus placeholder="Required" minlength="9"/>
                                  @if ($errors->has('tax_number'))
                                  <span class="invalid feedback"role="alert">
                                      <strong>{{ $errors->first('tax_number') }}.</strong>
                                  </span>
                                  @endif
                                </div>
                              </div>
                            </div>
                        <button
                          type="submit"
                          class="btn btn-primary btn-md xs-block mt-4"
                        >
                        Update Profile
                        </button>
                      </form>
                    </div>
                    <div class="tab-pane container fade" id="change_password">
                      <form id="change_password_form" method="POST" name="change_password_form" action="{{ route('changeOwnerPassword') }}">
                        {{ csrf_field() }} 
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Old Password</label>
                              <input id="password" type="password" class="ptype form-control @error('password') is-invalid @enderror" value="{{old('password')}}" name="password"  required autocomplete="password" autofocus placeholder="Required"/>
                                @if ($errors->has('password'))
                                <span class="invalid feedback"role="alert">
                                    <strong>{{ $errors->first('password') }}.</strong>
                                </span>
                                @endif
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>New Password</label>
                              <input id="new_password" type="password" class="ptype form-control @error('new_password') is-invalid @enderror" name="new_password"  value="{{old('new_password')}}" required autocomplete="new_password" autofocus placeholder="Required"/>
                              @if ($errors->has('new_password'))
                                <span class="invalid feedback"role="alert">
                                    <strong>{{ $errors->first('new_password') }}.</strong>
                                </span>
                                @endif
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Confirm Password</label>
                              <input id="password_confirmation" type="password" class="ptype form-control @error('password_confirmation') is-invalid @enderror" value="{{old('password_confirmation')}}" name="password_confirmation"  required autocomplete="password_confirmation" autofocus placeholder="Required"/>
                              @if ($errors->has('password_confirmation'))
                                <span class="invalid feedback"role="alert">
                                    <strong>{{ $errors->first('password_confirmation') }}.</strong>
                                </span>
                                @endif
                            </div>
                          </div>
                        </div>
                        <button
                        type="submit"
                        class="btn btn-primary btn-md xs-block mt-4"
                      >
                      Update Profile
                      </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



@endsection
