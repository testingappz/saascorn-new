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
                        <img class="responsive" src="{{env('AWS_BUCKET_PATH')}}images/{{ Auth::user()->profile_image}}" alt="image"/>
                      </div>
                      <div>
                        <h3 class="mb-0">{{Auth::user()->first_name}} {{ Auth::user()->last_name }}</h3>
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
                 
                <div class="inner_form">
                  @if (session('success'))
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                  @endif
                   @if (session('error'))
                    <div class="alert alert-error">
                        {{session('error')}}
                    </div>
                  @endif
                  <div class="form_heading">
                    <h3>Add New Investment</h3>
                    <p>Please fill all the details below</p>
                  </div>
                  
                  <form id="add_new_project" name="add_new_project" method="post" enctype="multipart/form-data" action="{{ route('addInvestment') }}">
                        {{ csrf_field() }}                
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label>Project Title</label> 
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus placeholder="Required"/>
                             @if ($errors->has('title'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('title')}}.</strong>
                            </span>
                            @endif
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-8">
                        <div class="form-group">
                          <label>Description</label> 
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description"  required autocomplete="description" autofocus placeholder="Required">{{old('description')}}</textarea>
                             @if ($errors->has('description'))
                            <span class="invalid feedback" role="alert">
                              <strong>{{$errors->first('description')}}.</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                  </div>
                    <div class="row">
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                          <label>Project Budget</label> 
                          <input id="budget" type="text" class="form-control @error('budget') is-invalid @enderror" name="budget" value="{{ old('budget') }}" required autocomplete="budget" autofocus placeholder="Required (in $)"/>
                             @if ($errors->has('budget'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('budget')}}.</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-4">

                        
                        <div class="form-group iconed_field">
                          <label>Offering End Date</label> 
                          
                         
                             <input id="offering_end_date" type="text" class=" datetimepicker form-control @error('offering_end_date') is-invalid @enderror" name="offering_end_date" value="{{ old('offering_end_date') }}" required autocomplete="offering_end_date" autofocus placeholder="Required"/>
                           
                            @if ($errors->has('offering_end_date'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('offering_end_date')}}.</strong>
                            </span>
                            @endif
                           
                          
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                          <label>Minimum Investment</label> 
                          <input id="minimum_investment" type="text" class="form-control @error('minimum_investment') is-invalid @enderror" name="minimum_investment" value="{{ old('minimum_investment') }}" required autocomplete="minimum_investment" autofocus placeholder="Required (in $)"/>
                            @if ($errors->has('minimum_investment'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('minimum_investment')}}.</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-md-4">
                      <div class="form-group">
                        <label>Benefit Return</label> 
                        <input id="benefit_return" type="text" class="form-control @error('benefit_return') is-invalid @enderror" name="benefit_return" value="{{ old('benefit_return') }}" required autocomplete="benefit_return" autofocus placeholder="Required (in %)"/>
                            @if ($errors->has('benefit_return'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('benefit_return')}}.</strong>
                            </span>
                            @endif
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Project Image</label> 
                      <div class="profile_pic_update">
                        <div class="profile_pic">
                          <img id="file_upload_project_preview" class="responsive" src="{{url('/images/image_placeholder.svg')}}" alt="image"/>
                        </div>
                        <div class="input_file">
                          <label for="file_upload_project" class="btn btn-outline-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Upload Picture</label>
                          <input id="file_upload_project" type="file" class="form-control @error('project_image') is-invalid @enderror" name="project_image" value="{{ old('project_image') }}"  autocomplete="project_image" autofocus placeholder="Required" accept="image/png,image/jpg,image/jpeg,image/gif" />
                            @if ($errors->has('project_image'))
                            <span class="invalid feedback" role="alert">
                                <strong>{{$errors->first('project_image')}}.</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12  col-md-4">
                    <div class="form-group">
                      <label>Documents</label> 
                      <div class="input_file">
                        <label for="doc_upload" class="btn btn-fade-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Add More Docs</label>
                        <input id="doc_upload" multiple name="documents[]" type="file"  accept="image/*,.doc, .docx,.pdf"  />
                      </div>
                      <input type="hidden" id="doclisthide" name="doclist[]">
                      <div class="file_listing" id="doclist">
                          <!-- <div class="list">
                              <h5>Promo_doc.docx (2 mb)</h5>
                              <img src="images/delete.svg" alt="delete"/>
                          </div>
                          <div class="list">
                            <h5>Intro.doc (1 mb)</h5>
                            <img src="images/delete.svg" alt="delete"/>
                          </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-12  col-md-4">
                    <div class="form-group">
                      <label>Videos</label> 
                      <div class="input_file">
                        <label for="video_upload" class="btn btn-fade-primary btn-sm md-block btn-less-rounded btn-font-13 mb-0">Add Video(s)</label>
                        <input id="video_upload" multiple name="videos[]" type="file"  accept="video/*"  />
                      </div>
                      <input type="hidden" id="vidlisthide" name="vidlisthide[]">
                       <div class="file_listing" id="vidlist">
                        <!-- <div class="list">
                            <h5>Promo_doc.mp4 (2 mb)</h5>
                            <img src="images/delete.svg" alt="delete"/>
                        </div> -->
                      </div> 
                    </div>
                  </div>
                </div>

                
                <button id="btn-submit"
                  type="submit"
                  class="btn btn-primary btn-md xs-block mt-4"
                >
                Add Investment
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
