<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // เพิ่ม picking_up เข้าไปใน ENUM
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'picking_up', 'picked_up', 'processing', 'delivering', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // ย้อนกลับเอา picking_up ออก (เพื่อความสมบูรณ์เวลา Rollback)
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'picked_up', 'processing', 'delivering', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};