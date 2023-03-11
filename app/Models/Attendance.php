<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Attendance extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $guarded = ['id', 'receipt_file'];
    protected $appends = ['name', 'receipt'];
    public function getNameAttribute()
    {
        return $this->title . ' ' . $this->first_name . ' ' . $this->last_name;
    }
    public function getReceiptAttribute()
    {
        return $this->hasMedia('receipts') ? $this->getFirstMediaUrl('receipts') : null;
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
