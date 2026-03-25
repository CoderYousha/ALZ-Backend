<?php


namespace App\Http\Services\ApiResponse;


use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function successResponse($data){
        self::printToLog('success','returned_data : successfully');

        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection
            && $data->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {

            $paginator = $data->resource;

            return response()->json([
                'message' => trans('messages.successfully'),
                'success' => true,
                'status_code'=> 200,
                'data' => $data->collection,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                ]
            ]);
        }

        return response()->json([
            'message' => trans('messages.successfully'),
            'success' => 'true',
            'status_code' => 200,
            'data' => $data
        ]);
    }

    public static function validateResponse($errors){
        self::printToLog(' validate error','errors : '.json_encode($errors));

        return response()->json([
            'message'=>trans('messages.validation_error'),
            'success'=>'false',
            'status_code'=>422,
            'errors'=>$errors->all()],
            422);

    }

    public static function deletedResponse($msg=null){
        self::printToLog(' success');

        if(is_null($msg)) $msg = trans('messages.deleted');
        return response()->json([
            'message'=>$msg,
            'success'=>'true',
            'status_code'=>200,
            'data'=>[]],
            200);
    }


    public static function successMsgResponse($msg=null){
        self::printToLog(' success');

        if(is_null($msg)) $msg = trans('messages.successfully');
        return response()->json([
            'message'=>$msg,
            'success'=>'true',
            'status_code'=>200,
            'data'=>[]],
            200);
    }

    public static function notFoundResponse($msg=null){
        self::printToLog(' not found error');

        if(is_null($msg)) $msg = trans('messages.not_found');

        return response()->json([
            'message'=>$msg,
            'success'=>'false',
            'status_code'=>404,
            'errors'=>[$msg]],
            404);
    }


    public static function unauthorizedResponse(){
        self::printToLog('  unauthorized error');

        return response()->json([
            'message'=>trans('messages.unAuthorized'),
            'success'=>'false',
            'status_code'=>401,
            'errors'=>[trans('messages.unAuthorized')]],
            401);
    }

    public static function errorMsgResponse($msg=null,$code=400){
        if(is_null($msg) || empty($msg)) $msg = trans('messages.something_went_wrong');

        self::printToLog(' error msg','msg : '.$msg);

        if(is_array($msg)) $msgArray = $msg;
        else  $msgArray = [$msg];

        return response()->json([
            'message'=>$msg,
            'success'=>'false',
            'status_code'=>$code,
            'errors'=>$msgArray],
            $code);
    }

    private static function printToLog(string $responseStatus,?string $optionalData=null){

//        Log::channel('customized_logger')->debug('endpoint_response_status: ['.request()->path().']'.$responseStatus);
//        if(isset($optionalData)){
//            Log::channel('customized_logger')->debug($optionalData);
//        }
//        Log::channel('customized_logger')->info('end_request');
//        Log::channel('customized_logger')->info('//////////////////////////');

    }


}
