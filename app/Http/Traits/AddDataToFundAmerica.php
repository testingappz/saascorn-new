<?php

namespace App\Http\Traits;
use App\User;
use App\Countries;
use App\States;
use App\Cities;
use App\Investment;
use App\MakeInvestment;
use Mail;
use Request;
use DB;


trait AddDataToFundAmerica {

    //create entity for issuer(owner),investor(person),investor(company)
    public function CreateEntity($userId)
    {
        $response = '';

        try
        {
            //check first if profile is updated or not
            $userInfo = User::with('userdetails')->where('id',$userId)->where('profile_updated',1)->first();

            if(!empty($userInfo))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds

                $city = $userInfo->userdetails->city;
                $country = $userInfo->userdetails->country;
                $email = $userInfo->email;
                $name = $userInfo->first_name.' '.$userInfo->last_name;
                $phone = $userInfo->userdetails->phone;
                $postalCode = $userInfo->userdetails->zipcode;
                $region = $userInfo->userdetails->state;
                $streetAddress1 = $userInfo->userdetails->address_line_1;
                $taxIdNumber = $userInfo->userdetails->tax_id_number;
                $type = $userInfo->type;
                $dateOfBirth = $userInfo->userdetails->date_of_birth;
                $executiveName = $userInfo->userdetails->executive_name;
                $regionFormedIn = $userInfo->userdetails->region_formed_in;
                $investorId = $userInfo->userdetails->investor_id;
                $existingEntity = $userInfo->entity_id;

                //getcounty
                $getCountry = Countries::find($country);

                $countryName = $getCountry->sortname;

                //getstate
                $getState = States::find($region);


                if(!empty($getState->sortname))
                {
                    $stateName = $getState->sortname;
                }
                else
                {
                    $stateName = $getState->name;
                }


                //get city

                $getCity = Cities::find($city);

                $cityName = $getCity->name;


                if($userInfo->user_type=="owner")//issuer or investor type company
                {

                    //getstate
                    $getRegion = States::find($regionFormedIn);

                    if(!empty($getRegion->sortname))
                    {
                        $regionName = $getRegion->sortname;
                    }
                    else
                    {
                        $regionName = $getRegion->name;
                    }

                    $data = array("city" =>$cityName, "country" =>$countryName ,"email" =>$email , "name" =>$name ,"phone" =>$phone , "postal_code" =>$postalCode ,"region" =>$stateName, "street_address_1" =>$streetAddress1 ,"tax_id_number" =>$taxIdNumber , "type" =>"company","contact_name"=>$executiveName,"region_formed_in"=>$regionName);
                }
                else if($userInfo->user_type=="investor")//investor type person
                {

                    $data = array("city" =>$cityName, "country" =>$countryName ,"email" =>$email , "name" =>$name ,"phone" =>$phone , "postal_code" =>$postalCode ,"region" =>$stateName, "street_address_1" =>$streetAddress1 ,"tax_id_number" =>$taxIdNumber , "type" =>"person" ,"date_of_birth"=>$dateOfBirth);
                }

                $jsonData = json_encode($data);

                if(!empty($existingEntity))
                {
                    $serviceUrl = $url.'entities/'.$existingEntity;
                    $typeOfReq = "PATCH";
                }
                else
                {
                    $serviceUrl = $url.'entities';
                    $typeOfReq = "POST";
                }

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    $entityId=$response->id;

                    //check if investor is created ot not

                    if($userInfo->user_type=="investor" && empty($investorId))
                    {
                        $resInvestorId = $this->CreateInvestor($entityId);

                        //update investor id in users table
                        if(!empty($resInvestorId))
                        {
                            $resinv = User::where('id', $userId)
                                ->update([
                                  'investor_id' => $resInvestorId
                                ]);
                        }
                    }

                    if(empty($existingEntity))
                    {
                        $entityId=$response->id;

                        $res = User::where('id', $userId)
                        ->update([
                          'entity_id' => $entityId
                        ]);
                    }

                    $response =  $entityId;
                }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //create entity for disbursement purpose
    public function CreateEntityForDisbursement($userInfo)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';
        try
        {

            if(!empty($userInfo))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds

                $city = $userInfo['city'];
                $country = $userInfo['country'];
                $email = $userInfo['email'];
                $name = $userInfo['acount_name'];
                $phone = $userInfo['phone'];
                $postalCode = $userInfo['zip_code'];
                $region = $userInfo['state'];
                $streetAddress1 = $userInfo['st_address'];
                $taxIdNumber = $userInfo['tax_id_number'];
                $executiveName = $userInfo['cname'];
                $regionFormedIn = $userInfo['state'];

                //getcounty
                $getCountry = Countries::find($country);

                $countryName = $getCountry->sortname;

                //getstate
                $getState = States::find($region);


                if(!empty($getState->sortname))
                {
                    $stateName = $getState->sortname;
                }
                else
                {
                    $stateName = $getState->name;
                }


                //get city

                $getCity = Cities::find($city);

                $cityName = $getCity->name;


                //getstate
                $getRegion = States::find($regionFormedIn);

                if(!empty($getRegion->sortname))
                {
                    $regionName = $getRegion->sortname;
                }
                else
                {
                    $regionName = $getRegion->name;
                }

                $data = array("city" =>$cityName, "country" =>$countryName ,"email" =>$email , "name" =>$name ,"phone" =>$phone , "postal_code" =>$postalCode ,"region" =>$stateName, "street_address_1" =>$streetAddress1 ,"tax_id_number" =>$taxIdNumber , "type" =>"company","contact_name"=>$executiveName,"region_formed_in"=>$regionName);



                $jsonData = json_encode($data);

                $serviceUrl = $url.'entities';
                $typeOfReq = "POST";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);




                if(isset($response->id) && !empty($response->id))
                {
                    $entityId=$response->id;
                    $resp['status'] = 1;
                    $resp['data'] = $entityId;
                }
                else
                {
                   if(isset($response->entity))
                    {
                        foreach($response->entity as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;
                }
            }


        }
        catch(Exception $e){

            $resp['status'] = 0;
            $resp['message'] = $e->getMessage();
        }

        $response = $resp;

        return $response;
    }

