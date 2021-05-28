@extends('admin.layouts.admin') 
@section('content')
 <div class="row">
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Project List</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table" class="table table-striped table-bordered first">
                                        <thead>
                                            <tr>
												<th>S.No</th>
												<th>Name</th>
                                                <th>Investment Title</th>
                                                <th>Investment Description</th>
                                                <th>Budget</th>
                                                <th>Min Investment</th>
                                                <th>benifit Return</th>
												<th>Investment Image</th>
												<th>Document's</th>
                                                <th>Offering End Date</th>
												 <th>Action</th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $d=1; @endphp	
										@foreach($list as $key => $list)
								
									
                                            <tr>
												<td>{{$d}}</td>
												<td>{{ $list->user[0]->first_name?? ''}}</td>
												<td>{{ $list->investment_title ?? '' }}</td>
												<td>
													{{strlen($list->investment_description) > 100 ? substr($list->investment_description,0,100)."..." : $list->investment_description}}
												</td>
												<td>{{ $list->budget ?? '' }}</td>
												<td>{{ $list->min_investment ?? '' }}</td>
												<td>{{ $list->benifit_return ?? '' }}</td>
												<td>
												
													
												  <img width="100%" height="100"  src= "@if(isset($list->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$list->investment_image}}@endif" class="responsive" alt="No Image" />
												
												</td>
											
												
												<td>
													@if( count($list->investmentDocs)>0)
													
													 <a class="btn btn-xs btn-primary" href="{{ route('showProjectList', $list->id) }}">
													View Document's ({{count($list->investmentdocs)}})
													</a>
													@else
													-
													@endif
												</td>
												
                                                <td>{{ $list->offering_end_date ?? '' }}</td>
												 <td>
													
													@if( $list->status == 1)
														 <input type="button"  class="changestatus state active1 btn btn-xs btn-info" id="{{$list->id}}_0" value="Activated" >
														
													@else
												
													 <input type= "button" class="changestatus state disabled1 btn btn-xs btn-info " id="{{$list->id}}_1" value="Deactivated" >
													
													@endif
												
											
												</td>
                                              
                                            </tr>
                                            @php $d++; @endphp
                                        @endforeach   
                                        </tbody>
                                      
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end basic table  -->
                    <!-- ============================================================== -->
                </div>
				
				@endsection
				
				