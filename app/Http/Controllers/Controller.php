<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * Format JSON response
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function jsonResponse(array $data, $code = 200): JsonResponse
    {
        return response()->json(array_merge($data, ['status_code' => $code]), $code);
    }

    /**
     * Some operation (save only?) has completed successfully
     * @param mixed $data
     * @return mixed
     */
    public function respondWithSuccess($data)
    {
        return $this->jsonResponse(is_array($data) ? $data : ['message' => $data]);
    }

    /**
     * Respond with an Error
     * @param string $data
     * @param int $code
     * @return JsonResponse
     */
    public function respondWithError($data = 'There was an error', $code = 400)
    {
        return $this->jsonResponse(is_array($data) ? $data : ['error' => true, 'errors' => $data], $code);
    }
}

