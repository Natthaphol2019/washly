<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // เพิ่มคอลัมน์ driver_id ไว้ต่อจาก user_id
            $table->unsignedBigInteger('driver_id')->nullable()->after('user_id');
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('driver_id');
        });
    }
};