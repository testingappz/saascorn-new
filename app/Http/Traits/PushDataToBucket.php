<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Storage;


trait PushDataToBucket {

    //upload
    public function AddFileToBucket($path,$data)
    {
        $response = 0;
        try
        {
            $res = Storage::disk('s3')->put($path,file_get_contents($data));
            if($res)
            {
                $response = 1;
            }
            
        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }

    //get
    public function GetFileUrlFromBucket($path)
    {
        $url = '';
        
        try
        {
            $res = Storage::disk('s3')->url($path);
            if($res)
            {
                $url = $res;
            }
            
        }
        catch(Exception $e){

            return $url;
        }

        return $url;
    }  

    //remove
    public function RemoveFileFromBucket($path)
    {
        $response = 0;

        try
        {
            $res = Storage::disk('s3')->delete($path);
            if($res)
            {
                $response = $res;
            }
            
        }
        catch(Exception $e){

            return $response;
        }

        return $response;
    }    
}