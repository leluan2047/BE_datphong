<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoiThat extends Model
{
    use HasFactory;
    public $table = "noithat";
    protected $fillable = [
        'maPhong',
        'tenNoiThat',
        'hinhAnh',
        'soLuong',
        'moTa'
    ];
}
