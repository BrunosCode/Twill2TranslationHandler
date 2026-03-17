<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('translation_values', 'active')) {
            return;
        }

        Schema::table('translation_values', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('value');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('translation_values', 'active')) {
            return;
        }

        Schema::table('translation_values', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
