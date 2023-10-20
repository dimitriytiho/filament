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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_name_id');
            $table->foreign('menu_name_id')
                ->references('id')
                ->on('menu_names')
                ->cascadeOnDelete();

            $table->string('title')->nullable()->index();
            $table->string('link')->nullable()->index();
            $table->string('image')->nullable();
            $table->string('item')->nullable();
            $table->string('class')->nullable();
            $table->string('target')->nullable();
            $table->json('attrs')->nullable();
            $table->text('content')->nullable();
            $table->boolean('active')->default(false)->index();
            $table->unsignedSmallInteger('sort')->default('5000')->nullable()->comment('max 65535');
            $table->timestamps();

            // For lazychaser/laravel-nestedset
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('_lft')->default('0')->index();
            $table->unsignedBigInteger('_rgt')->default('0')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
