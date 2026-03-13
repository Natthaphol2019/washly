<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            // เพิ่มสถานะ เปิด/ปิด (ค่าเริ่มต้นคือ true = เปิดใช้งาน)
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
