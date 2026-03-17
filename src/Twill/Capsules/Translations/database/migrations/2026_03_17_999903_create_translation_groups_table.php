<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('translation_groups')) {
            return;
        }

        Schema::create('translation_groups', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->unique();
            $table->boolean('published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_groups');
    }
};
