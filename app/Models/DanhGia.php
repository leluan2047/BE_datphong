<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;
    public $table = "danhgia";
    protected $fillable = [
        'maTK',
        'maPhong',
        'noiDung',
        'ngayDanhGia'
    ];
}
