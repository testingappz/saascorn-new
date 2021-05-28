@extends('layouts.main_layout')

@section('content')

 <section>
      <div class="container">
        <div class="row">
          <div class="col-12 col-xl-10 offset-xl-1">
            <div class="form_widget">
              <div class="login_block">
                <div class="inner_form">
                  <div class="form_heading">
                    <h3>Create Account</h3>
                    <p>Please fill all the details below</p>
                  </div>
                  @if(session()->get('errors'))
                  <div class="alert alert-danger">
                    {{ session()->get('errors')->first() }}
                  </div>
                  @endif
                  
                  <form method="POST" action="{{ route('register') }}">
                      @csrf
                    <div class="form-group">
                      <label>Account Type</label> 
                      <div class="account_type">
                        <div class="custom_radio">
                          <input
                            type="radio" required
                            class="form-check-input"
                            id="owner"
                            checked
                            name="user_type"
                            value="owner"  {{(old('user_type') == 'owner') ? 'checked' : ''}}
                          />
                          <label class="form-check-label" for="owner"
                            >I’m an issuer</label
                          >
                        </div>
                        <div class="custom_radio">
                          <input
                            type="radio"
                            class="form-check-input"
                            id="investor"
                            name="user_type"
                            value="investor" {{(old('user_type') == 'investor') ? 'checked' : ''}}
                          />

                          <label class="form-check-label" for="investor"
                            >I’m an Investor</label
                          >
                        </div>
                      </div>
                    </div> 
                    <div id="account_categoryowner" class="account_category">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>First Name</label> 
                             <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="Required"/>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Last Name</label> 
                             <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus placeholder="Required"/>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Email address</label> 
                            
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Required">
                          </div> 
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Password</label> 
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" autofocus placeholder="Required">
                          </div> 
                        </div>
                      </div>
                    </div>
                    <div id="account_categoryinvestor" class="account_category">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>First Name</label> 
                             <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="Required"/>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Last Name</label> 
                             <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus placeholder="Required"/>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Email address</label> 
                            
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Required">
                          </div> 
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-group">
                            <label>Password</label> 
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" autofocus placeholder="Required">
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
                                  <input type="radio" name="accredited_type" id="accredited_yes" value="1" {{(old('accredited_type') == '1') ? 'checked' : ''}}>
                                  <label for="accredited_yes">Yes</label>
                               </div>
                               <div class="radio_button">
                                <input type="radio" name="accredited_type" id="accredited_no" value="2" {{(old('accredited_type') == '2') ? 'checked' : ''}}>
                                <label for="accredited_no">No</label>
                              </div>
                              <div class="radio_button">
                                <input type="radio" name="accredited_type" id="accredited_dont" value="3" {{(old('accredited_type') == '3') ? 'checked' : ''}}>
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

                      <div class="row type_section" id="type_yes" style="display:none">
                        <div class="col-12">
                          <div class="qualify_accredited">
                            <h4>Please specify how you qualify as an accredited</h4>
                            <div class="qaulify_unit">
                              <div class="custom_checkbox">
                                <input
                                  type="radio" 
                                  class="form-check-input"
                                  id="qualify1" name="qualify" value="1" {{(old('qualify') == '1') ? 'checked' : ''}}
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
                                  id="qualify2" name="qualify" value="2"  {{(old('qualify') == '2') ? 'checked' : ''}}
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
                                  id="qualify3" name="qualify" value="3" {{(old('qualify') == '3') ? 'checked' : ''}}
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
                                  id="qualify4" name="qualify" value="4" {{(old('qualify') == '4') ? 'checked' : ''}}
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
                                  id="qualify5" name="qualify" value="5" {{(old('qualify') == '5') ? 'checked' : ''}}
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
                                  id="qualify6" name="qualify" value="6" {{(old('qualify') == '6') ? 'checked' : ''}}
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
                                  id="qualify7" name="qualify" value="7" {{(old('qualify') == '7') ? 'checked' : ''}}
                                />
                                <label class="form-check-label" for="qualify7"
                                  >We are a trust with assets in excess of $5 million, not specifically formed to acquire the securities offered, whose purchases a sophisticated investor makes.</label
                                >
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> 
                      <div class="row type_section" id="type_no" style="display:none">
                        <div class="col-12">
                          <div id="" class="">
                            <div class="row">
                              <div class="col-12 col-md-6">
                                <div class="form-group">
                                  <label>Annual Income</label>
                                  <input type="text" name="annual_income" class="form-control @if($errors->has('annual_income')) is-invalid @endif" placeholder="Required" value="{{ old('annual_income') }}" >
                                  <div>
                                    
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="form-group">
                                  <label>Networth</label>
                                  <input type="text" name="networth" class="form-control @if($errors->has('networth')) is-invalid @endif" placeholder="Required" value="{{ old('networth') }}">
                                  <div></div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12 col-md-6">
                                <div class="form-group">
                                  <label>How much did you invest in all Title III offerings within the last 12 months</label>
                                  <input type="text" name="last_offering" class="form-control @if($errors->has('last_offering')) is-invalid @endif" placeholder="Required" value="{{ old('last_offering') }}">
                                  <div></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row type_section" id="type_dont" style="display:none">
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

                    </div>
                   
                    <div class="form-remember">
                      <div
                        class="d-flex justify-content-between align-items-center"
                      >
                        <div class="custom_checkbox">
                          
                          <input  type="checkbox"  id="exampleCheck1" class="form-control @if($errors->has('terms')) is-invalid @endif" name="terms" value="true" {{ !old('terms') ?: 'checked' }}>

                          <label class="form-check-label" for="exampleCheck1"
                            >I agree to <a class="anchor" href="javascript:;">Terms of Use</a> & <a class="anchor" href="javascript:;">Privacy</a></label
                          >
                        </div>
                        
                      </div>
                    </div>
                    <div class="flexy-centered-sm">
                      <button
                        type="submit"
                        class="btn btn-primary btn-md xs-block"
                      >
                      Sign Up
                      </button>
                      <div class="is_account">
                        Already have an account?
                        <a href="{{ route('login') }}">Login </a>
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

 <script
      src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
      integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
      crossorigin="anonymous"
    ></script>
 <script>
      $(document).ready(function() {
       
        showAccount();
        showType();

        $("input[name$='user_type']").change(function() {
          showAccount();
          
        });
        $("input[name$='accredited_type']").change(function() {
          
          showType();
        });


        function showAccount() {

          let val = $('input[name="user_type"]:checked').val();
         
          $("div.account_category").hide();
          $("#account_category" + val).show();
          if(val=="owner")
          {
            $("#account_categoryinvestor").find("*").prop("disabled",true);
            $("#account_categoryowner").find("*").prop("disabled",false);
          }
          else
          {
            $("#account_categoryowner").find("*").prop("disabled",true);
            $("#account_categoryinvestor").find("*").prop("disabled",false);
          }
          //$("div.account_category").find("*").prop("disabled", true);
          //$("#account_category" + val).find("*").prop("disabled",false);

          
        }

        function showType() {

          var value = $( 'input[name=accredited_type]:checked' ).val();
          $('.type_section').hide();
          if(value==1)
          {
            $('#type_yes').show();
          }
          else if(value==2)
          {
            $('#type_no').show();
          }
          else if(value==3)
          {
            $('#type_dont').show();
          }
        }

       
      });

    </script>

@endsection


