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
                        
                        <img class="responsive" src=" @if(isset($userData->profile_image)){{env('AWS_BUCKET_PATH')}}images/{{$userData->profile_image }}@else images/person2.png @endif" alt="image"/>
                      </div>
                      <div>
                        <h3 class="mb-0">@if(isset($userData->first_name)){{$userData->first_name}}@endif @if(isset($userData->last_name)){{$userData->last_name}} @endif</h3>
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
            <div class="social_media_share">
              <ul>
                <li>
                  <a href="javascript:;">
                    <img src="{{url('/images/social_fb.svg')}}" alt="icon"/>
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{url('/images/social_tw.svg')}}" alt="icon"/>
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{url('/images/social_in.svg')}}" alt="icon"/>
                  </a>
                </li>
                <li>
                  <a href="javascript:;">
                    <img src="{{url('/images/social_insta.svg')}}" alt="icon"/>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row  mb-50px">
          <div class="col-12">
              <div class="manage_header">
                <h2>Manage Investment</h2>
                <div class="search_ui">
                  <form id="search_form" name="search_form" method="GET" action="{{ route('dashboard') }}">
                    <input type="search" id="search_input" name="search" placeholder="Search Keyword" class="form-field" required value="{{ Request::get('search') }}"/>
                   
                    <input id="search_button" class="btn btn-primary  btn-md btn-shadow" type="submit" value="search"/>
                  </form>
                </div>
              </div>
          </div>
        </div>
        <div class="row" id="investment_section">
          @if(isset($data) && !empty($data))
            @foreach($data as $invest)
          <div class="col-12 col-lg-4 investment_block" data-id="{{$invest->id}}">
            <div class="company">
              <div class="company_image">
                <img src="@if(isset($invest->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$invest->investment_image }}@endif" class="responsive" alt="image" />
              </div>
              <div class="company_text">
                <h3>@if(isset($invest->investment_title)){{$invest->investment_title}}@endif</h3>
                <p>
                  @if(isset($invest->investment_description)){{$invest->investment_description}}@endif
                </p>
                <div class="funds">
                  <dl>
                    <dt>Funding Goal</dt>
                    <dd>@if(isset($invest->budget)){{$invest->budget}}@endif</dd>
                  </dl>
                  <dl>
                    <dt>Min. Investment</dt>
                    <dd>@if(isset($invest->min_investment)){{$invest->min_investment}}@endif</dd>
                  </dl>
                </div>
                <a href="{{route('investmentDetails', ['id' => $invest->id])}}" class="link"
                  >View <img src="{{url('/images/arrow_blue.svg')}}" alt="icon"
                /></a>
              </div>
            </div>
          </div>
          @endforeach
          <div class="col-12" id="load_more">
            <div class="view_all">
              <a id="loadmorebutton" onclick="loadMoreInvestments();"
                class="btn btn-primary btn-md"
                href="javascript:;"
                >Load more
              </a>
            </div>
          </div>
          @endif
        </div>
      </div>
    </section>
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
                  <input id="offering_end_date1" class="form-control" placeholder="required" value="@if(isset($data->offering_end_date)){{$data->offering_end_date}}@endif" name="offering_end_date"/>
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
