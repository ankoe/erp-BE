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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->string('name', 50);
            $table->string('email', 40)->unique();
            $table->string('mobile', 15)->nullable();
            $table->string('email_proof_token', 50)->nullable();
            $table->timestamp('email_proof_token_expires_at')->nullable();
            $table->string('password')->nullable();
            $table->string('password_proof_token', 80)->nullable();
            $table->timestamp('password_proof_token_expires_at')->nullable();
            $table->string('image_profile', 100)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
