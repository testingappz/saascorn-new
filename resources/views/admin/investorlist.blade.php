@extends('admin.layouts.admin') 
@section('content')
 <div class="row">
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Investor List</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table" class="table table-striped table-bordered first">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>User Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										@foreach($list as $key => $list)
                                            <tr>
                                                <td>{{ $list->first_name ?? '' }}</td>
                                                <td>{{ $list->last_name ?? '' }}</td>
                                                <td>{{ $list->email ?? '' }}</td>
                                                <td>{{ $list->user_type ?? '' }}</td>
												 <td>
													 <a class="btn btn-xs btn-primary" href="{{ route('investorlist_show', $list->id) }}">
													   Show
														</a>

													
													@if( $list->status == 1)
														 <input type="button"  class="active_deactive state active1 btn btn-xs btn-info" id="{{$list->id}}_0_investor" value="Active" >
														
														@else
													
														 <input type= "button"   class="active_deactive state disabled1 btn btn-xs btn-info " id="{{$list->id}}_1_investor" value="Deactive" >
														
														@endif
												
											
												</td>
                                              
                                            </tr>
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