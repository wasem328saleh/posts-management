<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponderTrait
{

    public function returnError($errNum,$msg): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $msg
        ],$errNum);
    }
    public function returnSuccessMessage($msg,$errNum): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg
        ],$errNum);
    }
    public function returnData($data,$msg,$errNum): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg,
            'data' => $data
        ],$errNum);
    }
}
