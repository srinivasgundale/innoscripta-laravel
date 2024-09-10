<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RequestResponseLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Log the request details
        $this->logRequest($request);

        // Proceed with the request and capture the response
        $response = $next($request);

        // Log the response details
        $this->logResponse($response);

        return $response;
    }

    /**
     * Log request details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function logRequest($request)
    {
        Log::info('Request Details', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);
    }

    /**
     * Log response details.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    protected function logResponse($response)
    {
        Log::info('Response Details', [
            'status' => $response->status(),
            'content' => $response->getContent(),
        ]);
    }
}
