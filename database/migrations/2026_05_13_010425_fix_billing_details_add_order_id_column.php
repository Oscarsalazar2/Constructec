<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('billing_details', function (Blueprint $table) {
            if (! Schema::hasColumn('billing_details', 'order_id')) {
                $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_details', function (Blueprint $table) {
            if (Schema::hasColumn('billing_details', 'order_id')) {
                $table->dropForeignIdFor(Order::class);
                $table->dropColumn('order_id');
            }
        });
    }
};
