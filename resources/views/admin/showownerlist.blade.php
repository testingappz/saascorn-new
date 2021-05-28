@extends('admin.layouts.admin') 
@section('content')


<div class="card">
    <div class="card-header">
       Show 
    </div>

     <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           Accredited Investor Type
                        </th>
                        <td>
						@if(isset($list[0]->userdetails->accredited_investor_type))
							
							@if($list[0]->userdetails->accredited_investor_type == 1)
							 Yes 
							@elseif ($list[0]->userdetails->accredited_investor_type == 2)
							No
							@elseif ($list[0]->userdetails->accredited_investor_type == 3)
							know
							@else
							-
							@endif
                        @endif    
                        </td>
                    </tr>
                    <tr>
                        <th>
                         Accredited Type
                        </th>
                        <td>
						
						@if(isset($list[0]->userdetails->accredited_type))
							@if($list[0]->userdetails->accredited_type == 1)
							Bank 
							@elseif ($list[0]->userdetails->accredited_type == 2)
							Business
							@elseif ($list[0]->userdetails->accredited_type == 3)
							Corporation
							@elseif ($list[0]->userdetails->accredited_type == 4)
							Employee
							@elseif ($list[0]->userdetails->accredited_type == 5)
							Individual
							@elseif ($list[0]->userdetails->accredited_type == 6)
							With Spouse
							@elseif ($list[0]->userdetails->accredited_type == 7)
							Trust
							@else
							-
							@endif
						@endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                          Company Name
                        </th>
                        <td>
                            {{ $list[0]->userdetails->company_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Phone Number
                        </th>
                        <td>
                            {{ $list[0]->userdetails->phone ?? ''}}
                        </td>
                    </tr>
					 <tr>
                        <th>
                         Address Line
                        </th>
                        <td>
                             {{ $list[0]->userdetails->address_line_1 ?? '' }}
                        </td>
                    </tr>
					  <tr>
                        <th>
                            	Address Liine
                        </th>
                        <td>
                             {{ $list[0]->userdetails->address_line_2 ?? '' }}
                        </td>
                    </tr>
					  <tr>
                        <th>
                           City
                        </th>
                        <td>
                             {{ $list[0]->userdetails->city ?? '' }}
                        </td>
                    </tr>
					  <tr>
                        <th>
                            State
                        </th>
                        <td>
                             {{ $list[0]->userdetails->state ?? ''}}
                        </td>
                    </tr>
					  <tr>
                        <th>
                           Country
                        </th>
                        <td>
                             {{ $list[0]->userdetails->country  ?? ''}}
                        </td>
                    </tr>
					  <tr>
                        <th>
                            Investor Type
                        </th>
                        <td>
							@if(isset($list[0]->userdetails->investor_type))
								@if($list[0]->userdetails->investor_type == 1)
								Person 
								@elseif ($list[0]->userdetails->investor_type == 2)
								Entity
								@else
								-
								@endif
							@endif
							 	
                        </td>
                    </tr>
					  <tr>
                        <th>
                            	Max Investment
                        </th>
                        <td>
                             {{ $list[0]->userdetails->max_investment ?? '' }}
                        </td>
                    </tr>
					  <tr>
                        <th>
                            	Annual Income
                        </th>
                        <td>
                             {{ $list[0]->userdetails->annual_income ??'' }}
                        </td>
                    </tr>
					  <tr>
                        <th>
                            Networth
                        </th>
                        <td>
                             {{ $list[0]->user_details[0]->networth ?? '' }}
                        </td>
                    </tr>  <tr>
                        <th>
                            Last Investment
                        </th>
                        <td>
                             {{ $list[0]->user_details[0]->last_investment ?? '' }}
                        </td>
                    </tr>
					
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
				Back To Owner Listing
        </div>



    </div>
</div>
@endsection
			
				
				