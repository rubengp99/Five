<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    private $chatkit;
    private $roomId;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->chatkit = app('ChatKit');
        $this->roomId = 'e01b3fa9-3295-4178-9521-dbd5d5757cbd';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user=\DB::table('users')->where('email','=',$data['email'])->get();
        // Create User account on Chatkit
        $this->chatkit->createUser([
            'id' =>  $data['email'],
            'name' =>  $data['name'],
            'custom_data' => [
                'avatar' => $data['avatar'],
              ] 
        ]);

        $this->chatkit->addUsersToRoom([
            'user_ids' => [$data['email']],
            'room_id' => $this->roomId
        ]);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'profile_image' => $data['avatar'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
