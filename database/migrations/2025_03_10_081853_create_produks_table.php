<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->enum('kategori', ['makanan', 'minuman']);
            $table->text('deskripsi');
            $table->integer('harga');
            $table->enum('stok', ['tersedia', 'habis']);
            $table->string('gambar_produk');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produks');
    }
}
