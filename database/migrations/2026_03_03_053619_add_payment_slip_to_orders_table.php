<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // สร้างคอลัมน์เก็บชื่อไฟล์รูปสลิป (ให้ว่างได้ เผื่อลูกค้ายังไม่จ่าย)
            $table->string('payment_slip')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_slip');
        });
    }
};