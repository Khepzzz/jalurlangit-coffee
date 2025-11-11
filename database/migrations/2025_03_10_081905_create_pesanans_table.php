<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanansTable extends Migration
{
    public function up()
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->timestamp('tanggal_pesanan')->useCurrent();
            $table->integer('total_harga');
            $table->enum('status_pesanan', ['pending', 'diproses', 'selesai', 'dibatalkan']);

            // Foreign Keys
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesanans');
    }
}
