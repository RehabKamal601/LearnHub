<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::rename('sessions', 'tutoring_sessions');
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutoring_sessions', function (Blueprint $table) {
            //
        });
    }
};
