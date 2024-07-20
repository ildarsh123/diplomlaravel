<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    private $auth;
    public function __construct(AuthController $auth)
    {

        $this->auth = $auth;


    }

    public function handle(Request $request, Closure $next): Response
    {

        if (!$this->auth->isAdmin()) {
            return redirect('/');
        }

        return $next($request);
    }
}
