<?php

use App\Models\Post;
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
        Schema::create('professional_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class, 'post_id');
            $table->foreignIdFor(User::class, 'professional_id');
            $table->foreignIdFor(User::class, 'client_id');
            $table->double('rating', 2, 1);
            $table->text('review');
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('professional_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_ratings');
    }
};
