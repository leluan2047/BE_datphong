<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('email')->unique();
            $table->string('password');
            $table->string('hoTen');
            $table->string('diaChi');
            $table->json('anhDaiDien')->nullable();
            $table->string('soDienThoai');
            $table->enum('vaiTro',['user','hotelManager','admin']);
            $table->enum('trangThai',['choDuyet','dangHoatDong','biKhoa','tuChoi']);
            $table->timestamp('email_verified_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$selVxGF1HV7RW9Fjie3A6uNEYD7DHNCoyyh9zx2eKKRQh5VfBmWAW',
                'hoTen' => 'Admin',
                'diaChi' => "khong",
                'soDienThoai' =>"0858571662",
                'vaiTro' => 'admin',
                'trangThai' =>'dangHoatDong',
                
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
