<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $table = 'categories';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}