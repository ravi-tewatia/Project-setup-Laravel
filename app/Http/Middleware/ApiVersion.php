<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $version = $request->segment(2);

        if (!$version || !in_array($version, config('api.supported_versions', ['v1']))) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid API version',
                'meta' => [
                    'timestamp' => now()->toIso8601String(),
                ]
            ], 400);
        }

        return $next($request);
    }
} 