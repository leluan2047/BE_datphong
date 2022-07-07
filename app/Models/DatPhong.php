<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPhong extends Model
{
    use HasFactory;
    public $table = "datphong";
    protected $fillable = [
        'maTK',
        'maPhong',
        'ngayNhan',
        'ngayTra',
        'tongTien',
        'trangThai'
    ];
}
