<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $auth;

    public function __construct(AuthController $auth,)
    {
        $this->auth = $auth;
    }

    public function getUserId() {
        return $this->auth->getUserId();

    }

    public function createNewUser(Request $request) {
        $userData = $request->all();
        $request->validate([
            "name" => "required",
            "profession" => "required",
            "tel" => "required",
            "address" => "required",
            "email" => "required",
            "image" => "required",
            "status" => "required"
        ]);
        $userid = $this->auth->createNewUser($request->email, $request->password);
        unset($userData['_token']);
        unset($userData['password']);

        $request->validate(['image' => 'nullable|image|mimes:jpeg,jpg,png,gif',]);  // napechatat oshibki pri vivode dannih
        $userData['image'] = basename($request->file('image')->store('public/avatars'));
        $userData['tags'] = $userData['name'];
        $userData['user_id'] = $userid;
        DB::table('users_data')->insert($userData);

        return redirect('/')->with('success', 'User created!');
    }

    public function updateUserSecurity(Request $request, $id)
    {
        $request->validate([]);
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        if ((intval($this->auth->getUserId()) === intval($id))) {


            if (!empty($request->input('email')) && $userData->email !== $request->input('email')){
                $this->auth->changeUsersEmailOwn($userData->email);
            }

            if (!empty($request->input('password')) && $request->input('password') !== $request->input('password_confirmation')) {
                $this->auth->changeUsersPasswordOwn($request->input('oldpassword'), $request->input('password'));

                return redirect('/profile/'. $id)->with('success', 'User security updated!');
            }

            dd(1);
        }

        if ($this->auth->isAdmin() && !($this->auth->getUserId() === $id)) {



            if (!empty($request->input('email')) && $userData->email !== $request->input('email')){
                $this->auth->changeUsersEmailByAdmin($id);

            }

            if (!empty($request->input('password')) && $request->input('password') !== $request->input('password_confirmation')) {
                $this->auth->changeUsersPasswordByAdmin($id, $request->input('password') );
            }

            return redirect('/profile/'. $id)->with('success', 'User security updated!');

        }



    }

    public function statusUser (Request $request, $id) {
        //$request->validate([]);
        $userData = DB::table('users_data')->where('user_id', $id)->first();
        $newStatus = $request->input('status');
        $oldStatus = $userData->status;
        if($newStatus !== $oldStatus) {
            DB::table('users_data')->where('user_id', $id)->update(['status' => $newStatus]);
        }

        return redirect('/profile/'. $id)->with('success', 'Status updated!');
    }

    public function editUser (Request $request, $id) {
        $request->validate([
            "name" => "required",
            "profession" => "required",
            "tel" => "required",
            "address" => "required",

        ]);
        $userData = $request->all();
        unset($userData['_token']);
        DB::table('users_data')->where('user_id', $id)->update($userData);
        return redirect('/profile/' . $id)->with('success', 'User updated!');
    }

    public function changeAvatar (Request $request, $id)
    {

        $request->validate(['image' => 'nullable|image|mimes:jpeg,jpg,png,gif',]);  // napechatat oshibki pri vivode dannih


        $image = basename($request->file('image')->store('public/avatars'));
        DB::table('users_data')->where('user_id', $id)->update(['image' => $image]);
        return redirect('/profile/'. $id)->with('success', 'Image successfully uploaded');

    }


}
