<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $guarded = null;
    protected $appends = ['name'];
    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
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
