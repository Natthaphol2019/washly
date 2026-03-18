<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_logs', function (Blueprint $table) {
            // เพิ่มคอลัมน์ note ต่อท้ายคอลัมน์ new_status (ให้ว่างได้ nullable)
            $table->string('note', 1000)->nullable()->after('new_status');
        });
    }

    public function down()
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};