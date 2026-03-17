<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translation_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('translation_key_id')->constrained('translation_keys')->onDelete('cascade');
            $table->string('locale', 7);
            $table->unique(['translation_key_id', 'locale']);

            $table->text('value')->nullable();

            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_values');
    }
};
