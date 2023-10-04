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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ip')->nullable()->after('email');
            /*$table->string('phone', 50)->after('name')->unique();
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->unsignedTinyInteger('confirmation_phone')->default('0')->after('email_verified_at');
            $table->unsignedTinyInteger('confirmation_email')->default('0')->after('confirmation_phone');
            $table->timestamp('date_of_birth')->nullable()->after('ip');*/
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ip');
            /*$table->dropColumn('phone');
            $table->dropColumn('phone_verified_at');
            $table->dropColumn('confirmation_phone');
            $table->dropColumn('confirmation_email');*/
            $table->dropSoftDeletes();
        });
    }
};
