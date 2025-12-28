<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function callback($provider)
    {
        return DB::transaction(function () use ($provider) {

            [$name, $surname, $email] = match ($provider) {
                'vkontakte' => ['first_name', 'last_name', 'email'],
                'google' => ['given_name', 'family_name', 'email'],
                'yandex' => ['first_name', 'last_name', 'default_email'],
                default => [null, null],
            };

            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = User::firstOrCreate([
                'email' => $socialUser->user[$email],
            ], [
                'email' => $socialUser->user[$email],
                'name' => $socialUser->user[$name],
                'surname' => $socialUser->user[$surname],
                'reg_type' => $provider,
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => Carbon::now()->toDateTimeString()
            ]);
            if ($socialUser->avatar && $user->wasRecentlyCreated) {
                try {
                    $user->addMediaFromUrl($socialUser->avatar)
                        ->toMediaCollection('avatar');
                } catch (\Throwable $e) {
                }
            }

            $user->assignRole('user');

            $alertText = match ($user->wasRecentlyCreated) {
                true => "Вы успешно создали аккаунт через {$provider}! Для учетной записи (email: {$user->email}) был сгенерирован случайный пароль. Если входить через {$provider}, он не нужен. Но для возможности входа через email его необходимо сменить в настройках аккаунта.",
                false => "Вы успешно вошли через {$provider}! У вас уже был аккаунт на нашем сайте. В него теперь можно входить по email или через {$provider}."
            };
            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alertText
            ]);

            Auth::loginUsingId($user['id']);
            return redirect()->route('account.participations');
        });
    }
}
