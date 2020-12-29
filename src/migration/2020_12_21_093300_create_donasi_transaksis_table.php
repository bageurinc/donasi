<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonasiTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgr_yayasan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->integer('donasi_id')->unsigned()->nullable();
            $table->jsonb('raw_data')->nullable();
            $table->jsonb('payment_gateway')->nullable();
            $table->double('nominal');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bgr_yayasan_transaksi');
    }
}
