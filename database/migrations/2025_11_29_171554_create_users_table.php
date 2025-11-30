<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
        $table->enum('role', ['admin', 'teacher', 'student', 'parent'])->default('student');
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // إضافة المستخدم الأدمن الافتراضي
        DB::table('users')->insert([
            'name' => 'System Admin',
            'email' => 'admin@tutoring.com',
            'password' => Hash::make('admin123'), // غيري الباسورد
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};