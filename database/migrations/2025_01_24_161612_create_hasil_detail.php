<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hasil_id')->unsigned();
            $table->integer('gejala_id')->unsigned();
            $table->double('densitas')->notnull()->default(0);
            $table->timestamps();
            $table->foreign('hasil_id')->references('id')->on('hasil');
            $table->foreign('gejala_id')->references('id')->on('gejala');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_detail');
    }
};
