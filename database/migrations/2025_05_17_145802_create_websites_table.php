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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->unique();
            $table->string('api_key', 64)->unique();
            $table->string('secret_key', 128)->unique();
            $table->boolean('is_active')->default(true);
            $table->string('contact_email')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Pivot table for user-website relationship
        Schema::create('website_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['website_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_user');
        Schema::dropIfExists('websites');
    }
};
