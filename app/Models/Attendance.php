<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id',
        'order_number',
        'paid_amount',
        'status',
        'merchant_order_number',
        'payment_phone_number',
    ];
    protected $appends = ['receipt'];

    public function getReceiptAttribute()
    {
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    function paymentAttempts() : HasMany {
        return $this->hasMany(PaymentAttempt::class);
    }
}
