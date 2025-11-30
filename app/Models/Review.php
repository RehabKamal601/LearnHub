<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'session_id',
        'rating',
        'comment'
    ];

    /**
     * العلاقة مع المعلم
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * العلاقة مع الطالب
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * العلاقة مع الجلسة
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    // في app/Models/Review.php
protected static function booted()
{
    static::saved(function ($review) {
        // تحديث متوسط التقييم وعدد التقييمات في الملف الشخصي للمعلم
        $teacher = $review->teacher;
        $averageRating = $teacher->teacherReviews()->avg('rating');
        $reviewsCount = $teacher->teacherReviews()->count();
        
        if ($teacher->teacherProfile) {
            $teacher->teacherProfile->update([
                'rating' => $averageRating,
                'total_reviews' => $reviewsCount
            ]);
        }
    });
}

    /**
     * التحقق من أن التقييم بين 1 و 5
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, $value));
    }
}