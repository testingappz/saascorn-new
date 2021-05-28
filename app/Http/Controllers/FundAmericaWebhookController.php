<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Investment;
use App\MakeInvestment;
use Mail;
use App\Http\Traits\AddDataToFundAmerica;

class FundAmericaWebhookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    use AddDataToFundAmerica;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fundAmericaUpdates()
    {
        try
        {
            $postData = $_POST['data'];//get response of webhook in data param
            $jsonData = json_decode($postData);//get json and convert it into array
            $type = isset($jsonData->object)?$jsonData->object:'';
            $resourceType = isset($jsonData->resource_type)?$jsonData->resource_type:'';
            $agreementId = isset($jsonData->id)?$jsonData->id:'';
            $signature = isset($jsonData->signature)?$jsonData->signature:'';
            $action = isset($jsonData->action)?$jsonData->action:'';


            //$resinv = Investment::where('id',69)->update(['tech_agreement_response' => $postData]);

            //escrow agreement
            /*if($type=='escrow_agreement' && $agreementId!='' && $action=='update' && $signature!='')
            {
                $getdata = Investment::where('agreement_id',$agreementId)->first();
                //update signed response
                $resinv = Investment::where('agreement_id',$agreementId)->update(['agreement_response' => $postData]);

                //check by using agreement url that if agreement is updated as signed
                $getUpdatedResponse = $this->CheckUpdatedEscrowAgreement($agreementId,$getdata->id);

                //create escrow application

                if($getUpdatedResponse==1)
                {
                    $getUpdatedResponse = $this->CreateEscrowApplication($agreementId,$getdata->id);
                }

                
            }//tech service agreement
            else if($type=='offering' && $agreementId!='' && $action=='update' && $signature!='')
            {

                $getdata = Investment::where('offering_id',$agreementId)->first();
                //update signed response
                $resinv = Investment::where('offering_id',$agreementId)->update(['tech_agreement_response' => $postData]);

                //check by using agreement url that if agreement is updated as signed
                $getUpdatedResponse = $this->CheckUpdatedTechEscrowAgreement($getdata->tech_agreement_id,$getdata->id);
                
            }*/

            //escrow_service_application
            if($type=='escrow_service_application' && $agreementId!='' && $action=='update' && $signature!='')
            {
                $getdata = Investment::where('application_id',$agreementId)->first();
                //update signed response
                //check  application is updated as approved,pending,declined
                $getUpdatedResponse = $this->CheckApplicationStatus($getdata->application_id,$getdata->id);
                
            }
            //investment
            if($type=='investment' && $agreementId!='' && $action=='update' && $signature!='')
            {
                $getdata = MakeInvestment::where('investment_id',$agreementId)->first();
                //update signed response
                //check by investment api if investment is approved or not
                $getUpdatedResponse = $this->CheckUpdatedInvestment($agreementId,$getdata->id);
            }

            echo "1";

        }
        catch(Exception $e)
        {
            abort(403, $e->getMessage());
        }
    }

   
}
