<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 🌟 เพิ่มคอลัมน์ delivery_type ค่าเริ่มต้นคือ รับ-ส่งถึงที่
            $table->string('delivery_type')->default('door_to_door')->after('time_slot_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });
    }
};