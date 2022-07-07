<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachSan extends Model
{
    use HasFactory;
    public $table = "khachsan";
    protected $fillable = [
        'maTK',
        'tenKhachSan',
        'diaChi',
        'dienTich',
        'soDienThoai',
        'hinhAnh',
        'chatLuong',
        'luotDanhGia',
        'moTa',
    ];
}
