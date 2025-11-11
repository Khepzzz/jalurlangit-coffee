<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUlasansTable extends Migration
{
    public function up()
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_produk');
            $table->tinyInteger('rating');
            $table->text('komentar');
            $table->timestamp('tanggal_ulasan')->useCurrent();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ulasans');
    }
}
