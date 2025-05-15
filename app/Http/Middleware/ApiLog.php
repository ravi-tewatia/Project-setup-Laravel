<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiLog
{
    /**
     * Handle an incoming request.
     * log every api log in to api log channel
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (app()->environment(['local', 'uat', 'dev', 'stg']) && env("API_LOG", false)) {
            $sensitive = config('constants.SENSITIVE');
            $route = $request->route()->getName();
            $isSensitive = in_array(Str::before($route, '.'), $sensitive);
            $sesitiveFields = config('constants.SESITIVE_FIELDS');
            Log::channel('api')->debug(
                sprintf(
                    "\n\n******** Start Log For %s  ********\n" .
                    "Start Time: %s \n" .
                    "Route Name : %s \n" .
                    "Api End Point : %s \n" .
                    "Api Full URL : %s \n" .
                    "Method: %s \n" .
                    "Header Data: %s \n" .
                    "Request Parameters: %s \n" .
                    "Response: %s \n" .
                    "Remote Address: %s \n" .
                    "User Agent: %s \n" .
                    "User Id: %s \nEnd Time: %s \n\n ******** End Log ********\n\r",
                    $request->path(),
                    Carbon::now()->toDateTimeString(),
                    $route,
                    Str::before($route, '.'),
                    $request->fullUrl(),
                    $request->getMethod(),
                    json_encode($request->header()),
                    json_encode($request->except($sesitiveFields)),
                    $isSensitive ? "{}" : $response->getContent(),
                    $request->ip(),
                    $request->userAgent(),
                    !empty(Auth::id()) ? Auth::id() : "",
                    Carbon::now()->toDateTimeString(),
                )
            );
        }
        return $response;
    }
}