    //create payment method for disbursement purpose
    public function CreatePaymentMethodAch($userInfo)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';
        try
        {

            if(!empty($userInfo))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds

                $acountNo = $userInfo['acount_no'];
                $entityId = $userInfo['entity_id'];
                $accountName = $userInfo['acount_name'];
                $routingNumber = $userInfo['routing_number'];

                $accountType = $userInfo['account_type'];
                $chkType = $userInfo['check_type'];


                $data = array("account_number"=>$acountNo,"entity_id" =>$entityId ,"name_on_account" =>$accountName,"routing_number" =>$routingNumber ,"type" =>"ach" ,"account_type" =>$accountType,"check_type" =>$chkType);

                $jsonData = json_encode($data);

                $serviceUrl = $url.'bank_transfer_methods';
                $typeOfReq = "POST";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);



                if(isset($response->id) && !empty($response->id))
                {
                    $entityId=$response->id;
                    $resp['status'] = 1;
                    $resp['data'] = $entityId;
                }
                else
                {
                   if(isset($response->bank_transfer_method))
                    {
                        foreach($response->bank_transfer_method as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;
                }
            }


        }
        catch(Exception $e){

            $resp['status'] = 0;
            $resp['message'] = $e->getMessage();
        }

        $response = $resp;


        return $response;
    }

    //create payment method for disbursement purpose
    public function CreatePaymentMethodWire($userInfo)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';
        try
        {

            if(!empty($userInfo))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds

                $acountNo = $userInfo['acount_no'];
                $entityId = $userInfo['entity_id'];
                $accountName = $userInfo['acount_name'];
                $routingNumber = $userInfo['routing_number'];
                $accountType = $userInfo['account_type'];
                $chkType = $userInfo['check_type'];


                $data = array("account_number"=>$acountNo,"entity_id" =>$entityId ,"name_on_account" =>$accountName,"routing_number" =>$routingNumber ,"type" =>"wire");

                $jsonData = json_encode($data);

                $serviceUrl = $url.'bank_transfer_methods';
                $typeOfReq = "POST";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);



                if(isset($response->id) && !empty($response->id))
                {
                    $entityId=$response->id;
                    $resp['status'] = 1;
                    $resp['data'] = $entityId;
                    $resp['message'] = 'success';
                }
                else
                {
                   if(isset($response->bank_transfer_method))
                    {
                        foreach($response->bank_transfer_method as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;
                }
            }


        }
        catch(Exception $e){

            $resp['status'] = 0;
            $resp['message'] = $e->getMessage();
        }

        $response = $resp;

        return $response;
    }

    //create investor
    public function CreateInvestor($entityId)
    {
        $response = '';

        try
        {
            if(!empty($entityId))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds

                $data = array("primary_entity_id" =>$entityId);


                $jsonData = json_encode($data);

                $serviceUrl = $url.'investors';
                $typeOfReq = "POST";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    $entityId=$response->id;

                    //check if investor is created ot not
                    $response =  $entityId;
                }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //update offering or project
    public function UpdateOffering($userId,$projectId,$alldata)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';


        try
        {
            $projectInfo = Investment::where('id',$projectId)->first();

            if(!empty($projectInfo))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds
                $amount = $projectInfo->budget;
                $description = $projectInfo->investment_description;
                $minInvestment = $projectInfo->min_investment;
                $escrowClosesAt = $projectInfo->offering_end_date;
                $investmentTitle = $projectInfo->investment_title;
                $existingOffer = $projectInfo->offering_id;

                //get entity id of user
                $userInfo = User::where('id',$userId)->first();

                if(isset($userInfo->entity_id) && !empty($userInfo->entity_id))
                {
                    $entityId = $userInfo->entity_id;
                }
                else
                {
                    $entityId = $this->CreateEntity($userId);
                }


                if(isset($alldata['budget']))
                {
                    $amount = $alldata['budget'];
                }
                if(isset($alldata['description']))
                {
                    $description = $alldata['description'];
                }
                if(isset($alldata['offering_end_date']))
                {
                    $escrowClosesAt = $alldata['offering_end_date'];
                }
                if(isset($alldata['minimum_investment']))
                {
                    $minInvestment = $alldata['minimum_investment'];
                }
                if(isset($alldata['title']))
                {
                    $investmentTitle = $alldata['title'];
                }


                $data = array("amount" =>$amount ,"description" =>$description,"escrow_closes_at" =>$escrowClosesAt , "min_investment_amount" =>$minInvestment ,"name" =>$investmentTitle,"max_amount"=>$amount);



                $jsonData = json_encode($data);

                if(!empty($existingOffer))
                {
                    $serviceUrl = $url.'offerings/'.$existingOffer;
                    $typeOfReq = "PATCH";
                }


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    $resp['status'] = 1;
                    $resp['message'] = 'Request Success.';
                    $resp['data'] = $response->id;

                }
                else
                {
                    if(isset($response->offering))
                    {
                        foreach($response->offering as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;

                }

            }

        }
        catch(Exception $e){
            $msg = $e->getMessage();
            $resp['status'] = 0;
            $resp['message'] = $msg;

        }
        $res = $resp;

        return $res;
    }

    //create offering or project
    public function CreateOffering($userId,$projectId,$alldata)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';


        try
        {
            if(!empty($alldata))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                //api creds
                $amount = $alldata['budget'];
                $description = $alldata['description'];
                $escrowClosesAt = $alldata['offering_end_date'];
                $minInvestment = $alldata['minimum_investment'];
                $investmentTitle = $alldata['title'];


                //get entity id of user
                $userInfo = User::where('id',$userId)->first();

                if(isset($userInfo->entity_id) && !empty($userInfo->entity_id))
                {
                    $entityId = $userInfo->entity_id;
                }
                else
                {
                    $entityId = $this->CreateEntity($userId);
                }


                if(!empty($entityId))//create offering json
                {
                    $data = array("accredited_investors" =>true, "non_us_investors"=>true,"amount" =>$amount ,"description" =>$description , "entity_id" =>$entityId,"escrow_closes_at" =>$escrowClosesAt , "min_investment_amount" =>$minInvestment ,"name" =>$investmentTitle);
                }


                $jsonData = json_encode($data);

                $serviceUrl = $url.'offerings';
                $typeOfReq = "POST";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    if(empty($existingOffer))
                    {
                        $offerId = $response->id;

                        $agreementres = $this->CreateEscrowAgreement($offerId);

                        if($agreementres!='')
                        {
                            $agreementtechres = $this->CreateTechEscrowAgreement($offerId);

                            $resp['status'] = 1;
                            $resp['message'] = 'Request Success.';
                            $resp['data'] = $response->id;
                            $resp['agreement_id'] = $agreementres;
                            $resp['tech_agreement_id'] = $agreementtechres;
                        }


                    }

                }
                else
                {
                    if(isset($response->offering))
                    {
                        foreach($response->offering as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;

                }

            }

        }
        catch(Exception $e){
            $msg = $e->getMessage();
            $resp['status'] = 0;
            $resp['message'] = $msg;

        }
        $res = $resp;

        return $res;
    }

    //function to approve offering/project to recive investments via investors in sandbox environment

    public function ApproveOffering($projectId,$offerId)
    {
        $response = '';

        try
        {
            $projectInfo = Investment::where('id',$projectId)->first();

            if(!empty($projectInfo))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                if(!empty($offerId))
                {
                    $data = array("accept_investments" =>true);
                }

                $jsonData = json_encode($data);


                $serviceUrl = $url.'test_mode/offerings/'.$offerId;
                $typeOfReq = "PATCH";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    // $projectInfo = Investment::where('id',$projectId)->with('ownerData')->first();
                    //
                    // $url = 'https://saascorn-new.iapplabz.co.in/images/templateimages/emailbg.png';
                    // $emai = $projectInfo->ownerData->email;
                    // $details = [
                    // 'name' => $projectInfo->ownerData->first_name,
                    // 'title' => $projectInfo->investment_title,
                    // 'budget' => $projectInfo->budget,
                    // 'min_investment' => $projectInfo->min_investment,
                    // 'offering_end_date' => $projectInfo->offering_end_date,
                    // 'url' => $url
                    // ];
                    // \Mail::to($emai)->send(new \App\Mail\Approve($details));

                    $res = Investment::where('id', $projectId)
                    ->update([
                      'accept_investment' => 1
                    ]);
                  }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //function to approve investment ,change status to received

    public function ApproveInvestment($investmentId,$getLatestId)
    {
        $response = '';

        try
        {
            $investInfo = MakeInvestment::where('id',$getLatestId)->first();

            if(!empty($investInfo))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                if(!empty($investmentId))
                {
                    $data = array("status" =>"received");
                }

                $jsonData = json_encode($data);


                $serviceUrl = $url.'test_mode/investments/'.$investmentId;
                $typeOfReq = "PATCH";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {

                    $res = MakeInvestment::where('id', $getLatestId)
                    ->update([
                      'status' => 'received'
                    ]);

                }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //function to create investment
    public function CreateInvestment($userId,$amount,$projectId)
    {
        $response = '';

        try
        {
            $projectInfo = Investment::where('id',$projectId)->first();

            if(!empty($projectInfo))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
                //api creds
                $amount = $projectInfo->budget;
                $description = $projectInfo->investment_description;
                $minInvestment = $projectInfo->min_investment;
                $escrowClosesAt = $projectInfo->offering_end_date;
                $investmentTitle = $projectInfo->investment_title;
                $existingOffer = $projectInfo->offering_id;

                //get entity id of user
                $userInfo = User::where('id',$userId)->first();

                if(isset($userInfo->entity_id) && !empty($userInfo->entity_id))
                {
                    $entityId = $userInfo->entity_id;
                }
                else
                {
                    $entityId = $this->CreateEntity($userId);
                }

                if(empty($existingOffer))
                {
                    if(!empty($projectInfo) && !empty($entityId))//create offering json
                    {
                        $data = array("accredited_investors" =>true, "amount" =>$amount ,"description" =>$description , "entity_id" =>$entityId,"escrow_closes_at" =>$escrowClosesAt , "min_investment_amount" =>$minInvestment ,"name" =>$investmentTitle);
                    }
                }
                else
                {
                    $data = array("amount" =>$amount ,"description" =>$description,"escrow_closes_at" =>$escrowClosesAt , "min_investment_amount" =>$minInvestment ,"name" =>$investmentTitle,"max_amount"=>$amount);
                }


                $jsonData = json_encode($data);

                if(!empty($existingOffer))
                {
                    $serviceUrl = $url.'offerings/'.$existingOffer;
                    $typeOfReq = "PATCH";
                }
                else
                {
                    $serviceUrl = $url.'offerings';
                    $typeOfReq = "POST";
                }

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->id) && !empty($response->id))
                {
                    if(empty($existingOffer))
                    {
                        $offerId=$response->id;

                        $res = Investment::where('id', $projectId)
                        ->update([
                          'offering_id' => $offerId
                        ]);


                    }

                }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //function to create ACh auth id
    public function CreateACHAuth($userId,$alldata)
    {
        $achId='';

        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request.';
        //api creds
        $url = env('FUND_AMERICA_URL');//url
        $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
        //api creds
        $res = User::where('id', $userId)->first();



        if($res->ach_id!='')
        {
            $resp['status'] = 1;
            $resp['data'] = $res->ach_id;
        }
        else
        {
            $ipAddress = Request::ip();

            try
            {

                $data = array("account_number" =>$alldata['account_number'],"address" =>$alldata['address'] ,"city" =>$alldata['city'],"email"=>$res->email,"entity_id" =>$res->entity_id,"ip_address"=>$ipAddress,"literal" =>$res->first_name,"name_on_account"=>$alldata['name_on_account'],"routing_number" =>$alldata['route_number'],"state"=>$alldata['state'],"zip_code"=>$alldata['zipcode']);


                $jsonData = json_encode($data);
                $serviceUrl = $url.'ach_authorizations';
                $typeOfReq = "POST";



                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);



                if(isset($response->id) && $response->id!='')
                {
                    $achId = $response->id;
                    $res = User::where('id', $userId)
                    ->update([
                      'ach_id' => $achId
                    ]);


                    $resp['status'] = 1;
                    $resp['data'] = $achId;
                }
                else
                {
                    if(isset($response->ach_authorization))
                    {
                        foreach($response->ach_authorization as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;

                }

            }
            catch(Exception $e){

                $resp['status'] = 0;
                $resp['message'] = $e->getMessage();

            }

        }

        return $resp;

    }

    //function to make  investment using ACh auth id
    public function MakeInvestmentUsingACH($userId,$alldata)
    {
        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
            //api creds

            //get ach authorization id either from db or api
           $getachIdResponse =  $this->CreateACHAuth($userId,$alldata);

           $res = array();

           if($getachIdResponse['status']==0 || $getachIdResponse['status']==2)
           {
                $resp['status']=$getachIdResponse['status'];
                $resp['message']=$getachIdResponse['message'];
                return $resp;
           }
           else
           {
                $getachId = $getachIdResponse['data'];

                $resUser = User::where('id', $userId)->first();

                if($resUser->entity_id=='')
                {
                    $this->CreateEntity($userId);
                    $resUser = User::where('id', $userId)->first();
                }

                 $project = Investment::where('id', $alldata['project_id'])->first();

                $data = array("amount" =>$alldata['amount'] ,"offering_id" =>$project->offering_id,"address" =>$alldata['address'] , "payment_method" =>"ach","entity_id" =>$resUser->entity_id,"ach_authorization_id"=>$getachId);


                $jsonData = json_encode($data);
                $serviceUrl = $url.'investments';
                $typeOfReq = "POST";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                if(isset($response->id) && !empty($response->id))
                {
                    $investmentId = $response->id;
                    $res['status'] = 1;
                    $res['investmentStatus']=$response->status;
                    $res['data'] = $investmentId;
                    $res['res'] = json_encode($response);

                    //save data in investment table to create new project
                    $investment = new MakeInvestment;
                    $investment->investor_id = $userId;
                    $investment->project_id = $alldata['project_id'];
                    $investment->investment_id = $res['data'];
                    $investment->status = $res['investmentStatus'];
                    $investment->response = $res['res'];
                    $investment->amount = $alldata['amount'];
                    $investment->save();

                    //get investlatese id to update status in db
                    $getLatestId = $investment->id;

                    //approve status to received

                    $this->ApproveInvestment($investmentId,$getLatestId);

                    //approve status to received

                }
                else
                {
                    foreach($response->investment as $k=>$error)
                    {
                       $allErrors[] = $k.': '.$error[0].'</br>';
                    }


                    $res['status']  =2;
                    $res['message'] = $allErrors;
                }

               return $res;
           }
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
            $res['status'] = 0;
            $res['message'] = $msg;
            return $res;
        }
    }

    //function to make  investment using wire method
    public function MakeInvestmentUsingWire($userId,$alldata)
    {
        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
            //api creds

            $res = array();

            $resUser = User::where('id', $userId)->first();

            if($resUser->entity_id=='')
            {
                $this->CreateEntity($userId);
                $resUser = User::where('id', $userId)->first();
            }

            $project = Investment::where('id', $alldata['project_id'])->first();

            $data = array("amount" =>$alldata['amount'] ,"offering_id" =>$project->offering_id, "payment_method" =>"wire","entity_id" =>$resUser->entity_id);

            $jsonData = json_encode($data);
            $serviceUrl = $url.'investments';
            $typeOfReq = "POST";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_POSTFIELDS => $jsonData,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));


            // obtain response
            $getResponse = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse);

            if(isset($response->id) && !empty($response->id))
            {
                $investmentId = $response->id;
                $res['status'] = 1;
                $res['investmentStatus']=$response->status;
                $res['data'] = $investmentId;
                $res['res'] = json_encode($response);

                //save data in investment table to create new project
                $investment = new MakeInvestment;
                $investment->investor_id = $userId;
                $investment->project_id = $alldata['project_id'];
                $investment->investment_id = $res['data'];
                $investment->status = $res['investmentStatus'];
                $investment->response = $res['res'];
                $investment->amount = $alldata['amount'];
                $investment->save();

                //get investlatese id to update status in db
                $getLatestId = $investment->id;

                //approve status to received

                $this->ApproveInvestment($investmentId,$getLatestId);

                //approve status to received

            }
            else
            {
                foreach($response->investment as $k=>$error)
                {
                   $allErrors[] = $k.': '.$error[0].'</br>';
                }


                $res['status']  =2;
                $res['message'] = $allErrors;
            }

           return $res;

        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
            $res['status'] = 0;
            $res['message'] = $msg;
            return $res;
        }
    }

    //function to make  investment using check method
    public function MakeInvestmentUsingCheck($userId,$alldata)
    {
        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
            //api creds

            $res = array();

            $resUser = User::where('id', $userId)->first();

            if($resUser->entity_id=='')
            {
               $data =  $this->CreateEntity($userId);

                $resUser = User::where('id', $userId)->first();
            }

            $project = Investment::where('id', $alldata['project_id'])->first();

            $data = array("amount" =>$alldata['amount'] ,"offering_id" =>$project->offering_id, "payment_method" =>"check","entity_id" =>$resUser->entity_id);



            $jsonData = json_encode($data);
            $serviceUrl = $url.'investments';
            $typeOfReq = "POST";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_POSTFIELDS => $jsonData,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));


            // obtain response
            $getResponse = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse);

            //die();
            if(isset($response->id) && !empty($response->id))
            {
                $investmentId = $response->id;
                $res['status'] = 1;
                $res['investmentStatus']=$response->status;
                $res['data'] = $investmentId;
                $res['res'] = json_encode($response);

                //save data in investment table to create new project
                $investment = new MakeInvestment;
                $investment->investor_id = $userId;
                $investment->project_id = $alldata['project_id'];
                $investment->investment_id = $res['data'];
                $investment->status = $res['investmentStatus'];
                $investment->response = $res['res'];
                $investment->amount = $alldata['amount'];
                $investment->save();

                //get investlatese id to update status in db
                $getLatestId = $investment->id;

                //approve status to received

                $this->ApproveInvestment($investmentId,$getLatestId);

                //approve status to received

            }
            else
            {
                foreach($response->investment as $k=>$error)
                {
                   $allErrors[] = $k.': '.$error[0].'</br>';
                }


                $res['status']  =2;
                $res['message'] = $allErrors;
            }

           return $res;

        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
            $res['status'] = 0;
            $res['message'] = $msg;
            return $res;
        }
    }

    //get data related investments for investor
    public function GetInvestorDetails($userId)
    {
        $noOfInvestments=$totalInvestment=$countTransactions=0;

        //get count of no of investments
        $noOfInvestments = MakeInvestment::where('investor_id',$userId)->count();

        //get sum of total investment

        $totalInvestment = MakeInvestment::where('investor_id',$userId)->sum('amount');

        //get count of total transactions

        $countTransactions = MakeInvestment::where('investor_id',$userId)->count();

        return array($noOfInvestments,$totalInvestment,$countTransactions);
    }

    //get data related investments for owner

    public function GetOwnerDetails($userId)
    {
        //get count of no of investments
        $noOfInvestments=$sumOFFunding=$totalRaised=0;

        $getProjects =  Investment::select(DB::raw('GROUP_CONCAT(id) AS projectlist'))->where('user_id',$userId)->first()->toArray();

        $list = explode(',',$getProjects['projectlist']);

        $noOfInvestments =   MakeInvestment::whereIn('project_id',$list)->count();


        //get sum of funding goals

        $totalRaised =   MakeInvestment::whereIn('project_id',$list)->sum('amount');

        //get sum of total raised amount

        $sumOFFunding =   Investment::where('user_id',$userId)->sum('budget');

        return array($noOfInvestments,$sumOFFunding,$totalRaised);
    }

    //function to get investment details
    public function InvestmentDetails($investmentId)
    {
        $response='';

        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key
            //api creds
            $res = array();
            $serviceUrl = $url.'investments/'.$investmentId;
            $typeOfReq = "GET";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));

            // obtain response
            $getResponse = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse,true);

        }
        catch(Exception $e)
        {

        }

        return $response;
    }

    //create escrow application
    public function CreateEscrowApplication($agreementId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($agreementId))
            {
                $url = url('/');
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                if(!empty($offerId))
                {
                    $data = array("escrow_agreement_id" =>$agreementId,"ppm_url"=>$url,"offering_id"=>$offerId);
                }

                $jsonData = json_encode($data);


                $serviceUrl = $url.'escrow_service_applications';
                $typeOfReq = "POST";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                //die();

                //update response
                $resinv = Investment::where('agreement_id',$agreementId)->update(['application_response' => $getResponse]);

                if(isset($response->id) && !empty($response->id))
                {
                    //update application id
                    $resinv = Investment::where('agreement_id',$agreementId)->update(['application_id' => $response->id]);
                }


            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //create escrow agreement

    public function CreateEscrowAgreement($offerId)
    {
        $response = '';

        try
        {
            if(!empty($offerId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                if(!empty($offerId))
                {
                    $data = array("offering_id"=>$offerId);
                }

                $jsonData = json_encode($data);

                $serviceUrl = $url.'escrow_agreements';
                $typeOfReq = "POST";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                if(isset($response->signing_links->issuer_signature->url) && !empty($response->signing_links->issuer_signature->url))
                {
                    /*$fromEmail = env('MAIL_FROM_ADDRESS');//from email address
                    $fromName = env('MAIL_FROM_NAME');//from name

                    $agreementUrl =  $response->signing_links->issuer_signature->url;
                    $customData = array('name'=>$name,'agreementUrl'=>$agreementUrl);
                    $userData = array('fromEmail'=>$fromEmail,'fromName'=>$fromName,'email'=>$email,'name'=>$name);*/

                    //send url to issuer so that issuer can sign agreement
                   /* Mail::send('email.signAgreement',$customData,function ($message) use ($userData) {
                        $message->from($userData['fromEmail'], $userData['fromName']);
                        $message->to($userData['email'],$userData['name'])->subject('Escrow Saascorn Agreement');

                    });*/

                   return $response->id;
                }

            }
        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //get escrow agreement

    public function GetEscrowAgreement($agreementId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($agreementId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                $serviceUrl = $url.'escrow_agreements/'.$agreementId;
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                return $response;

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //create tech escrow agreement

    public function CreateTechEscrowAgreement($offerId)
    {
        $response = '';

        try
        {
            if(!empty($offerId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                if(!empty($offerId))
                {
                    $data = array("offering_id"=>$offerId);
                }

                $jsonData = json_encode($data);

                $serviceUrl = $url.'tech_services_agreements';
                $typeOfReq = "POST";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                if(isset($response->signing_links->issuer_signature->url) && !empty($response->signing_links->issuer_signature->url))
                {

                   return $response->id;
                }

            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //get tech escrow agreement

    public function GetTechEscrowAgreement($agreementId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($agreementId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                $serviceUrl = $url.'tech_services_agreements';
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                return $response;
            }


        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //check escrow agreement status signed or not
    public function CheckUpdatedEscrowAgreement($agreementId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($agreementId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key


                $serviceUrl = $url.'escrow_agreements/'.$agreementId;
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);



                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                $resinvres = Investment::where('id',$offerId)->update(['agreement_response' => $getResponse]);

                if(isset($response->signed) && ($response->signed===true || $response->signed===TRUE))
                {
                    $resinv = Investment::where('id',$offerId)->update(['agreement_status' => 1]);

                    return 1;
                }

            }


        }
        catch(Exception $e){

            //return $response;

            $resinvres = Investment::where('id',57)->update(['agreement_response' => $e->getMessage()]);
        }

        return $response;
    }

    //check escrow tech agreement status signed or not
    public function CheckUpdatedTechEscrowAgreement($agreementId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($agreementId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key


                $serviceUrl = $url.'tech_services_agreements/'.$agreementId;
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);



                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                $resinvres = Investment::where('id',$offerId)->update(['tech_agreement_response' => $getResponse]);

                if(isset($response->signed) && ($response->signed===true || $response->signed===TRUE))
                {
                    $resinv = Investment::where('id',$offerId)->update(['tech_agreement_status' => 1]);

                    return 1;
                }

            }


        }
        catch(Exception $e){

            //return $response;

            $resinvres = Investment::where('id',57)->update(['agreement_response' => $e->getMessage()]);
        }

        return $response;
    }


    //check escrow application status
    public function CheckApplicationStatus($applicationId,$offerId)
    {
        $response = '';

        try
        {
            if(!empty($applicationId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key


                $serviceUrl = $url.'escrow_service_applications/'.$applicationId;
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);

                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);


                if(isset($response->status))
                {
                    if($response->status=="pending")//Being reviewed
                    {
                        $status=0;
                    }
                    else if($response->status=="accepted")//The escrow account has been opened and your offering can now accept investments.
                    {
                        $status=1;

                        $projectInfo = Investment::where('id',$offerId)->with('ownerData')->first();
                        if($projectInfo->accept_investment == 0 && $status == 1){

                            // $url = 'https://saascorn-new.iapplabz.co.in/images/templateimages/emailbg.png';
                            $url = $_SERVER['HTTP_HOST'].'/images/templateimages/emailbg.png';
                            $email = $projectInfo->ownerData->email;
                            $details = [
                            'name' => $projectInfo->ownerData->first_name,
                            'title' => $projectInfo->investment_title,
                            'budget' => $projectInfo->budget,
                            'min_investment' => $projectInfo->min_investment,
                            'offering_end_date' => $projectInfo->offering_end_date,
                            'url' => $url
                            ];
                            \Mail::to($email)->send(new \App\Mail\Approve($details));
                      }

                    }
                    else if($response->status=="denied")//here was a problem with the application that customer service was unable to resolve.

                    {
                        $status=2;
                    }

                    $resinv = Investment::where('id',$offerId)->update(['accept_investment' => $status,'application_response' => $getResponse]);

                    return 1;
                }

            }


        }
        catch(Exception $e){

            //return $response;

            return $response;
        }

        return $response;
    }

    //function to send signature of owner for escrow agreement

    public function SignAgreementLinkFA($companyName,$email,$signature,$title,$signlink,$agreementId,$offeringId,$useragent)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';


        try
        {
            if(!empty($agreementId))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                $ipAddress = Request::ip();

                if(!empty($signature))//create offering json
                {
                    $data = array("company" =>$companyName, "email" =>$email ,"ip_address" =>$ipAddress , "literal" =>$signature,"name" =>$signature , "title" =>$title ,"user_agent" =>$useragent);
                }


                $jsonData = json_encode($data);



                $serviceUrl = $signlink;
                $typeOfReq = "PATCH";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                //update response
                Investment::where('agreement_id',$agreementId)->update(['agreement_response'=>$getResponse]);

                if(isset($response->id) && !empty($response->id))
                {
                    $signId = $response->id;

                    if($response->signed===true || $response->signed===TRUE)
                    {
                        Investment::where('agreement_id',$agreementId)->update(['agreement_status'=>1]);

                        //send request for escrow application
                        $this->CreateEscrowApplication($agreementId,$offeringId);

                        $resp['status'] = 1;
                        $resp['message'] = 'Agreement Signed Succcessfully.';
                    }
                    else
                    {
                        $resp['status'] = 0;
                        $resp['message'] = 'Sign Process Failed.Please Try After Sometime.';
                    }

                }
                else
                {
                    $resp['status'] = 0;
                    $resp['message'] = $response->status_message;

                }

            }

        }
        catch(Exception $e){
            $msg = $e->getMessage();
            $resp['status'] = 0;
            $resp['message'] = $msg;

        }
        $res = $resp;

        return $res;
    }

    //function to send signature of owner for escrow agreement

    public function SignTechAgreementLinkFA($companyName,$email,$signature,$title,$signlink,$agreementId,$offeringId,$useragent)
    {
        $resp['status'] = 0;
        $resp['message'] = 'Invalid Request';


        try
        {
            if(!empty($agreementId))
            {
                //api creds

                $url = env('FUND_AMERICA_URL');//url

                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                $ipAddress = Request::ip();

                if(!empty($signature))//create offering json
                {
                    $data = array("company" =>$companyName, "email" =>$email ,"literal" =>$signature , "title" =>$title,"name" =>$signature , "ip_address" =>$ipAddress ,"user_agent" =>$useragent);
                }


                $jsonData = json_encode($data);

                $serviceUrl = $signlink;
                $typeOfReq = "PATCH";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));


                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                //update response
                Investment::where('tech_agreement_id',$agreementId)->update(['tech_agreement_response'=>$getResponse]);

                if(isset($response->id) && !empty($response->id))
                {
                    $signId = $response->id;

                    if($response->signed===true || $response->signed===TRUE)
                    {
                        Investment::where('tech_agreement_id',$agreementId)->update(['tech_agreement_status'=>1]);

                        $resp['status'] = 1;
                        $resp['message'] = 'Agreement Signed Succcessfully.';
                    }
                    else
                    {
                        $resp['status'] = 0;
                        $resp['message'] = 'Sign Process Failed.Please Try After Sometime.';
                    }

                }
                else
                {
                    if(isset($response->electronic_signature))
                    {
                        foreach($response->electronic_signature as $k=>$error)
                        {
                           $errors[] = $k.': '.$error[0].'</br>';
                        }

                    }

                    $resp['status'] = 2;
                    $resp['message'] = $errors;

                }

            }

        }
        catch(Exception $e){
            $msg = $e->getMessage();
            $resp['status'] = 0;
            $resp['message'] = $msg;

        }
        $res = $resp;

        return $res;
    }

    //check status of investment when a update for status is received via webhook
    public function CheckUpdatedInvestment($investmentId,$getId)
    {
        $response = '';

        try
        {
            if(!empty($investmentId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key


                $serviceUrl = $url.'investments/'.$investmentId;
                $typeOfReq = "GET";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);



                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                $resinv = MakeInvestment::where('id',$getId)->update(['response' => $getResponse]);


                if(isset($response->status))
                {
                    //update status of investment
                    $resinv = MakeInvestment::where('id',$getId)->update(['status' => $response->status]);

                    return 1;
                }

            }


        }
        catch(Exception $e){

            //return $response;

            return $response;
        }

        return $response;
    }

    //get all disbursements

    public function GetAlldisbursements($start,$limit)
    {
        $response = '';

        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

            $serviceUrl = $url.'disbursements?page='.$start.'&per='.$limit.'';
            $typeOfReq = "GET";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));

            // obtain response
            $getResponse = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse);

        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //get all investments related to offering

    public function GetAllInvestments($start,$limit,$offeringId)
    {
        $response = '';

        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

            $serviceUrl = $url.'/offerings/'.$offeringId.'/investments?page='.$start.'&per='.$limit.'';

            //echo "<pre>";
            //print_r($serviceUrl);
            //die();
            $typeOfReq = "GET";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));

            // obtain response
            $getResponse = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse);

        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //get detail of single disbursement
    public function GetSingleDisbursement($id)
    {
        $response = '';

        try
        {
            //api creds
            $url = env('FUND_AMERICA_URL');//url
            $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

            $serviceUrl = $url.'disbursements/'.$id;
            $typeOfReq = "GET";

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $serviceUrl,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $typeOfReq,
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$token."",
                "content-type: application/json",
              ),
            ));

            // obtain response
            $getResponse = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $response = json_decode($getResponse);

        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //function to Create Disbursements (ach)

    public function CreateDisbursementUsingAch($userInfo)
    {
        $res['status']  = 0;
        $res['message'] = 'Invalid Request';

        try
        {
            if(!empty($userInfo))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key

                $city = $userInfo['city'];
                $country = $userInfo['country'];
                $email = $userInfo['email'];
                $name = $userInfo['acount_name'];
                $phone = $userInfo['phone'];
                $postalCode = $userInfo['zip_code'];
                $region = $userInfo['state'];
                $streetAddress1 = $userInfo['st_address'];
                $taxIdNumber = $userInfo['tax_id_number'];
                $executiveName = $userInfo['cname'];
                $regionFormedIn = $userInfo['state'];
                $amount = $userInfo['amount'];
                $reference = $userInfo['reference'];
                $offering = $userInfo['offering_id'];
                $bankTransferMethodId = $userInfo['bank_transfer_method_id'];

                //getcounty
                $getCountry = Countries::find($country);

                $countryName = $getCountry->sortname;

                //getstate
                $getState = States::find($region);


                if(!empty($getState->sortname))
                {
                    $stateName = $getState->sortname;
                }
                else
                {
                    $stateName = $getState->name;
                }


                //get city

                $getCity = Cities::find($city);

                $cityName = $getCity->name;


                //getstate
                $getRegion = States::find($regionFormedIn);

                if(!empty($getRegion->sortname))
                {
                    $regionName = $getRegion->sortname;
                }
                else
                {
                    $regionName = $getRegion->name;
                }



                if(!empty($userInfo))
                {
                    $datajson = array("status"=>"pending","amount"=>$amount,"city"=>$cityName,"country"=>$countryName,"email"=>$email,"name"=>$name,"phone"=>$phone,"postal_code"=>$postalCode,"region"=>$stateName,"street_address_1"=>$streetAddress1,"street_address_2"=>$streetAddress1,"reference"=>$reference,"bank_transfer_method_id"=>$bankTransferMethodId,"offering_id"=>$offering);
                }

                $jsonData = json_encode($datajson);

                $serviceUrl = $url.'disbursements';
                $typeOfReq = "POST";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                if(isset($response->id) && !empty($response->id))
                {

                   $res['status']  = 1;
                   $res['data'] = $response->id;
                }
                else
                {
                    foreach($response->disbursement as $k=>$error)
                    {
                       $allErrors[] = $k.': '.$error[0].'</br>';
                    }


                    $res['status']  =2;
                    $res['message'] = $allErrors;
                }

            }


        }
        catch(Exception $e){

            $res['status']  = 0;
            $res['message'] = $e->getMessage();
        }

        $response =  $res;
        return $response;
    }


    //function to Create Disbursements (Check)

    public function UpdateDisbursement($data)
    {
        $res['status']  = 0;
        $res['message'] = $e->getMessage();

        try
        {
            if(!empty($offerId))
            {
                //api creds
                $url = env('FUND_AMERICA_URL');//url
                $token = base64_encode(env('FUND_AMERICA_SECRET_KEY'));//api key


                if(!empty($offerId))
                {
                    //$data = array("amount"=>);
                }

                $jsonData = json_encode($data);

                $serviceUrl = $url.'disbursements/'.$id;
                $typeOfReq = "PATCH";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $serviceUrl,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => $typeOfReq,
                  CURLOPT_POSTFIELDS => $jsonData,
                  CURLOPT_HTTPHEADER => array(
                    "authorization: Basic ".$token."",
                    "content-type: application/json",
                  ),
                ));

                // obtain response
                $getResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($getResponse);

                if(isset($response->id) && !empty($response->id))
                {

                   $res['status']  = 1;
                   $res['data'] = $response->id;
                }
                else
                {
                    foreach($response->disbursement as $k=>$error)
                    {
                       $allErrors[] = $k.': '.$error[0].'</br>';
                    }


                    $res['status']  =2;
                    $res['message'] = $allErrors;
                }

            }


        }
        catch(Exception $e){

            $res['status']  = 0;
            $res['message'] = $e->getMessage();
        }

        $response =  $res;
        return $response;
    }
}
