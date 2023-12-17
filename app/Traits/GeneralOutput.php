<?php

namespace App\Traits;

trait GeneralOutput
{
    public function returnCheck(bool $status,$msgSuccess="",$msgFails){
        return response()->json([
            'status' => $status,
            //'errNum' => $errNum,
            'msg' => $status?$msgSuccess:$msgFails
        ]);
    //     if(!$status)
    //     return $this->returnError($msgFails);
    //    return $this->returnSuccessMessage($msgSuccess);
    }
    public function returnError($msg)
    {
        return response()->json([
            'status' => false,
            //'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($msg = "")
    {
        return response()->json([
            'status' => true,
            //'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnData($key='data', $value, $msg = ""){
        return [
            'status' => true,
            'msg'=>$msg,
            $key=>$value,
        ];
    }
}
