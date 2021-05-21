<?php

namespace App\Http\Middleware;

use App\helpers\JwtAuth;
use Closure;
use Illuminate\Http\Request;

class apiAuthMiddl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            return $next($request);

        } else {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => "Usuario no identificado",
            );
            return response()->json($data, $data['code']);
        }

    }
}