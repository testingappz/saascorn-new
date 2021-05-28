@extends('admin.layouts.admin') 
@section('content')


<div class="card">
    <div class="card-header">
       View Disbursement Details 
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    
                    <tr>
                        <th>
                            Amount
                        </th>
                        <td>
                            {{$list->amount}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Check Mailing Address
                        </th>
                        <td>
                            {{$list->check_mailing_address}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Check Payee
                        </th>
                        <td>
                            {{$list->check_payee}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            City
                        </th>
                        <td>
                            {{$list->city}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Contact Name
                        </th>
                        <td>
                            {{$list->contact_name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Country
                        </th>
                        <td>
                            {{$list->country}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Created At
                        </th>
                        <td>
                            {{$list->created_at}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Disbursed At
                        </th>
                        <td>
                            {{$list->disbursed_at}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{$list->email}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{$list->name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Payment Details
                        </th>
                        <td>
                            {{$list->payment_details}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Payment Method
                        </th>
                        <td>
                            {{$list->payment_method}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Phone
                        </th>
                        <td>
                            {{$list->phone}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Postal Code
                        </th>
                        <td>
                            {{$list->postal_code}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Reference
                        </th>
                        <td>
                            {{$list->reference}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Region
                        </th>
                        <td>
                            {{$list->region}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            {{$list->status}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Street Address 1
                        </th>
                        <td>
                            {{$list->street_address_1}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Street Address 2
                        </th>
                        <td>
                            {{$list->street_address_2}}
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                Back To Disbursement Listing
        </div>


    </div>
</div>
@endsection
            
                
                