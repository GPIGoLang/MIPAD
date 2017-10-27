<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    protected $table = 'user_tests';

    protected $fillable = [
        'user_id', 'title','test_id','username','status','access_token', 'test_link',
        'score', 'cut_off', 'result', 'resport_link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
