<?php

namespace App\Traits ;

trait ManageApiTrait
{
    public function createApi($data , $msg = 'Data is created Sucessfully')
    {
        return response()->json(['msg' =>$msg , 'data' => $data] , 201);
    }

    public function successApi($data = null , $msg = null )
    {
        return response()->json([ 'message' => $msg , 'data' => $data] , 200);
    }

    public function failedApi($msg = '' , $status = 422)
    {
        return response()->json(['status' => false , 'message' => $msg ] , $status);
    }

}

