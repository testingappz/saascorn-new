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
        <div class="row">
          <div class="col-12">
            <div class="form_widget">
              <div class="login_block">
                 
                <div class="inner_form">
                   <div class="alert" id="message" style="display: none"></div>
                  <div class="form_heading">
                    <h3>Create Owner/Company Profile</h3>
                    <p>Please fill all the details below</p>
                  </div>
                    <div class="form-group">
                      <label>Account Type</label> 
                    <div class="profile_pic_update">
                        <div class="profile_pic">
                         
                            <img id="profile_pic_data" class="responsive" src="@if(isset($userDetails->profile_image) && !empty($userDetails->profile_image)){{env('AWS_BUCKET_PATH')}}images/{{$userDetails->profile_image}}@else {{ URL::asset('images/image_placeholder.svg') }} @endif" alt="image"/>
                        </div>
                        <form method="POST" id="upload_picture" enctype="multipart/form-data" action="{{ route('uploadpic') }}">
                          {{ csrf_field() }}
                          <div class="input_file">
                              <label for="file_upload" class="btn btn-outline-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Upload new picture</label>
                              <input id="file_upload" type="file" name="profile_picture" accept="image/png,image/jpg,image/jpeg,image/gif"/>
                          </div>
                        </form>
                        <button id="remove_picture" class="btn btn-danger btn-font-13 btn-less-rounded" style="display:none">Remove</button>
                    </div>
                     
                    </div>  
                    <form method="POST" id="basic_profile" enctype="multipart/form-data" action="{{ route('basicdata') }}">
                          {{ csrf_field() }}                 
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label>Company Name</label> 
                            <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus placeholder="Required"/>
                             @if ($errors->has('company_name'))
                            <span class="invalid feedback"role="alert">
                                <strong>{{ $errors->first('company_name') }}.</strong>
                            </span>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label>Phone</label> 
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus placeholder="Required"/>
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
                            <input id="address_line_1" type="text" class="form-control @error('address_line_1') is-invalid @enderror" name="address_line_1" value="{{ old('address_line_1') }}" required autocomplete="address_line_1" autofocus placeholder="Required"/>
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
                            <input id="address_line_2" type="text" class="form-control @error('address_line_2') is-invalid @enderror" name="address_line_2" value="{{ old('address_line_2') }}" required autocomplete="address_line_2" autofocus placeholder="Required"/>
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
                                <select class="form-control" id="country" name="country">
                                    <option value="0">Choose Country</option>
                                    @if(isset($countries) && !empty($countries))
                                      @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{ucfirst($country->name)}}</option>
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
                              <select class="form-control" id="state" name="state">
                                 <option value="0">Choose State</option>
                                  
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
                              <select class="form-control" id="city" name="city">
                                  <option value="0">Choose City</option>
                                 
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
                            <input id="postal" type="text" class="form-control @error('postal') is-invalid @enderror" name="postal" value="{{ old('postal') }}" required autocomplete="postal" autofocus placeholder="Required"/>
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
                            <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name') }}" required autocomplete="contact_name" autofocus placeholder="Required"/>
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
                            <input id="tax_number" type="text" class="form-control @error('tax_number') is-invalid @enderror" name="tax_number" value="{{ old('tax_number') }}" required autocomplete="tax_number" autofocus placeholder="Required" minlength="9"/>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



@endsection
