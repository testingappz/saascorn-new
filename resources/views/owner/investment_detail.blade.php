@extends('layouts.main_layout')

@section('content')
<style>
  .invalid
  {
    color:#dc3545;
  }
</style>

<section class="bg_cover" style="background-image: url({{ URL::asset('images/cover.png') }});"></section>

    
  <section class="investment_details">
    <div class="container">
      <div class="row mb-50px">
          <div class="col-12">
            <div class="investment_details_desc form_widget">
                <div class="invest_image">

                  <input type="hidden" id="project_id" name="project_id" value="{{ request()->id }}">
                  <img id="project_image" class="responsive"src="@if(isset($data->investment_image) && !empty($data->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$data->investment_image }}@else {{URL::asset('images/image_placeholder.svg')}} @endif" alt="image"/>
                </div>
               
                <div class="invest_details">
                  <h3>@if(isset($data->ownerData->userdetails->company_name)){{$data->ownerData->userdetails->company_name}}@endif</h3>
                  <h4>@if(isset($data->investment_title)){{$data->investment_title}}@endif</h4>
                  <div class="invest_counter">
                    <h6><span>Funding Goal  : </span> @if(isset($data->budget)){{$data->budget}}@endif</h6>
                    <h6><span>Offering End Date : </span> @if(isset($data->offering_end_date)){{ \Carbon\Carbon::parse($data->offering_end_date)->format('d M, Y')}}@endif</h6>
                  </div>
                  
                  <ul class="social_share">
                    <li>
                      <a href="javascript:;">
                        
                        <img src="{{ URL::asset('images/social_fb.svg') }}" alt="icon"/>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                        <img src="{{ URL::asset('images/social_tw.svg') }}" alt="icon"/>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                        <img src="{{ URL::asset('images/social_in.svg') }}" alt="icon"/>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                        <img src="{{ URL::asset('images/social_insta.svg') }}" alt="icon"/>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="update_investment">
                  <a class="btn btn-primary btn-sm" href="javascript:;" data-toggle="modal" data-target="#edit_project">Update</a>
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
                    <a class="nav-link @if($data->agreement_status==1 && $data->tech_agreement_status==1) active @endif" data-toggle="pill" href="#description">Description</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#videos">Videos</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" data-toggle="pill" href="#documents">Documents</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#investment_details">Investment Details</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link @if($data->agreement_status==0 || $data->tech_agreement_status==0) active @endif" data-toggle="pill" href="#agreement_details">Agreement Details</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#track_investment"> Investments</a>
                  </li>
                </ul>
               </div>

                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane @if($data->agreement_status==1 && $data->tech_agreement_status==1) active @else fade @endif" id="description">   
                      <div class="description_text" id="view_description">
                        @if(isset($data->investment_description)){{$data->investment_description}}@endif
                      </div>
                      <button type="button" class="btn btn-primary btn-md xs-block mt-5" data-toggle="modal" data-target="#edit_description">Edit</button>
                  </div>
                  <div class="tab-pane fade" id="videos">
                      <ul class="video_listing">
                      @if(isset($data->investmentDocs) && !empty($data->investmentDocs))
                        @foreach($data->investmentDocs as $doc)
                          @if($doc->type==2)
                           <li id="parent_{{$doc->id}}">
                              <video width="320" height="240" controls>

                                <source src="{{env('AWS_BUCKET_PATH')}}videos/{{$doc->doc_name }}" type="video/mp4">
                                Your browser does not support the video tag.
                              </video> 
                        
                              <img data-id="{{$doc->id}}" data-type="{{$doc->type}}" class="delete_image" src="{{ URL::asset('images/delete_icon.svg') }}" alt="icon">

                          </li>
                          @endif
                        @endforeach
                      @endif
                     
                     </ul>
                      <button type="submit" class="btn btn-primary btn-md xs-block mt-4" data-toggle="modal" data-target="#add_new_video">Add New Video</button>
                  </div>
                  <div class="tab-pane fade" id="documents">
                    <ul class="doc_listing">
                      @if(isset($data->investmentDocs) && !empty($data->investmentDocs))
                        @foreach($data->investmentDocs as $doc)
                          @if($doc->type==1)
                            <li id="parent_{{$doc->id}}">

                              <a href="{{env('AWS_BUCKET_PATH')}}docs/{{$doc->doc_name }}" target="_blank">
                                @if (pathinfo($doc->doc_name, PATHINFO_EXTENSION) == 'docx')
                                <img  class="responsive" src="{{ URL::asset('images/file_types/013-docx.svg') }}" alt="file"/>
                                @elseif (pathinfo($doc->doc_name, PATHINFO_EXTENSION) == 'pdf')
                                <img  class="responsive" src="{{ URL::asset('images/file_types/032-pdf.svg') }}" alt="file"/>
                                @else 
                                <img  class="responsive" src="{{ URL::asset('images/file_types/025-jpg.svg') }}" alt="file"/>
                                @endif
                              </a>
                              
                              <h4>{{ $doc->doc_name }}</h4>
                              <img data-id="{{$doc->id}}" data-type="{{$doc->type}}" class="delete_image" src="{{ URL::asset('images/delete_icon.svg') }}" alt="icon">
                            </li>
                          @endif
                        @endforeach
                      @endif
                        
                    </ul>
                    <button
                      type="button"
                      class="btn btn-primary btn-md xs-block mt-4"  data-toggle="modal" data-target="#add_new_doc"
                    >
                    Add New Document
                    </button>
                  </div>
                  <div class="tab-pane fade" id="investment_details">
                     <form>
                      <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                              <label>Minimum Investment</label> 
                              <input name="minimum_investment" type="text" class="form-control" placeholder="Required" value="@if(isset($data->min_investment)){{$data->min_investment}}@endif">
                            </div>
                          </div>
                          <div class="col-12 col-md-4">
                            <div class="form-group iconed_field">
                              <label>Offering End Date</label> 
                              <input name="offering_end_date" type="text" class="form-control datetimepicker" placeholder="Required" value="@if(isset($data->offering_end_date)){{$data->offering_end_date}}@endif">

                              
                            </div>
                          </div>
                      </div>
                      <button type="button" class="btn btn-primary btn-md xs-block mt-4" data-toggle="modal" data-target="#edit_investment_details">
                        Update
                        </button>
                     </form>
                  </div>
                  <div class="tab-pane @if($data->agreement_status==0 || $data->tech_agreement_status==0) active @else fade @endif" id="agreement_details">
                    <div class="invest_details">
                      <div class="invest_counter">
                        <h6 class="mb-4">
                          <span>Escrow Agreement Status  : 
                          </span>@if($data->agreement_status==0) Not Signed Yet @else Signed @endif 
                          <br>
                          @if(isset($agreementData->body_html))
                             <button id="send_agreement_again" type="button" class="btn btn-primary btn-sm xs-block mt-3" data-toggle="modal" data-target="#sign_agreement1">
                              Click To Sign The Agreement
                            </button> 
                          @endif
                         
                        </h6>
                        <h6 class="mb-4">
                          <span>Tech Services Agreement Status  : 
                          </span>@if($data->tech_agreement_status==0) Not Signed Yet @else Signed @endif 
                          <br>
                          @if(isset($agreementTechData->resources[0]->body_html))
                            <button id="send_tech_agreement_again" type="button" class="btn btn-primary btn-sm xs-block mt-3" data-toggle="modal" data-target="#sign_agreement2">
                              Click To Sign The Agreement
                            </button>
                            @endif
                        </h6>
                        <h6 class="mb-4">
                          <span>Project Approval Status  : 
                          </span> @if($data->accept_investment==0)Pending @elseif($data->accept_investment==1) Approved @elseif($data->accept_investment==2) Denied @endif
                        </h6>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="track_investment">
                    <table id="track_investment_table" class="table table-borderless">
                      <thead>
                        <tr>
                          <th>Investor</th>
                          <th>Email</th>
                          <th>Date Invested</th>
                          <th>Invested</th>
                          <th>Received</th>
                          <th>Ref</th>
                          <th>Via</th>
                          <th>Status</th>
                          <th>Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        <input type="hidden" id="current_inv_id" value="{{request()->route('id')}}">
                        @if(isset($allinvestments) && !empty($allinvestments))
                          @foreach($allinvestments as $allinvestment)
                          <tr>
                            <td>
                              {{$allinvestment['name']}}
                            </td>
                            <td>
                              {{$allinvestment['email']}}
                            </td>
                            <td>
                              {{$allinvestment['invested']}}
                            </td>
                            <td>
                              ${{$allinvestment['amount']}}
                            </td>
                            <td>
                              ${{$allinvestment['received']}}
                            </td>
                            <td>
                              {{$allinvestment['reference']}}
                            </td>
                            <td>
                              {{$allinvestment['paymentMethod']}}
                            </td>
                            <td>
                              {{$allinvestment['status']}}
                            </td>
                            <td>
                              <a href="{{route('investmentProjectDetails', ['id'=>$allinvestment['id'],'pid' => request()->route('id')])}}" class="link"
                              >details</a>
                            </td>
                          </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                    <input type="hidden" id="total" value="{{$count}}">
                    @if(isset($count) && ($count>10))
                    <div class="col-12" id="load_more">
                      <div class="view_all">
                        <form name="loadmore_form" method="POST" action="{{route('loadmoreinv')}}">
                          {{ csrf_field() }} 
                          <a id="loadmorebutton_project" onclick="loadMoreProjectInvestments();"
                          class="btn btn-primary btn-md"
                          href="javascript:;"
                          >Load more
                        </a>

                        </form>
                        
                      </div>
                    </div>
                    @endif
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- sign first document -->
  <div class="modal fade" id="sign_agreement1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-90" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Sign Agreement</h5>
           
        </div>
        
        <form id="sign_agreement1_form" name="sign_agreement1_form" method="POST" action="{{ route('signAgreementLink') }}">
          {{ csrf_field() }} 
            <input type="hidden" name="projectId" value="{{$data->id}}">
            <input type="hidden" name="signlink" value="@if(isset($agreementData->electronic_signatures[0]->anchor_id) && $agreementData->electronic_signatures[0]->anchor_id=='issuer_signature'){{$agreementData->electronic_signatures[0]->url}} @endif">
          <div class="modal-body">
              @if(isset($agreementData->body_html))
                <div class="form-group scroller">
                {!! $agreementData->body_html !!}
                </div>
                <div class="form-group">
                  <label>Signature</label>
                  <input id="signature_agreement1" class="form-control" placeholder="signature" value="" name="signature" required>
                  <input type="hidden" value="{{$data->id}}" name="ProjectId">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input id="email_agreement1" class="form-control" placeholder="required" value="@if(isset($data->ownerData->email)){{$data->ownerData->email}}@endif" name="email" readonly required>
                </div>
                <div class="form-group">
                  <label>Title</label>
                  <input id="title_agreement1" class="form-control" placeholder="title" value="" name="title" required>
                </div>
                <div class="form-group">
                  <label>Company</label>
                  <input id="company_agreement1" class="form-control" placeholder="Company" value="@if(isset($data->ownerData->userdetails->company_name)){{$data->ownerData->userdetails->company_name}}@endif" name="company" required>
                </div>
              @endif
          </div>
          <div class="modal-footer">
            
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- sign second document -->
   <div class="modal fade" id="sign_agreement2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-90" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Sign Agreement</h5>
           
        </div>

        <form id="sign_agreement2_form" name="sign_agreement2_form" method="POST" action="{{ route('signTechAgreementLink') }}">
          {{ csrf_field() }} 
            <input type="hidden" name="projectId" value="{{$data->id}}">
            <input type="hidden" name="signlink" value="@if(isset($agreementTechData->resources[0]->electronic_signatures[0]->anchor_id) && $agreementTechData->resources[0]->electronic_signatures[0]->anchor_id=='issuer_signature'){{$agreementTechData->resources[0]->electronic_signatures[0]->url}} @endif">
          <div class="modal-body">
            @if(isset($agreementTechData->resources[0]->body_html))
            <div class="form-group scroller">
              {!! $agreementTechData->resources[0]->body_html !!}
            </div>
            
            <div class="form-group">
                  <label>Signature</label>
                  <input id="signature_agreement1" class="form-control" placeholder="signature" value="" name="signature" required>
                  <input type="hidden" value="{{$data->id}}" name="ProjectId">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input id="email_agreement1" class="form-control" placeholder="required" value="@if(isset($data->ownerData->email)){{$data->ownerData->email}}@endif" name="email" readonly required>
                </div>
                <div class="form-group">
                  <label>Title</label>
                  <input id="title_agreement1" class="form-control" placeholder="title" value="" name="title" required>
                </div>
                <div class="form-group">
                  <label>Company</label>
                  <input id="company_agreement1" class="form-control" placeholder="company" value="@if(isset($data->ownerData->userdetails->company_name)){{$data->ownerData->userdetails->company_name}}@endif" name="company" required>
                </div>
                @endif
              
          </div>
          <div class="modal-footer">
            
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- edit description Modal -->
  <div class="modal fade" id="edit_description" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="alert" id="messagedesc" style=" display:none"></div>
        <div class="modal-header">
          <h5 class="modal-title">Description</h5>
           
        </div>
        <form id="update_desc" name="update_desc" method="POST" action="{{ route('updateDesc') }}">
          {{ csrf_field() }} 
          <div class="modal-body">
              <div class="form-group">
                <label>Enter Text</label>
                <textarea  class="form-control" id="project_desc" name="description">@if(isset($data->investment_description)){{$data->investment_description}}@endif</textarea>
              </div>
            
          </div>
          <div class="modal-footer">
            
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- edit project Modal -->
  <div class="modal fade" id="edit_project" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="alert messagedesc"  style=" display:none"></div>
        <div class="modal-header">
          <h5 class="modal-title">Update</h5>
           
        </div>
        <form id="update_proj_det" name="update_proj_det" method="POST" action="{{ route('updateProjectData') }}" enctype="multipart/form-data">

          {{ csrf_field() }} 

          <div class="modal-body">
              <div class="form-group">
                <label>PROJECT TITLE</label>
                <input id="title" class="form-control" placeholder="required" value="@if(isset($data->investment_title)){{$data->investment_title}}@endif" name="title"/>
              </div>
              <div class="form-group iconed_field">
                <label>PROJECT BUDGET</label>
                <input id="budget" class="form-control" placeholder="required" value="@if(isset($data->budget)){{$data->budget}}@endif" name="budget"/>
              </div>
              <div class="form-group">
                <label>Project Image</label> 
                <div class="profile_pic_update">
                  <div class="profile_pic">
                    <img id="file_upload_project_preview" class="responsive" src="@if(isset($data->investment_image) && !empty($data->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$data->investment_image}}@else {{URL::asset('images/image_placeholder.svg')}} @endif" alt="image">
                  </div>
                  <div class="input_file">
                    <label for="file_upload_project" class="btn btn-outline-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Upload Picture</label>
                    <input id="file_upload_project" type="file" class="form-control " name="project_image" value="" autocomplete="project_image" autofocus="" placeholder="Required" accept="image/png,image/jpg,image/jpeg,image/gif">
                  </div>
                </div>
              </div>
         
            </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- add new video Modal -->
  <div class="modal fade" id="add_new_video" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="alert messagedesc" style=" display:none"></div>
        <div class="modal-header">
          <h5 class="modal-title">Add New Video</h5>
        </div>
        <form id="add_new_video_form" name="add_new_video_form" method="POST" enctype="multipart/form-data" action="{{ route('updateDocOrVideo') }}">
         {{ csrf_field() }}  
          <div class="modal-body">
            <div class="row">
              
              <div class="col-12">
                <div class="form-group">
                  <label>Videos</label> 
                  <div class="input_file">
                    <label for="video_upload" class="btn btn-fade-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Add Video(s)</label>
                    <input id="video_upload" multiple name="videos[]" type="file"  accept="video/*"  required/>
                    <input type="hidden" name="doctype" value="2">
                    <input type="hidden" name="pId" value="{{ request()->id }}">
                  </div>
                  <input type="hidden" id="vidlisthide" name="vidlisthide[]">
                  <div class="file_listing" id="vidlist">
                  <!-- <div class="list">
                  <h5>Promo_doc.mp4 (2 mb)</h5>
                  <img src="images/delete.svg" alt="delete">
                  </div> -->
                </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="modal-footer">
            
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!--add new doc Modal -->
  <div class="modal fade" id="add_new_doc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="alert messagedesc" style=" display:none"></div>
        <div class="modal-header">
          <h5 class="modal-title">Add New Document</h5>
           
        </div>
        <form id="add_new_doc_form" name="add_new_doc_form" method="POST" enctype="multipart/form-data" action="{{ route('updateDocOrVideo') }}">
           {{ csrf_field() }} 
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Documents</label> 
                  <div class="input_file">
                    <label for="doc_upload" class="btn btn-fade-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Add More Docs</label>
                    <input id="doc_upload" multiple name="documents[]" type="file"  accept="image/*,.doc, .docx,.pdf" required />
                    <input type="hidden" name="doctype" value="1">
                    <input type="hidden" name="pId" value="{{ request()->id }}">
                  </div>
                  <input type="hidden" id="doclisthide" name="doclist[]">
                  <div class="file_listing" id="doclist">
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="modal-footer">
            
            <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- add investment details Modal -->
  <div class="modal fade" id="edit_investment_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="alert messagedesc" style=" display:none"></div>
        <div class="modal-header">
          <h5 class="modal-title">Investment Details</h5>
        </div>
          <form id="update_invest_info" name="update_invest_info" method="POST" enctype="multipart/form-data" action="{{ route('updateInvestInfo') }}">
             {{ csrf_field() }} 
            <div class="modal-body">
              <div class="form-group">
                <label>Minimum Investment</label>
                <input id="minimum_investment1" class="form-control" placeholder="required" value="@if(isset($data->min_investment)){{$data->min_investment}}@endif" name="minimum_investment"/>
              </div>
              <div class="form-group iconed_field">
                <label>Offering End Date</label>
                <input id="offering_end_date1" class="form-control datetimepicker" placeholder="required" value="@if(isset($data->offering_end_date)){{$data->offering_end_date}}@endif" name="offering_end_date"/>
              </div>
         
            </div>
            <div class="modal-footer">
              
              <button type="submit" class="btn btn-secondary btn-md xs-block " data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary btn-md xs-block ml-2">
                Save Changes
              </button>
            </div>
          </form>
      </div>
    </div>
  </div>


@endsection
