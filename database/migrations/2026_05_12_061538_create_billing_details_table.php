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
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('rfc', 20);
            $table->string('business_name');
            $table->string('tax_regime', 120);
            $table->string('postal_code', 20);
            $table->string('fiscal_address')->nullable();
            $table->string('cfdi_usage', 20)->nullable();
            $table->string('invoice_pdf_path')->nullable();
            $table->string('invoice_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_details');
    }
};
