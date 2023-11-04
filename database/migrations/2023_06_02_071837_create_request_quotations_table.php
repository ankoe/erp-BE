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
        Schema::create('request_quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('purchase_request_item_id');
            $table->unsignedBigInteger('vendor_id');
            $table->integer('vendor_price')->nullable();
            $table->integer('vendor_stock')->nullable();
            $table->string('vendor_incoterms', 100)->nullable();
            $table->boolean('vendor_is_agree')->nullable();
            $table->boolean('is_selected')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_quotations');
    }
};
