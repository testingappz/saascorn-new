@extends('layouts.main_layout')

@section('content')

<section class="padding-70">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="form_widget">
              <div class="login_block">
                 
                <div class="inner_form">
                  <div class="form_heading">
                    <h3>Investor Profile</h3>
                    <p>Please fill all the details below</p>
                  </div>
                  
                 
                    <div class="form-group">
                      <label>Profile Picture</label> 

                      <div class="profile_pic_update">
                          <div class="profile_pic">
                              <img id="profile_pic_data" class="responsive" src="@if (isset($userDetails->profile_image) && !empty($userDetails->profile_image)){{env('AWS_BUCKET_PATH')}}images/{{$userDetails->profile_image}}@else {{ URL::asset('images/image_placeholder.svg') }} @endif" alt="image"/>
                          </div>
                          <form method="POST" id="upload_pic" enctype="multipart/form-data" action="{{ route('uploadpicinvestor') }}">

                            {{ csrf_field() }}
                            
                            <div class="input_file">
                                <label for="file_upload" class="btn btn-outline-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Upload new picture</label>
                                <input id="file_upload" type="file" name="profile_picture"/>
                            </div>
                          </form>
                          <button id="remove_picture" class="btn btn-danger btn-font-13 btn-less-rounded" style="display:none">Remove</button>
                      </div>
                     
                    </div> 

                  <form method="POST" id="basic_profile_investor" enctype="multipart/form-data" action="{{ route('basicdataInvestor') }}">
                          {{ csrf_field() }}                
                    <div class="row">
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                          <label>INVESTOR TYPE</label> 
                            <div class="select_box">
                              <select class="form-control" id="investor_type_update" name="investor_type" required>
                                <option value="">Select</option>
                                <option value="2" @if (old('investor_type') == "2") {{ 'selected' }} @endif>Company</option>
                                <option value="1" @if (old('investor_type') == "1") {{ 'selected' }} @endif>Person</option>
                              </select>
                              <div></div>
                            </div>
                        </div>
                      </div>
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
                            <select class="form-control" id="country" name="country" required>
                                <option value="">Choose Country</option>
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
                            <select class="form-control" id="state" name="state" required>
                               <option value="">Choose State</option>
                                
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
                            <select class="form-control" id="city" name="city" required>
                                <option value="">Choose City</option>
                               
                              </select>
                              @if ($errors->has('city'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('city') }}.</strong>
                              </span>
                              @endif
                          </div>
                        </div> 
                      </div>
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
                          <label>Tax SSN/FEIN Number</label> 
                          <input id="tax_number" type="text" class="form-control @error('tax_number') is-invalid @enderror" name="tax_number" value="{{ old('tax_number') }}" required autocomplete="tax_number" autofocus placeholder="Required" minlength="9" maxlength="9"/>
                          @if ($errors->has('tax_number'))
                          <span class="invalid feedback"role="alert">
                              <strong>{{ $errors->first('tax_number') }}.</strong>
                          </span>
                          @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-4" id="section_contact_name" style="display:none">
                          <div class="form-group">
                            <label>Contact Name</label> 
                            <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name') }}" autocomplete="contact_name" autofocus placeholder="Required"/>
                            @if ($errors->has('contact_name'))
                            <span class="invalid feedback"role="alert">
                                <strong>{{ $errors->first('contact_name') }}.</strong>
                            </span>
                            @endif
                          </div>
                      </div>
                      <div class="col-12 col-md-4" id="section_region_name" style="display:none">
                        <div class="form-group">
                          <label>Company Region/Established</label> 
                          <div class="select_box">
                            <select class="form-control" id="company_region" name="company_region">
                               <option value="">Choose State</option>
                                
                              </select>
                               @if ($errors->has('company_region'))
                              <span class="invalid feedback"role="alert">
                                  <strong>{{ $errors->first('company_region') }}.</strong>
                              </span>
                              @endif
                          </div>
                        </div> 
                      </div>
                      <div class="col-12 col-md-4" id="section_dob" style="display:none">
                        <div class="form-group">
                          <label>Date of birth</label> 
                          <input id="date_of_birth" type="text" class="form-control datetimepickerdob @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}"  autocomplete="date_of_birth" autofocus placeholder="Required" minlength="9"/>
                          @if ($errors->has('date_of_birth'))
                          <span class="invalid feedback"role="alert">
                              <strong>{{ $errors->first('date_of_birth') }}.</strong>
                          </span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="line">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="accredited">
                             <h4>I’m an Accredited Investor</h4>
                             <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                             <div class="radio_button_group">
                               <div class="radio_button">
                                  <input type="radio" name="accredited_type" id="accredited_yes" value="1" @if($userDetails->userdetails->accredited_investor_type==1) checked @endif>
                                  <label for="accredited_yes">Yes</label>
                               </div>
                               <div class="radio_button">
                                <input type="radio" name="accredited_type" id="accredited_no" value="2" @if($userDetails->userdetails->accredited_investor_type==2) checked @endif>
                                <label for="accredited_no">No</label>
                              </div>
                              <div class="radio_button">
                                <input type="radio" name="accredited_type" id="accredited_dont" value="3" @if($userDetails->userdetails->accredited_investor_type==3) checked @endif>
                                <label for="accredited_dont">I Don't Know</label>
                              </div>
                             </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <div class="line">
                        </div>
                      </div>
                    </div>
                    <div class="row type_section" id="type_yes" style="@if($userDetails->userdetails->accredited_investor_type==1) @else display:none @endif">
                        <div class="col-12">
                          <div class="qualify_accredited">
                            <h4>Please specify how you qualify as an accredited</h4>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio" 
                                  class="form-check-input"
                                  id="qualify1" name="qualify" value="1" @if($userDetails->userdetails->accredited_type==1) checked @endif
                                />
                                <label class="form-check-label" for="qualify1"
                                  >We are a bank, insurance company, pension fund, or other registered investment company with assets exceeding $5 million.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify2" name="qualify" value="2"  @if($userDetails->userdetails->accredited_type==2) checked @endif
                                />
                                <label class="form-check-label" for="qualify2"
                                  >We are a business, trust or other non-individual entity in which all the equity owners or grantors/settlors are accredited investors.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify3" name="qualify" value="3" @if($userDetails->userdetails->accredited_type==3) checked @endif
                                />
                                <label class="form-check-label" for="qualify3"
                                  >We are a corporation, partnership, or charitable organization with at least $5 million in assets.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify4" name="qualify" value="4" @if($userDetails->userdetails->accredited_type==4) checked @endif
                                />
                                <label class="form-check-label" for="qualify4"
                                  >We are an employee benefit plan, within the meaning of the Employee Retirement Income Security Act, if a bank, insurance company, or registered investment adviser makes the investment decisions, or if the plan has total assets in excess of $5 million.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify5" name="qualify" value="5" @if($userDetails->userdetails->accredited_type==5) checked @endif
                                />
                                <label class="form-check-label" for="qualify5"
                                  >I am an individual with income of over $200,000 in each of the last two years, or joint income with my spouse exceeding $300,000 in those years, and I reasonably expect at least the same this year.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify6" name="qualify" value="6" @if($userDetails->userdetails->accredited_type==6) checked @endif
                                />
                                <label class="form-check-label" for="qualify6"
                                  >I have an individual net worth, or joint net worth with my spouse, that exceeds $1 million including any IRA's, 401K's and other retirement accounts, but excluding the net value of my primary residence.</label
                                >
                              </div>
                            </div>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio"
                                  class="form-check-input"
                                  id="qualify7" name="qualify" value="7" @if($userDetails->userdetails->accredited_type==7) checked @endif
                                />
                                <label class="form-check-label" for="qualify7"
                                  >We are a trust with assets in excess of $5 million, not specifically formed to acquire the securities offered, whose purchases a sophisticated investor makes.</label
                                >
                              </div>
                            </div>
                          </div>
                        </div>
                    </div> 
                    <div class="row type_section" id="type_no" style="@if($userDetails->userdetails->accredited_investor_type==2) @else display:none @endif">
                      <div class="col-12">
                        <div id="" class="">
                          <div class="row">
                            <div class="col-12 col-md-6">
                              <div class="form-group">
                                <label>Annual Income</label>
                                <input type="text" name="annual_income" class="form-control @if($errors->has('annual_income')) is-invalid @endif" placeholder="Required" value="@if (isset($userDetails->userdetails->annual_income) && !empty($userDetails->userdetails->annual_income)) {{$userDetails->userdetails->annual_income}}@endif" >
                                <div>
                                  
                                </div>
                              </div>
                            </div>
                            <div class="col-12 col-md-6">
                              <div class="form-group">
                                <label>Networth</label>
                                <input type="text" name="networth" class="form-control @if($errors->has('networth')) is-invalid @endif" placeholder="Required" value="@if (isset($userDetails->userdetails->networth) && !empty($userDetails->userdetails->networth)) {{$userDetails->userdetails->networth}}@endif">
                                <div></div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12 col-md-6">
                              <div class="form-group">
                                <label>How much did you invest in all Title III offerings within the last 12 months</label>
                                <input type="text" name="last_offering" class="form-control @if($errors->has('last_offering')) is-invalid @endif" placeholder="Required" value="@if (isset($userDetails->userdetails->last_investment) && !empty($userDetails->userdetails->last_investment)) {{$userDetails->userdetails->last_investment}}@endif">
                                <div></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row type_section" id="type_dont" style="@if($userDetails->userdetails->accredited_investor_type==3) @else display:none @endif">
                      <div class="col-12">
                        <div class="qualify_accredited">
                          <h4>Please specify how you qualify as an accredited</h4>
                          <div class="qaulify_unit">
                            <div class="custom_checkbox">
                              <input
                                type="checkbox" 
                                class="form-check-input"
                                id="doqualify1" name="doqualify" value="1"
                              />
                              <label class="form-check-label" for="doqualify1"
                                >Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</label
                              >
                            </div>
                          </div>
                          <div class="qaulify_unit">
                            <div class="custom_checkbox">
                              <input
                                type="checkbox" 
                                class="form-check-input"
                                id="doqualify2" name="doqualify" value="2"
                              />
                              <label class="form-check-label" for="doqualify2"
                                >Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</label
                              >
                            </div>
                          </div>
                          
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


