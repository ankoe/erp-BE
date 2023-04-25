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
        Schema::create('purchase_request_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_request_id');
            $table->integer('order');
            $table->integer('role_id');
            $table->integer('approve_user_id')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->string('approve_status', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_approvals');
    }
};
