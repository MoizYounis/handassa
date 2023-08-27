<?php

use App\Helpers\Constant;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name');
            $table->string('email')->nullable(true)->change();
            $table->string('password')->nullable(true)->change();
            $table->string('mobile_number')->after('username');
            $table->string('phone_number')->nullable()->after('mobile_number');
            $table->string('image')->nullable()->after('phone_number');
            $table->string('cr_copy')->nullable()->after('image');
            $table->string('id_copy')->nullable()->after('cr_copy');
            $table->string('location')->after('id_copy')->nullable();
            $table->string('latitude')->nullable()->after('location');
            $table->string('longitude')->nullable()->after('latitude');
            $table->enum('role', [Constant::CLIENT, Constant::PROFESSIONAL, Constant::ADMIN])->nullable()->after('longitude');
            $table->enum('type', [Constant::PERSON, Constant::FREELANCER, Constant::COMPANY, Constant::ADMIN])->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->dropColumn('mobile_number');
            $table->dropColumn('phone_number');
            $table->dropColumn('image');
            $table->dropColumn('cr_copy');
            $table->dropColumn('id_copy');
            $table->dropColumn('location');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('role');
            $table->dropColumn('type');
        });
    }
};
