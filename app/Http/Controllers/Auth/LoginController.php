<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the participation and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/myaccount/collections/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function sign_vk()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function callback_vk()
    {
        $user = Socialite::driver('vkontakte')->stateless()->user();
        dd($user);

        $user = User::firstOrCreate([
            'email' => $user->email,
            'reg_type' => 'vk',
        ], [
            'email' => $user->email,
            'name' => $user->user['first_name'],
            'surname' => $user->user['last_name'],
            'reg_type' => 'vk',
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'avatar' => $user->avatar,
            'avatar_cropped' => $user->avatar
        ]);

        dd($user);


        $user->assignRole('user');

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Отлично!');

        if ($user->wasRecentlyCreated) {

            $user_wallet = new UserWallet;
            $user_wallet->user_id = $user['id'];
            $user_wallet->cur_amount = 0;
            $user_wallet->save();
            $text = "Вы успешно создали аккаунт через VK! Для учетной записи (email: {$user->email}) был сгенерирован случайный пароль. Если входить через ВК, он не нужен. Но для возможности входа через email его необходимо сменить в настройках аккаунта.";
        } else {
            $text = "Вы успешно вошли через VK! У вас уже был аккаунт на нашем сайте. В него теперь можно входить по email или через VK.";
        }
        session()->flash('alert_text', $text);

        Auth::loginUsingId($user['id']);
        return redirect()->route('collections');
    }


    public function sign_ok()
    {
        return Socialite::driver('odnoklassniki')->redirect();
    }

    public function callback_ok()
    {
        $user = Socialite::driver('odnoklassniki')->stateless()->user();

        $user = User::firstOrCreate([
            'email' => $user->email,
            'reg_type' => 'ok',
        ], [
            'email' => $user->email,
            'name' => $user->user['first_name'],
            'reg_type' => 'ok',
            'surname' => $user->user['last_name'],
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'avatar' => $user->avatar,
            'avatar_cropped' => $user->avatar
        ]);


        $user->assignRole('user');

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Отлично!');

        if ($user->wasRecentlyCreated) {

            $user_wallet = new UserWallet;
            $user_wallet->user_id = $user['id'];
            $user_wallet->cur_amount = 0;
            $user_wallet->save();
            $text = "Вы успешно создали аккаунт через VK! Для учетной записи (email: {$user->email}) был сгенерирован случайный пароль. Если входить через ВК, он не нужен. Но для возможности входа через email его необходимо сменить в настройках аккаунта.";
        } else {
            $text = "Вы успешно вошли через VK! У вас уже был аккаунт на нашем сайте. В него теперь можно входить по email или через VK.";
        }
        session()->flash('alert_text', $text);

        Auth::loginUsingId($user['id']);
        return redirect()->route('collections');
    }

    public function sign_google()
    {
        return Socialite::driver('google')->redirect();

//        dd(env('ODNOKLASSNIKI_REDIRECT_URI'));
    }

    public function callback_google()
    {
        $user = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate([
            'email' => $user->email,
            'reg_type' => 'google'
        ], [
            'email' => $user->email,
            'name' => $user->user['given_name'],
            'surname' => $user->user['family_name'],
            'reg_type' => 'google',
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'avatar' => $user->avatar,
            'avatar_cropped' => $user->avatar
        ]);


        $user->assignRole('user');

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Отлично!');

        if ($user->wasRecentlyCreated) {

            $user_wallet = new UserWallet;
            $user_wallet->user_id = $user['id'];
            $user_wallet->cur_amount = 0;
            $user_wallet->save();
            $text = "Вы успешно создали аккаунт через Google! Для учетной записи (email: {$user->email}) был сгенерирован случайный пароль. Если входить через Google, он не нужен. Но для возможности входа через email его необходимо сменить в настройках аккаунта.";
        } else {
            $text = "Вы успешно вошли через Google! У вас уже был аккаунт на нашем сайте. В него теперь можно входить по email или через Google.";
        }
        session()->flash('alert_text', $text);

        Auth::loginUsingId($user['id']);
        return redirect()->route('collections');
    }

    public function sign_facebook()
    {
        return Socialite::driver('facebook')->redirect();

    }

    public function callback_facebook()
    {
        $user = Socialite::driver('facebook')->stateless()->user();

        dd($user);

        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'email' => $user->email,
            'name' => $user->user['given_name'],
            'surname' => $user->user['family_name'],
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'avatar' => $user->avatar,
            'avatar_cropped' => $user->avatar
        ]);


        $user->assignRole('user');

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Отлично!');

        if ($user->wasRecentlyCreated) {

            $user_wallet = new UserWallet;
            $user_wallet->user_id = $user['id'];
            $user_wallet->cur_amount = 0;
            $user_wallet->save();
            $text = "Вы успешно создали аккаунт через Google! Для учетной записи (email: {$user->email}) был сгенерирован случайный пароль. Если входить через Google, он не нужен. Но для возможности входа через email его необходимо сменить в настройках аккаунта.";
        } else {
            $text = "Вы успешно вошли через Google! У вас уже был аккаунт на нашем сайте. В него теперь можно входить по email или через Google.";
        }
        session()->flash('alert_text', $text);

        Auth::loginUsingId($user['id']);
        return redirect()->route('collections');
    }
}
