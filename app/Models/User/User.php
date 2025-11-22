<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\OwnBook\OwnBook;
use App\Models\Work\Work;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasRoles, HasFactory, Notifiable, interactsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    public function participations(): HasMany
    {
        return $this->HasMany(Participation::class);
    }
    public function works(): HasMany
    {
        return $this->HasMany(Work::class);
    }
    public function awards(): HasMany
    {
        return $this->HasMany(Award::class);
    }
    public function ownBooks(): HasMany
    {
        return $this->HasMany(OwnBook::class);
    }
    public function subscribers(): HasMany
    {
        return $this->HasMany(UserXUserSubscription::class, 'subscribed_to_user_id', 'id');
    }

    function getUserFullName(): string
    {
        return $this['name'] . ' ' . $this['surname'];
    }

    function isOnline(): bool
    {
        return $this->last_seen !== null
            && $this->last_seen->gt(Carbon::now()->subMinutes(5));
    }

    public function subscribedToUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_x_user_subscriptions',
            'user_id',       // кто подписан
            'subscribed_to_user_id'     // на кого подписан
        );
    }
}
