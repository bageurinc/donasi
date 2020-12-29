<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Campaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgr_yayasan_campaign', function (Blueprint $table) {
            $table->id();
            $table->integer('penerima_id')->unsigned()->nullable();
            $table->integer('lembaga_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('title_seo');
            $table->string('sub')->nullable();
            $table->longText('description');
            $table->integer('target_dana');
            $table->string('gambar')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('nama_penerima')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('bgr_yayasan_lembaga', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_seo');
            $table->longText('description');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('bgr_yayasan_campaign_donasi', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable();
            $table->json('donatur')->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->double('nominal')->nullable();
            $table->longText('pesan')->nullable();
            $table->boolean('anonim')->default('false');
            $table->string('status')->nullable()->default('aktif');
            $table->timestamps();
        });

        Schema::create('bgr_yayasan_campaign_aktifitas', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->integer('lembaga_id')->unsigned()->nullable();
            $table->string('nama');
            $table->string('nama_seo');
            $table->longText('description')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // Schema::create('bgr_yayasan_members', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('referals')->nullable();
        //     $table->integer('user_id')->unsigned()->nullable();
        //     $table->integer('campaign_id')->unsigned()->nullable();
        //     $table->string('status')->default('aktif');
        //     $table->timestamps();
        // });

        Schema::create('bgr_yayasan_penerima', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alasan');
            $table->string('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kodepos');
            $table->string('no_telp');
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('bgr_yayasan_campaign');
        Schema::dropIfExists('bgr_yayasan_lembaga');
        Schema::dropIfExists('bgr_yayasan_donatur');
        Schema::dropIfExists('bgr_yayasan_aktifitas');
        Schema::dropIfExists('bgr_yayasan_members');
    }
}
