<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translation_group_locales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('translation_group_id')->constrained('translation_groups')->onDelete('cascade');
            $table->string('locale', 7);
            $table->unique(['translation_group_id', 'locale']);

            $table->string('description')->nullable();
            
            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_group_locales');
    }
};
