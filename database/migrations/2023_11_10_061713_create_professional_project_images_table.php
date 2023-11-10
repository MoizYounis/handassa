<?php

use App\Models\User;
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
        Schema::create('professional_project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'professional_id');
            $table->string('image');
            $table->timestamps();
            $table->foreign('professional_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_project_images');
    }
};
