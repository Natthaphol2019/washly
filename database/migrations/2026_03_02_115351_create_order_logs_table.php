<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // เลข Tracking
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained();
            $table->foreignId('time_slot_id')->constrained();

            $table->text('pickup_address'); // ที่อยู่ที่ให้ไปรับ (เผื่อลูกค้าส่งให้เพื่อน)
            // ในตาราง orders (เก็บที่อยู่ตอนสั่ง เผื่อลูกค้าไปอยู่หอเพื่อน)
            $table->string('pickup_latitude')->nullable();
            $table->string('pickup_longitude')->nullable();
            $table->string('pickup_map_link')->nullable();
            $table->enum('wash_temp', ['เย็น', 'อุ่น', 'ร้อน'])->nullable();
            $table->enum('dry_temp', ['อุ่น', 'ปานกลาง', 'ร้อน'])->nullable();

            // สถานะการขนส่งและทำงาน
            $table->enum('status', [
                'pending',     // รอรับผ้า
                'picked_up',   // ไรเดอร์รับผ้ามาแล้ว
                'processing',  // กำลังดำเนินการซัก/อบ
                'delivering',  // กำลังเดินทางไปส่ง
                'completed',   // ส่งคืนเรียบร้อย
                'cancelled'    // ยกเลิก
            ])->default('pending');

            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->decimal('total_price', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
