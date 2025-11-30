<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->integer('rating'); // من 1 إلى 5
            $table->text('comment'); // هذا هو الكومنت
            $table->timestamps();
            
            // لمنع تقييم متكرر لنفس الجلسة
            $table->unique(['student_id', 'session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};