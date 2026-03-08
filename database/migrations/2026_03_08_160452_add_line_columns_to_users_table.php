<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // เพิ่มคอลัมน์ใหม่
            $table->string('line_id')->nullable()->unique()->after('id');
            $table->string('avatar')->nullable()->after('username');
            
            // แก้ให้รหัสผ่านเว้นว่างได้ (เพราะคนที่ล็อกอินผ่าน LINE จะไม่มีรหัสผ่าน)
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['line_id', 'avatar']);
            $table->string('password')->nullable(false)->change();
        });
    }
};