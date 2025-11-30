<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // عرض صفحة التسجيل
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // معالجة التسجيل
    public function register(Request $request)
    {
        try {
            // تحديد قواعد الفاليديشن بناءً على الدور
            $rules = $request->role === 'teacher' 
                ? User::teacherRegisterRules() 
                : User::studentRegisterRules();

            // الفاليديشن
            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
                'password.min' => 'كلمة المرور يجب أن تكون至少 8 أحرف',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
                'role.in' => 'الدور يجب أن يكون either طالب أو معلم',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // بدء المعاملة
            return DB::transaction(function () use ($request) {
                // إنشاء المستخدم
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'phone' => $request->phone,
                ]);

                // إنشاء البروفايل بناءً على الدور
                if ($request->role === 'student') {
                    $this->createStudentProfile($user, $request);
                } else {
                    $this->createTeacherProfile($user, $request);
                }

                // تسجيل الدخول تلقائياً
                auth()->login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'تم التسجيل بنجاح!',
                    'redirect' => $request->role === 'student' ? '/student/dashboard' : '/teacher/dashboard'
                ]);

            });

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'يوجد أخطاء في البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()
            ], 500);
        }
    }

    // إنشاء بروفايل الطالب
    private function createStudentProfile(User $user, Request $request)
    {
        Student::create([
            'user_id' => $user->id,
            'grade' => $request->grade,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'address' => $request->address,
        ]);
    }

    // إنشاء بروفايل المعلم
    private function createTeacherProfile(User $user, Request $request)
    {
        TeacherProfile::create([
            'user_id' => $user->id,
            'bio' => $request->bio,
            'subjects' => $request->subjects,
            'education_levels' => $request->education_levels,
            'experience_years' => $request->experience_years,
            'hourly_rate' => $request->hourly_rate,
            'location' => $request->location,
            'rating' => 0,
            'total_reviews' => 0,
            'is_verified' => false,
        ]);
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}