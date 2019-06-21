<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class SupervisorCheckMiddleware {

    public function handle($request, Closure $next, $guard = null) {
        $user = $request->auth;
        if ($user->user_type != "Admin" && $user->user_type != "Supervisor") {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 401);
        }
        return $next($request);
    }

}
