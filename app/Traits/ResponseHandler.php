<?php

namespace App\Traits;

use App\Facades\TraceCodeMaker;
use Illuminate\Http\JsonResponse;

trait ResponseHandler
{
    /**
     * Succesfull base response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        string $methodName,
        string $className,
        ?string $message = 'Successful Response',
        ?int $httpCode = 200,
        mixed $data = null,
        ?string $codeDescription = null,
    ): JsonResponse {

        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode('TEST', $httpCode, $methodName, $className, $codeDescription);

        $finalResponse = [
            'resultCode'    => 'SUCCESS',
            'resultMessage' => $message,
            'traceCode'     => $traceCode['traceCode'] ?? 'Could not get a trace code.',
            'result'        => $data,
        ];

        $response = response()->json($finalResponse, $httpCode);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }

    /**
     * Error base response
     *
     * @return JsonResponse
     */
    protected function errorResponse(
        string $methodName,
        string $className,
        ?string $message = 'An error occurred while processing the request.',
        ?int $httpCode = 500,
        ?string $codeDescription = null,
        mixed $result = [],
    ): JsonResponse {

        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode('TEST', $httpCode, $methodName, $className, $codeDescription);

        $finalResponse = [
            'resultCode'    => 'ERROR',
            'resultMessage' => $message,
            'traceCode'     => $traceCode['traceCode'] ?? 'Could not get a trace code.',
        ];

        if (isset($result) && !empty($result)) {
            $finalResponse['result'] = $result;
        }

        $response = response()->json($finalResponse, $httpCode);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}
