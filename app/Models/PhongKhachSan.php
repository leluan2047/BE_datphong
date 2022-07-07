<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongKhachSan extends Model
{
    use HasFactory;
    public $table = "phongkhachsan";
    protected $fillable = [
        'maKS',
        'maPhong',
        'soGiuong',
        'dienTich',
        'hinhAnh',
        'donGia',
        'trangThai'
    ];
}
