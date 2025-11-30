<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'description',
        'preferred_time', 'budget', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

   
}
