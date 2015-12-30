<?php

namespace App\Api\Controllers;

use Arrilot\Api\Skeleton\BaseController;

abstract class Controller extends BaseController
{
    public function success($message='success',$status_code=200){
        $this->setStatusCode($status_code);
        return $this->respondWithArray([
            'message'=>$message,
            'status_code'=>$status_code
        ]);
    }

    public function successWithData($message='success',$status_code=200){
        if($message==""){
            $message="success";
        }
        if($status_code==""){
            $status_code=200;
        }
        $this->setStatusCode(200);
       return $this->respondWithArray([
            'message'=>$message,
            'status_code'=>$status_code,
           key(func_get_arg(2))=>func_get_arg(2)[key(func_get_arg(2))]
        ]);
        /*return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code,
            key(func_get_arg(2))=>func_get_arg(2)[key(func_get_arg(2))]
        ])->statusCode($status_code);*/

    }
    public function errorWithData($message='error',$status_code=404){
        if($message==""){
            $message="error";
        }
        if($status_code==""){
            $status_code=404;
        }
        return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code,
            key(func_get_arg(2))=>func_get_arg(2)[key(func_get_arg(2))]
        ])->statusCode($status_code);
    }
    /**
     * error response method with default message and error code
     */
    public function error($message='error',$status_code=404){
        $this->setStatusCode($status_code);
        return $this->respondWithItem([
            'message'=>$message,
            'status_code'=>$status_code
        ]);
    }

    public function my_validate($data_array){
        $validate=Validator::make($data_array['data'],$data_array['rules'],$data_array['messages']);
        if($validate->fails()){
            return ['result'=>false,'error'=>$this->error(Messages::showErrorMessages($validate),422)];
        }else{
            return ['result'=>true,'error'=>'no error'];
        }
    }

}
