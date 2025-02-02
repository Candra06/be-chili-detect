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
        Schema::create('hasil', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->enum('is_valid', ['Y','N']);
            $table->integer('penyakit_id_result')->unsigned();
            $table->integer('penyakit_id_recommended')->unsigned();
            $table->string('created_by');
            $table->text('keterangan');
            $table->timestamps();
            $table->foreign('penyakit_id_result')->references('id')->on('penyakit');
            $table->foreign('penyakit_id_recommended')->references('id')->on('penyakit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil');
    }
};
