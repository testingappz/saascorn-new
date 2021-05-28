@extends('admin.layouts.admin') 
@section('content')
 <div class="row">
   <div class="container">
    <div class="escrew_box">  
      <h5 class="card-header">New Disbursement</h5>
      <div class="card-body mt_10">
        <div class="add_Disbursement_fields">
          <form id="manage_payment_form" name="" method="POST" action="{{ route('managePayment') }}">
             {{ csrf_field() }}        
            <div class="row">
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Escrow</h5>
                  
                  <select required class="mdb-select md-form" name="offer_name" searchable="Search here..">
                    <option value="">Select Offering</option>
                    @foreach($list as $offer)
                    <option value="{{$offer['investment_detail']['id']}}">

                    {{$offer['investment_detail']['investment_title']}}</option>

                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Contact name</h5>
                  <input type="text" name="cname" placeholder="Enter contact name" required>
                </div>
              </div>
              <hr class="field_hr_line">
              <div class="col-md-12">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Amount to Disburse</h5>
                  <input type="text" name="amount" placeholder="Enter Amount" required>
                </div>
              </div>
              <hr class="field_hr_line">
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Beneficiary Account Name (must match name on bank account)</h5>
                  <input type="text" name="name" placeholder="Enter name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Email</h5>
                  <input type="email" name="email" placeholder="Enter email" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Phone</h5>
                  <input type="text" name="phone" placeholder="Enter phone number" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Country</h5>
                  <select id="country" class="mdb-select md-form" searchable="Search here.." required name="country">
                    <option value=""  selected>Choose country</option>
                    @foreach($countries as $country)
                    <option value="{{$country->id}}">

                    {{$country->name}}</option>
                    
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Street Address</h5>
                  <input type="text" name="st_address" placeholder="Street Address" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Apartment, suite or unit number.</h5>
                  <input type="text" name="apartment" placeholder="Apartment, suite or unit number." required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">City</h5>
                  <select id="city" class="mdb-select md-form" searchable="Search here.." required name="city">
                    <option value=""  selected>Select city name</option>
                    
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">State</h5>
                  <select id="state" class="mdb-select md-form" searchable="Search here.." required name="state">
                    <option value=""  selected>Select state name</option>
                    
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">ZIP Code</h5>
                  <input type="text" name="zip_code" placeholder="ZIP Code" required>
                </div>
              </div>
              <hr class="field_hr_line">
            
              <div class="col-md-6">
                <h5 class="escrew_fields_name">Send via</h5>
                <div class="fields_inner">
                  <select class="mdb-select md-form" searchable="Search here.." required name="payment_method">
                    <option value="ach"  selected>ACH</option>
                    <option value="wire">Wire</option>
                  </select>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">ACH Transfer Method</h5>
                  <select class="mdb-select md-form" searchable="Search here..">
                    <option value=""selected></option>
                    <option value="1">option1</option>
                    <option value="2">option2</option>
                    <option value="3">option3</option>
                    <option value="3">option4</option>
                    <option value="3">option5</option>
                  </select>
                </div>
              </div> -->
              <hr class="field_hr_line">
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Name on Account</h5>
                <input type="text" name="acount_name" placeholder="name on account" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Routing Number</h5>
                <input type="text" name="routing_number" placeholder="routing number" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Account Number</h5>
                <input type="text" name="acount_no" placeholder="account number" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Tax Id Number</h5>
                <input type="text" name="tax_id_number" placeholder="tax id number" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="fields_inner ">
                <h5 class="escrew_fields_name">Account Type</h5>
                <!-- Group of default radios - option 1 -->
                <div class="popup_checkbox">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="account_type" value="checking">
                    <label class="custom-control-label" for="defaultGroupExample1">Checking</label>
                  </div>

                  <!-- Group of default radios - option 2 -->
                  <div class="custom-control custom-radio ml_20">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="account_type" checked value="savings">
                    <label class="custom-control-label" for="defaultGroupExample2">Savings</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Check Type</h5>
                <!-- Group of default radios - option 1 -->
                <div class="popup_checkbox">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="check_type" value="business">
                    <label class="custom-control-label" for="defaultGroupExample3">Business</label>
                  </div>

                  <!-- Group of default radios - option 2 -->
                  <div class="custom-control custom-radio ml_20">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample4" name="check_type" checked value="personal">
                    <label class="custom-control-label" for="defaultGroupExample4">Personal</label>
                  </div>
                </div>
              </div>
            </div>
          
              <div class="col-md-6">
                <div class="fields_inner">
                  <h5 class="escrew_fields_name">Reference</h5>
                  <input type="text" name="reference" placeholder="Reference" required>
                </div>
              </div>
             <!--  <div class="col-md-6">
                <div class="fields_inner ach_button_field">
                  <button type="button" class="btn ach_button" data-toggle="modal" data-target="#myModal">Add New ACH Method</button>
                  <button type="button" class="btn ach_button d-none" data-toggle="modal" data-target="#myModal2">Second modal</button>
                </div>
              </div> -->
              <hr class="field_hr_line">
              <div class="col-md-12">
                <div class="escrew_buttons">

                  <!-- <button class="field_button btn-danger">Cancel</button>
                  <button class="field_button btn-success ml_10 btn-primary">Save</button> -->
                  <button id="cancelbtn" class="btn btn-sm md-block field_button btn-danger">Cancel</a>
                  <button id="savebtn" class="btn  btn-primary btn-sm md-block field_button btn-success ml_10">Save</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  </div>
  <div class="modal_popup">
      <!-- The Modal -->
      <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h6 class="modal-title">New Bank Transfer Method </h6>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Name on Account</h5>
                <input type="text" name="name" placeholder="Start typing offering name to search for escrow">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Routing Number</h5>
                <input type="text" name="name" placeholder="Enter name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Account Number</h5>
                <input type="text" name="name" placeholder="Enter name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="fields_inner ">
                <h5 class="escrew_fields_name">Account Type</h5>
                <!-- Group of default radios - option 1 -->
                <div class="popup_checkbox">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="groupOfDefaultRadios">
                    <label class="custom-control-label" for="defaultGroupExample1">Option 1</label>
                  </div>

                  <!-- Group of default radios - option 2 -->
                  <div class="custom-control custom-radio ml_20">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="groupOfDefaultRadios" checked>
                    <label class="custom-control-label" for="defaultGroupExample2">Option 2</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Check Type</h5>
                <!-- Group of default radios - option 1 -->
                <div class="popup_checkbox">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="groupOfDefaultRadios">
                    <label class="custom-control-label" for="defaultGroupExample3">Option 1</label>
                  </div>

                  <!-- Group of default radios - option 2 -->
                  <div class="custom-control custom-radio ml_20">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample4" name="groupOfDefaultRadios" checked>
                    <label class="custom-control-label" for="defaultGroupExample4">Option 2</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                
                  <button type="button" class="popup_buttons btn-success" data-dismiss="modal">Save</button>
            <button type="button" class="popup_buttons btn-danger" >Cancel</button>
        
            </div>
            <!-- Modal footer end -->
          </div>
        </div>
      </div>
  </div>
  <div class="modal_popup">
      <!-- The Modal -->
      <div class="modal fade" id="myModal2">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h6 class="modal-title">New Bank Transfer Method </h6>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Name on Account</h5>
                <input type="text" name="name" placeholder="Name on bank account">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Intermediary routing number</h5>
                <input type="text" name="name" placeholder="Enter routing number">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Intermediary account number</h5>
                <input type="text" name="name" placeholder="Enter account number">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Bank name</h5>
                <input type="text" name="name" placeholder="Enter bank name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Swift code</h5>
                <input type="text" name="name" placeholder="Enter swift code">
              </div>
            </div>
            <div class="col-md-12">
              <div class="fields_inner">
                <h5 class="escrew_fields_name">Account Number</h5>
                <input type="text" name="name" placeholder="Bank account number">
              </div>
            </div>
          </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                
                  <button type="button" class="popup_buttons btn-success" data-dismiss="modal">Save</button>
            <button type="button" class="popup_buttons btn-danger" >Cancel</button>
        
            </div>
            <!-- Modal footer end -->
          </div>
        </div>
      </div>
  </div>
    <!-- ============================================================== -->
    <!-- end basic table  -->
    <!-- ============================================================== -->
</div>
				
@endsection
				
				