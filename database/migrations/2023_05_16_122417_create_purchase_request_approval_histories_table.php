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
        Schema::create('purchase_request_approval_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_request_id');
            $table->integer('role_id');
            $table->integer('user_id');
            $table->datetime('approved_at');
            $table->string('approve_status', 10);
            $table->string('remarks', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_approval_histories');
    }
};
