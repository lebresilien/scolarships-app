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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->string('fname');
            $table->string('lname')->nullable();
            $table->string('sexe');
            $table->string('born_place')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name');
            $table->string('fphone')->nullable();
            $table->string('mphone');
            $table->date('born_at');
            $table->text('allergy')->nullable();
            $table->string('logo')->default('anonymous.png');
            $table->text('description')->nullable();
            $table->string('quarter')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
