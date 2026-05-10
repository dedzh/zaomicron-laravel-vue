<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = 'FROM_CHEESECAKE_TO_SITE';

        if (! Schema::hasTable($table)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($table) {
            if (! Schema::hasColumn($table, 'IMAGE')) {
                $blueprint->string('IMAGE', 255)->nullable()->after('NAME');
            }
        });
    }

    public function down(): void
    {
        $table = 'FROM_CHEESECAKE_TO_SITE';

        if (! Schema::hasTable($table)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($table) {
            if (Schema::hasColumn($table, 'IMAGE')) {
                $blueprint->dropColumn('IMAGE');
            }
        });
    }
};
