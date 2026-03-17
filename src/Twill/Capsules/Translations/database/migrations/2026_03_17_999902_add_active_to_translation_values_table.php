<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('translation_values')) {
            Schema::create('translation_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('translation_key_id')
                    ->constrained('translation_keys')
                    ->onDelete('cascade');
                $table->string('locale', 7);
                $table->text('value')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['translation_key_id', 'locale']);
            });

            return;
        }

        if (! Schema::hasColumn('translation_values', 'active')) {
            Schema::table('translation_values', function (Blueprint $table) {
                $table->boolean('active')->default(true)->after('value');
            });
        }
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
