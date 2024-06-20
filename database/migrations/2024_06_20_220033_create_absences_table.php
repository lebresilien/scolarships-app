<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->contrained()->onDelete('cascade');
            $table->foreignId('course_id')->contrained()->onDelete('cascade');
            $table->datetime('day');
            $table->integer('justify_hour')->default(0);
            $table->text('justify')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absents');
    }
};
