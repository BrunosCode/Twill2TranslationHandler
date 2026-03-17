<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('translation_keys', 'published')) {
            return;
        }

        Schema::table('translation_keys', function (Blueprint $table) {
            $table->boolean('published')->default(true)->after('key');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('translation_keys', 'published')) {
            return;
        }

        Schema::table('translation_keys', function (Blueprint $table) {
            $table->dropColumn('published');
        });
    }
};
