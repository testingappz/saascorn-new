@extends('admin.layouts.admin') 
@section('content')


<div class="card">
    <div class="card-header">
       View Investment Details 
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    @if(isset($list['updates']) && !empty($list['updates']))
                    <tr>
                        <th>
                            Investor Name
                        </th>
                        <td>
                            {{$list['investor_detail']['first_name']}} {{$list['investor_detail']['last_name']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Investment Title
                        </th>
                        <td>
                            {{$list['investment_detail']['investment_title']}} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Investment Budget
                        </th>
                        <td>
                            ${{$list['investment_detail']['budget']}} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Investment Minimun Investment
                        </th>
                        <td>
                            ${{$list['investment_detail']['min_investment']}} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Administration Fee
                        </th>
                        <td>
                            ${{$list['updates']['administration_fee']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Aml Check Investor
                        </th>
                        <td>
                            {{$list['updates']['aml_check_investor']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Amount
                        </th>
                        <td>
                            ${{$list['updates']['amount']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Amount In Escrow
                        </th>
                        <td>
                            ${{$list['updates']['amount_in_escrow']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Amount Received
                        </th>
                        <td>
                            ${{$list['updates']['amount_received']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Amount Refunded
                        </th>
                        <td>
                            ${{$list['updates']['amount_refunded']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Bank Reference
                        </th>
                        <td>
                            {{$list['updates']['bank_reference']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Check Mailing Address
                        </th>
                        <td>
                            {{$list['updates']['check_mailing_address']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Check Mailing Instructions
                        </th>
                        <td>
                            {{$list['updates']['check_mailing_instructions']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Debt Par Value
                        </th>
                        <td>
                            ${{$list['updates']['debt_par_value']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Equity Share Count
                        </th>
                        <td>
                            ${{$list['updates']['equity_share_count']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Equity Share Price
                        </th>
                        <td>
                            ${{$list['updates']['equity_share_price']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Funds Transfer Method
                        </th>
                        <td>
                            {{$list['updates']['funds_transfer_method']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            In Escrow At
                        </th>
                        <td>
                            {{$list['updates']['in_escrow_at']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            {{$list['updates']['status']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Remittance Details
                        </th>
                        <td>
                            <b>Bank Address: </b>{{$list['updates']['remittance_details']['bank_address']}}
                            </br>
                            <b>Bank Name: </b>
                            {{$list['updates']['remittance_details']['bank_name']}}
                             </br>
                            <b>Bank Phone: </b>
                            {{$list['updates']['remittance_details']['bank_phone']}}
                             </br>
                            <b>Routing Number: </b>
                            {{$list['updates']['remittance_details']['routing_number']}}
                             </br>
                            <b>Account Number: </b>
                            {{$list['updates']['remittance_details']['account_number']}}
                             </br>
                            <b>Beneficiary Name: </b>
                            {{$list['updates']['remittance_details']['beneficiary_name']}}
                             </br>
                            <b>Beneficiary Address: </b>
                            {{$list['updates']['remittance_details']['beneficiary_address']}}
                            </br>
                            <b>Reference: </b>
                            {{$list['updates']['remittance_details']['reference']}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Wire Details
                        </th>
                        <td>
                            <b>Bank Address: </b>{{$list['updates']['wire_details']['bank_address']}}
                            </br>
                            <b>Bank Name: </b>
                            {{$list['updates']['wire_details']['bank_name']}}
                             </br>
                            <b>Bank Phone: </b>
                            {{$list['updates']['wire_details']['bank_phone']}}
                             </br>
                            <b>Routing Number: </b>
                            {{$list['updates']['wire_details']['routing_number']}}
                             </br>
                            <b>Swift Code: </b>
                            {{$list['updates']['wire_details']['swift_code']}}
                             </br>
                            <b>Account Number: </b>
                            {{$list['updates']['wire_details']['account_number']}}
                             </br>
                            <b>Beneficiary Address: </b>
                            {{$list['updates']['wire_details']['beneficiary_address']}}
                            </br>
                            <b>Reference: </b>
                            {{$list['updates']['wire_details']['reference']}}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                Back To Investments Listing
        </div>


    </div>
</div>
@endsection
            
                
                