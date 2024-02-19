<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'phone_number',
        'region_id',
        'district_id',
        'institution',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'name'
    ];

    function getNameAttribute(): string
    {
        return str($this->title . ' ' . $this->first_name . ' ' . $this->last_name)->lower()->headline();
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function attendence()
    {
        return $this->hasOne(Attendance::class)->where(['event_id'=>Event::where('status', 1)->first()?->id, 'user_id' => auth()->id()]);
    }
}
