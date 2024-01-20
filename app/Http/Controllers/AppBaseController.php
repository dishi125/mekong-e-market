<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function responseWithData($data,$message)
    {
        return Response::json(array('Response'=>true,'ResponseCode' => 1, 'ResponseMessage' => $message, 'Data' => $data));
    }

    public function responseWith2Data($data,$data2,$message)
    {
        return Response::json(array('Response'=>true,'ResponseCode' => 1, 'ResponseMessage' => $message, 'Data' => $data, 'transaction_fees' => $data2));
    }

    public function responseError($message)
    {
        return Response::json(array('Response'=>false,'ResponseCode' => 0, 'ResponseMessage' => $message));
    }

    public function sendSuccess($message)
    {
        return Response::json(array('Response'=>true,'ResponseCode' => 1, 'ResponseMessage' => $message));
    }
}
