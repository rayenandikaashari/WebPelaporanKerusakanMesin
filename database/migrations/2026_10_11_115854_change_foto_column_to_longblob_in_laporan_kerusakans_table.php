<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE laporan_kerusakans MODIFY foto LONGBLOB NULL'
        );
    }

    public function down(): void
    {
        DB::statement(
            'ALTER TABLE laporan_kerusakans MODIFY foto VARCHAR(255) NULL'
        );
    }
};
