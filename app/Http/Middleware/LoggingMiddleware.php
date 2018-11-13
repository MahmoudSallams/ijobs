<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;

class LoggingMiddleware
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
		$this->startTime = microtime(true);
		
		return $next($request);
	}
	
	public function terminate($request, $response)
	{
		$this->end = microtime(true);
		
		$this->log($request, $response);
	}
	
	protected function log($request, $response)
	{
		//Log::info('Duration:  ' .number_format($this->end - $this->start, 3));
		Log::info('URL: ' . $request->fullUrl());
		Log::info('Post Data: ' . json_encode($request->all()));
		Log::info('Response: ' . json_encode($response));
		Log::info('===========================');
		//Log::info('Method: ' . $request->getMethod());
		//Log::info('IP Address: ' . $request->getClientIp());
		//Log::info('Status Code: ' . $response->getStatusCode());
	}
}
