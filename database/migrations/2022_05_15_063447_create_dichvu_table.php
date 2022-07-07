<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDichvuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dichvu', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('maKS')->unsigned()->nullable(false);
            $table->foreign('maKS')->references('id')->on('khachsan');
            $table->string('tenDichVu');
            $table->json('hinhAnh')->nullable();
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
        Schema::dropIfExists('dichvu');
    }
}
