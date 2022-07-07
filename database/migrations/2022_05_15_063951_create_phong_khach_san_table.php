<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhongKhachSanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phongKhachSan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maKS')->unsigned()->nullable(false);
            $table->foreign('maKS')->references('id')->on('khachsan');
            $table->string('maPhong');
            $table->integer('dienTich');
            $table->integer('soGiuong');
            $table->json('hinhAnh')->nullable();
            $table->integer('donGia');
            $table->enum('trangThai',['phongTrong','dangThue','chuanBi']);
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
        Schema::dropIfExists('phongKhachSan');
    }
}
