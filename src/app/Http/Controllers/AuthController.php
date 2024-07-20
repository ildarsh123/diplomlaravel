<?php

namespace App\Http\Controllers;

use Delight\Auth\Role;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    private $auth;
    private $request;

    public function __construct(Request $request)
    {
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();// required and must be unique in the ducks table
        $this->auth = new \Delight\Auth\Auth($db);
        $this->request = $request;

    }


    public function registration()
    {
        $this->request->validate([
            'email'            => 'required|email',
            'password'         => 'required'
        ]);

        //Illuminate\Validation\Rules\Password




        try {
            $this->auth->register($this->request->input('email'), $this->request->input('password'));
            return redirect('/login')->with('success', 'Регистрация успешна');

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return redirect('/register/form')->with('error', 'Invalid email address');

        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return redirect('/register/form')->with('error', 'Invalid password');

        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return redirect('/register/form')->with('error', 'User already exists');

        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            return redirect('/register/form')->with('error', 'Too many requests');

        }
    }

    public function login()
    {

        $this->request->validate([
            'email'            => 'required|email',
            'password'         => 'required'

        ]);


        try {
            $this->auth->login($this->request->input('email'), $this->request->input('password'));

            return redirect('/');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return redirect('/login')->with('error', 'Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return redirect('/login')->with('error', 'Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            return redirect('/login')->with('error', 'Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            return redirect('/login')->with('error', 'Too many requests');
        }
    }

    public function isLoggedIn()
    {
        return $this->auth->isLoggedIn();
    }

    public function logout()
    {
        $this->auth->logOut();

        return redirect('/login');

    }

    public function setUserAsAdmin() {
        $this->auth->admin()->addRoleForUserById('2', \Delight\Auth\Role::ADMIN);
    }

    public function isAdmin()
    {
        return $this->auth->hasRole(Role::ADMIN);
    }

    public function getUserId()
    {
        return $id = $this->auth->getUserId();
    }

    public function createNewUser($email, $password) {


        try {
            $userId = $this->auth->admin()->createUser($email, $password);


            return $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return redirect('/')->with('error', 'Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return redirect('/')->with('error', 'Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return redirect('/')->with('error', 'User already exists');
        }


    }


    public function changeUsersEmailOwn($email) {
        try {
            $this->auth->changeEmail($email, function ($selector, $token) {
                //echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email to the *new* address)';
                $num =  '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';

            }) ;

            redirect('/profile')->with('success', 'Почта пользователя изменена');


        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            redirect('/profile')->with('error', 'Invalid email address');

        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            redirect('/profile')->with('error', 'Email address already exists');


        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            redirect('/profile')->with('error', 'Account not verified');


        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            redirect('/profile')->with('error', 'Not logged in');


        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            redirect('/profile')->with('error', 'Too many requests');


        }
    }

    public function changeUsersEmailByAdmin($id) {
        return redirect('/profile/' . $id) -> with('error', 'Only user can change own email');
    }

    public function changeUsersPasswordOwn($oldpassword, $newpassword) {
        try {
            $this->auth->changePassword($oldpassword, $newpassword);

            echo 'Password has been changed';
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password(s)');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function changeUsersPasswordByAdmin($password, $id) {
        try {
            $this->auth->admin()->changePasswordForUserById($_POST['id'], $_POST['newPassword']);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
    }

    public function deleteUser () {
        try {

            $this->auth->admin()->deleteUserById();
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }
    }
}


