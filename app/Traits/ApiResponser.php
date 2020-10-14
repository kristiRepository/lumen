<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $code
     * @return void
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {

        return response()->json(['data' => $data, $code]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @param [type] $code
     * @return void
     */
    public function errorResponse($message, $code)
    {

        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
