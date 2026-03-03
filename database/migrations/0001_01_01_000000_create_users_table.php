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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname'); // ชื่อ-นามสกุลจริง
            $table->string('username')->unique(); // ใช้ Login
            $table->string('password');
            $table->string('phone')->nullable();
            $table->text('address')->nullable(); // สำคัญมากสำหรับไรเดอร์ไปรับผ้า
            // ในตาราง users (เก็บที่อยู่ประจำ)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('map_link')->nullable(); // เผื่อให้ลูกค้าแปะลิงก์
            $table->enum('role', ['admin', 'customer'])->default('customer');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
