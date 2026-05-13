<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }

            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_phone');
            }

            if (! Schema::hasColumn('orders', 'customer_address')) {
                $table->string('customer_address')->nullable()->after('customer_email');
            }

            if (! Schema::hasColumn('orders', 'customer_city')) {
                $table->string('customer_city')->nullable()->after('customer_address');
            }

            if (! Schema::hasColumn('orders', 'customer_state')) {
                $table->string('customer_state')->nullable()->after('customer_city');
            }

            if (! Schema::hasColumn('orders', 'customer_postal_code')) {
                $table->string('customer_postal_code', 20)->nullable()->after('customer_state');
            }

            if (! Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('customer_postal_code');
            }

            if (! Schema::hasColumn('orders', 'discount_id')) {
                $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete()->after('subtotal');
            }

            if (! Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_id');
            }

            if (! Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->dropConstrainedForeignId('discount_id');
                $table->dropColumn('discount_amount');
            }

            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }

            if (Schema::hasColumn('orders', 'customer_postal_code')) {
                $table->dropColumn('customer_postal_code');
            }

            if (Schema::hasColumn('orders', 'customer_state')) {
                $table->dropColumn('customer_state');
            }

            if (Schema::hasColumn('orders', 'customer_city')) {
                $table->dropColumn('customer_city');
            }

            if (Schema::hasColumn('orders', 'customer_address')) {
                $table->dropColumn('customer_address');
            }

            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropColumn('customer_email');
            }

            if (Schema::hasColumn('orders', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }

            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }

            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropColumn('order_number');
            }
        });
    }
};
