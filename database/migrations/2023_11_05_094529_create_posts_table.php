<?php

use App\Helpers\Constant;
use App\Models\Category;
use App\Models\Service;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'client_id');
            $table->foreignIdFor(User::class, 'professional_id')->nullable();
            $table->foreignIdFor(Service::class, 'service_id');
            $table->foreignIdFor(Category::class, 'category_id');
            $table->string('title');
            $table->text('description');
            $table->string('job')->nullable();
            $table->enum('status', [Constant::NEW, Constant::ACCEPTED, Constant::PROCESSING, Constant::COMPLETED, Constant::ENDED])->default(Constant::NEW);
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('professional_id')->references('id')->on('users');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
