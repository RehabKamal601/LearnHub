<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TeacherProfile;

class Session extends Model
{
    protected $fillable = [
        'teacher_id', 'student_id', 'post_id',
        'date', 'duration', 'status', 'paid'
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
