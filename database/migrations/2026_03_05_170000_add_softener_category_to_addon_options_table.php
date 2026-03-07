<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE addon_options MODIFY category ENUM('detergent','softener','service') NOT NULL DEFAULT 'service'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE addon_options MODIFY category ENUM('detergent','service') NOT NULL DEFAULT 'service'");
    }
};
