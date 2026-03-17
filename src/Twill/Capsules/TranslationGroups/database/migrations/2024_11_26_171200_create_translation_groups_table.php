<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translation_groups', function (Blueprint $table) {
            $table->id();

            $table->string('prefix');
            $table->unique('prefix');

            $table->boolean('published')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_groups');
    }
};
