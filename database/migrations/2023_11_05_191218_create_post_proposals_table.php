<?php

use App\Helpers\Constant;
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
        Schema::create('post_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class, 'post_id');
            $table->foreignIdFor(User::class, 'professional_id');
            $table->double('price', 8, 2);
            $table->text('description');
            $table->enum('status', [Constant::PENDING, Constant::ACCEPTED, Constant::REJECTED])->default(Constant::PENDING);
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('professional_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_proposals');
    }
};
