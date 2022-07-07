<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    use HasFactory;
    public $table = "DichVu";
    protected $fillable = [
        'maKS',
        'tenDichVu',
        'hinhAnh',
        'moTa'
    ];
}
