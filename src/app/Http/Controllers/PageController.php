<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    private $user;
    private $auth;

    public function __construct(UserController $user, AuthController $auth)
    {
        $this->user = $user;
        $this->auth = $auth;
    }


    public function mainPage() {
        $admin = $this->auth->isAdmin();      //true or false
        $currentUserId = $this->auth->getUserId();


        $usersData = DB::table('users_data')->get();
        return view('users',['usersData' => $usersData, 'admin' => $admin, 'currentUserId' => $currentUserId]);
    }

    public function editPage($id) {
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        return view('edit',['userData' => $userData]);
    }

    public function createUserPage() {
        return view('create_user');
    }

    public function profilePage($id)
    {
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        return view('page_profile',['userData' => $userData]);
    }

    public function statusPage($id)
    {
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        return view('status',['userData' => $userData]);
    }

    public function securityPage($id)
    {
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        return view('security',['userData' => $userData]);
    }

    public function mediaPage($id)
    {
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        return view('usermedia',['userData' => $userData]);
    }


}
