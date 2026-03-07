<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['transfer', 'cash'])->default('transfer')->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'use_customer_detergent')) {
                $table->boolean('use_customer_detergent')->default(false)->after('pickup_map_link');
            }

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 8, 2)->default(0)->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'addon_total')) {
                $table->decimal('addon_total', 8, 2)->default(0)->after('subtotal');
            }

            if (!Schema::hasColumn('orders', 'selected_addons')) {
                $table->json('selected_addons')->nullable()->after('addon_total');
            }
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'pending_cash', 'reviewing', 'paid') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'reviewing', 'paid') DEFAULT 'unpaid'");

        Schema::table('orders', function (Blueprint $table) {
            $drops = [];

            foreach (['payment_method', 'use_customer_detergent', 'subtotal', 'addon_total', 'selected_addons'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $drops[] = $column;
                }
            }

            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};
