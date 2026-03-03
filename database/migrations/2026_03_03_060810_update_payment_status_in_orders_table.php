<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // อย่าลืมใส่บรรทัดนี้ด้วยนะครับ

return new class extends Migration
{
    public function up(): void
    {
        // ใช้คำสั่ง SQL ตรงๆ เพื่อเปลี่ยนชนิดของ ENUM ให้เพิ่มคำว่า 'reviewing' เข้าไป
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'reviewing', 'paid') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        // ถ้าย้อนกลับ (rollback) ก็ให้เหลือแค่ unpaid กับ paid เหมือนเดิม
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid'");
    }
};