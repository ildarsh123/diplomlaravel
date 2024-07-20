<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\AuthController;

class EnsureUserHasAccess
{
    private $auth;
    public function __construct(AuthController $auth)
    {

        $this->auth = $auth;


    }

    public function handle(Request $request, Closure $next): Response
    {     
        if (!$this->auth->isLoggedIn()) {
            return redirect('/login');
        }

         return $next($request);
    }
}
