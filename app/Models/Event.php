<?php

namespace App\Models;

use App\Enums\EventStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'year',
        'amount',
        'event_date'
    ];

    protected $casts = [
        'amount' => 'double',
        'status'=> EventStatus::class
    ];

    protected $appends  = ['attendeesCount','statusLabel'];

    function getAttendeesCountAttribute(): int
    {
        return $this->attendencies()->count();
    }

    function getEventDateAttribute($value): string
    {
        return Carbon::parse($value)->format('D d M y');
    }

    function getStatusLabelAttribute($value): string
    {
        return $this->status->getLabel();
    }

    function attendencies() {
        return $this->hasMany(Attendance::class);
    }

    public function attendance()
    {
        return Attendance::where(['event_id'=> $this->id, 'user_id' => auth()->id()])->first();
    }

}
