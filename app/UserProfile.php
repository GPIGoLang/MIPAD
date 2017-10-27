<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProfile extends Model
{
    protected $dates = ['dob'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mobile', 'category_id', 'fullname', 'dob', 'gender', 'age_group'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function setDobAttribute($date)
    {
        $this->attributes['dob'] = Carbon::parse($date);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}