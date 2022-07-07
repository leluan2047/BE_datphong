<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatPhongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datphong', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maTK')->unsigned()->nullable(false);
            $table->foreign('maTK')->references('id')->on('users');
            $table->bigInteger('maPhong')->unsigned()->nullable(false);
            $table->foreign('maPhong')->references('id')->on('phongkhachsan');
            $table->datetime('ngayNhan');
            $table->datetime('ngayTra');
            $table->integer('tongTien');
            $table->enum('trangThai',['xetDuyet','chuaThanhToan','daThanhToan','hoanTat','daHuy']);
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
        Schema::dropIfExists('datPhong');
    }
}
