<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\AuthController;

class CanEditInformation
{

    private $auth;
    public function __construct(AuthController $auth)
    {

    $this->auth = $auth;


    }

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->pathInfo;
        $path = (strrchr($path,'/'));
        $trans = array("/" => "");
        $path = strtr($path, $trans);
        $userId = intval($path);

        if($this->auth->isAdmin()||$this->auth->getUserId() === $userId) {
            return $next($request);
        }
        return redirect('/');
    }
}
