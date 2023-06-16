<?php

namespace App\Http\Middleware;

use App\Models\Log as ModelsLog;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WriteLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $log = null;

        try {
            $data = [
                'ip' => $request->ip(),
                'user_id' => auth('api')?->user()?->id ?? null,
                'route' => $request->path(),
                'method' => $request->method(),
                'data' => json_encode($request->all()),
            ];
            $log = new ModelsLog($data);
            $log->save();
        } catch (Exception $e) {
            Log::error('WriteLogMiddleware', [$e]);
        } finally {
            Log::info('WriteLogMiddleware', [$log]);
        }

        return $next($request);
    }
}
