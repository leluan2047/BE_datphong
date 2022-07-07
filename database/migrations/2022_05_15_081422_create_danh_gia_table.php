<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhGiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhgia', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maTK')->unsigned()->nullable(false);
            $table->foreign('maTK')->references('id')->on('users');
            $table->bigInteger('maPhong')->unsigned()->nullable(false);
            $table->foreign('maPhong')->references('id')->on('phongKhachSan');
            $table->string('noiDung');
            $table->date('ngayDanhGia');
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
        Schema::dropIfExists('danhGia');
    }
}
