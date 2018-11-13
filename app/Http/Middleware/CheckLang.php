<?php

namespace App\Http\Middleware;

use Closure;

class CheckLang
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
    	$fullUrl = $request->fullUrl();
    	$fullUrlArr = explode("/", $fullUrl);
    	
    	$lang = "en";
    	$found = false;
    	foreach ($fullUrlArr as $s)
    	{
    		if($found)
    		{
    			if($s == "en" || $s == "ar")
    			{
    				$lang = $s;
    			}
    			
    			$found = false;
    		}
    		
    		if($s == "api")
    		{
    			$found = true;
    		}
    	}
    	
    	$request->setLocale($lang);
    	
        return $next($request);
    }
}
