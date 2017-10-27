<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    protected $fillable = [
        'nominator_id',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'code',
        'status'
    ];

    protected $table = 'nominations';

    public function assessments() {
        return $this->hasMany(UserTest::class);
    }
}
