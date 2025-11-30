<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TeacherProfile;

class Promotion extends Model
{
    protected $fillable = [
        'teacher_id', 'admin_id', 'title',
        'discount_percentage', 'start_date', 'end_date'
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
