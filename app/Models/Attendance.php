<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];
    protected $appends = [ 'receipt'];
  
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
}
