<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','staff','customer') NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        // Normalize unsupported role before rolling back enum values.
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'staff'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','customer') NOT NULL DEFAULT 'customer'");
    }
};
