<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoiThatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noithat', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maPhong')->unsigned()->nullable(false);
            $table->foreign('maPhong')->references('id')->on('phongKhachSan');
            $table->string('tenNoiThat');
            $table->json('hinhAnh')->nullable();
            $table->integer('soLuong');
            $table->string('moTa');
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
        Schema::dropIfExists('noiThat');
    }
}
