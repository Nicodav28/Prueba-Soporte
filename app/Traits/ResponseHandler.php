<?php

namespace App\Traits;

use App\Facades\TraceCodeMaker;
use Illuminate\Http\RedirectResponse;

trait ResponseHandler
{
    private function redirectWithSuccess(string $message, string $methodName, string $className, ?int $httpCode = null, ?string $codeDescription = null): RedirectResponse
    {
        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode('TEST', $httpCode ?? 200, $methodName, $className, $codeDescription ?? $message);

        return redirect()->back()->with('success', $message . ' - ' . $traceCode);
    }

    private function redirectWithError(string $message, string $methodName, string $className, ?int $httpCode = null, ?string $codeDescription = null): RedirectResponse
    {
        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode('TEST', $httpCode ?? 500, $methodName, $className, $codeDescription ?? $message);

        return redirect()->back()->with('error', $message . ' - ' . $traceCode);
    }
}