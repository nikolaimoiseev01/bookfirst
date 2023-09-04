<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/myaccount/collections';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'name.required' => 'Пожалуйста, введите имя',
            'surname.required' => 'Пожалуйста, введите фамилию',
            'email.required' => 'Пожалуйста, введите Email',
            'email.email' => 'Email введен неверно',
            'email.unique' => 'Этот Email уже используется в нашей системе',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль должен содержать 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'reg_type' => 'self',
            'password' => Hash::make($data['password']),
            'reg_utm_source' => $data['utm_source'],
            'reg_utm_medium' => $data['utm_medium'],
        ]);

        $user_wallet = new UserWallet;
        $user_wallet->user_id = $user['id'];
        $user_wallet->cur_amount = 0;
        $user_wallet->save();

        $user->assignRole('user');
        return $user;
    }
}
