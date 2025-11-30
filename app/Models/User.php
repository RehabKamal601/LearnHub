<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
 // قواعد الفاليديشن للتسجيل
    public static function registerRules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'role' => ['required', Rule::in(['student', 'teacher'])],
            'phone' => 'required|string|max:20',
        ];
    }

    // قواعد الفاليديشن للتسجيل كطالب
    public static function studentRegisterRules()
    {
        return array_merge(self::registerRules(), [
            'grade' => 'required|string|max:50',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
    }

     // قواعد الفاليديشن للتسجيل كمعلم
    public static function teacherRegisterRules()
    {
        return array_merge(self::registerRules(), [
            'bio' => 'required|string|max:1000',
            'subjects' => 'required|string|max:255',
            'education_levels' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
        ]);
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function studentpost()
    {
        return $this->hasMany(Post::class, 'student_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'admin_id');
    }
}
