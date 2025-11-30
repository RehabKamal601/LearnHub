<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'subjects', 'education_levels',
        'experience_years', 'hourly_rate', 'location',
        'rating', 'total_reviews', 'is_verified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'teacher_id');
    }
}
