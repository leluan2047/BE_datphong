<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhachsanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khachsan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maTK')->unsigned()->nullable(false);
            $table->foreign('maTK')->references('id')->on('users');
            $table->string('tenKhachSan');
            $table->string('dienTich');
            $table->string('diaChi');
            $table->string('soDienThoai');
            $table->json('hinhAnh')->nullable();
            $table->float('chatLuong', 8, 2);
            $table->integer('luotDanhGia')->default(1);
            $table->longText('moTa');
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
        Schema::dropIfExists('khachsan');
    }
}
