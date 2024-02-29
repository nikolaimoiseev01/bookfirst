<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    public static function find($id)
    {
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function canAccessFilament(): bool
    {
        return $this->hasRole('admin');
    }

    protected $fillable = [
        'name',
        'surname',
        'nickname',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'google_id',
        'avatar_cropped',
        'reg_type',
        'reg_utm_source',
        'reg_utm_medium'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Work() {
        return $this->hasmany(Work::class);
    }

    public function UserWallet() {
        return $this->hasOne(UserWallet::class);
    }

    public function Participation() {
        return $this->hasmany(Participation::class);
    }

    public function own_book() {
        return $this->hasmany(own_book::class);
    }

    public function user_subscription() {
        return $this->hasmany(user_subscription::class);
    }

    public function work_comment() {
        return $this->hasmany(work_comment::class);
    }

    public function work_likes() {
        return $this->hasmany(work_like::class);
    }

    public function Survey() {
        return $this->hasmany(Survey::class);
    }


}
