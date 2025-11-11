<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pesanan'); // Foreign key ke tabel pesanan
            $table->timestamp('tanggal_pembayaran');
            $table->enum('metode_pembayaran', ['QR', 'VA', 'DANA', 'GoPay']);
            $table->decimal('jumlah', 10, 2);  // Jumlah yang dibayar
            $table->enum('status_pembayaran', ['pending', 'proses', 'dibayar'])->default('pending');  // Status pembayaran
            $table->string('bukti_pembayaran')->nullable();

            // Foreign key untuk pesanan
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}
