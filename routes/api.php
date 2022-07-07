<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\KhachSanController;
use App\Http\Controllers\PhongKhachSanController;
use App\Http\Controllers\NoiThatController;
use App\Http\Controllers\DatPhongController;;
use App\Http\Controllers\DanhGiaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',[AuthController::class,'register']);
Route::get('/account/show/{id}',[AuthController::class,'show']);
Route::post('/login',[AuthController::class,'login']);

Route::get('/hotel',[KhachSanController::class,'index']);

Route::get('/room',[PhongKhachSanController::class,'index']);

Route::get('/service',[DichVuController::class,'index']);

Route::get('/furniture',[NoiThatController::class,'index']);

Route::get('/danhGia',[DanhGiaController::class,'index']);

//-----------------------------------------------------------------
Route::post('/hotel/search',[KhachSanController::class,'search']);

Route::get('/hotelManager/show/{id}',[KhachSanController::class,'show']);

Route::get('/hotelManager/serviceForHotel/{id}',[DichVuController::class,'serviceForHotel']);

Route::get('/hotelManager/roomForHotel/{id}',[PhongKhachSanController::class,'roomForHotel']);

Route::get('/hotelManager/room/show/{id}',[PhongKhachSanController::class,'show']);

Route::get('/hotelManager/furnitureForRoom/{id}',[NoiThatController::class,'furnitureForRoom']);

Route::get('/datPhong/thanhToanThanhCong',[DatPhongController::class,'thanhToanThanhCong']);


//-----------------------------------------------------------------

// Route::get('/dichvu',[DichVuController::class,'destroy']);







Route::group(['middleware'=>['auth:sanctum']],function(){
    
    Route::get('/logout',[AuthController::class,'logout']);
    Route::post('/account/update/{id}',[AuthController::class,'update']);

    
    Route::get('/hotelManager/Myhotel',[KhachSanController::class,'getMyHotel']);
  
    Route::post('/hotelManager/update/{id}',[KhachSanController::class,'update']);
    
    Route::get('/hotelManager/destroy/{id}',[KhachSanController::class,'destroy']);
    
    Route::post('/hotelManager/addHotel',[KhachSanController::class,'store']);

    //-------------------------------------------------
    Route::get('/hotelManager/myService',[DichVuController::class,'getMyService']);
    Route::post('/hotelManager/service/add',[DichVuController::class,'store']);
    Route::post('/hotelManager/service/update/{id}',[DichVuController::class,'update']);
    Route::get('/hotelManager/service/show/{id}',[DichVuController::class,'show']);
    Route::get('/hotelManager/service/destroy/{id}',[DichVuController::class,'destroy']);
    // update dich vu thi ID la dich vu

    Route::get('/hotelManager/myRoom',[PhongKhachSanController::class,'getMyRoom']);
    Route::post('/hotelManager/room/add',[PhongKhachSanController::class,'store']);
    
    Route::post('/hotelManager/room/update/{id}',[PhongKhachSanController::class,'update']);
    Route::get('/hotelManager/room/destroy/{id}',[PhongKhachSanController::class,'destroy']);

    

    //-----------------------------------------------------------------
    Route::get('/hotelManager/myFurniture',[NoiThatController::class,'getMyFurniture']);
    Route::post('/hotelManager/furniture/add',[NoiThatController::class,'store']);
    Route::get('/hotelManager/furniture/show/{id}',[NoiThatController::class,'show']);
    Route::post('/hotelManager/furniture/update/{id}',[NoiThatController::class,'update']);
    Route::get('/hotelManager/furniture/destroy/{id}',[NoiThatController::class,'destroy']);

//---------------------------------------------------------------------------------
    Route::get('/admin/xetDuyet',[AuthController::class,'danhSachXetDuyet']);
    Route::get('/admin/account',[AuthController::class,'getAllAccount']);
    Route::get('/admin/accountBlocked',[AuthController::class,'getAllBlocked']);
    Route::get('/admin/accountFefuse',[AuthController::class,'getAllRefuse']);

    
    Route::get('/admin/khoaTK/{id}',[AuthController::class,'khoaTK']);
    Route::get('/admin/moKhoa/{id}',[AuthController::class,'moKhoa']);
    Route::get('/admin/xetDuyet/ok/{id}',[AuthController::class,'dongY']);
    Route::get('/admin/xetDuyet/no/{id}',[AuthController::class,'tuChoi']);
//-----------------------------------------------------------------------------------
    Route::post('/datPhong',[DatPhongController::class,'store']);
    Route::get('/datPhong/history',[DatPhongController::class,'myHistory']);
    Route::get('/datPhong/huy/{id}',[DatPhongController::class,'huyDat']);

    Route::get('/datPhong/request',[DatPhongController::class,'request']);
    Route::get('/datPhong/agree/{id}',[DatPhongController::class,'agree']);
    Route::get('/datPhong/deny/{id}',[DatPhongController::class,'deny']);
    Route::get('/datPhong/historyOfHotelDir',[DatPhongController::class,'historyOfHotelDirector']);
    Route::get('/datPhong/danhSachNo',[DatPhongController::class,'danhSachNo']);
    Route::post('/datPhong/phongTrongThuocKhachSan/{id}',[DatPhongController::class,'phongTrongThuocKhachSan']);
    Route::post('/datPhong/thanhToan',[DatPhongController::class,'thanhToan']);
    Route::post('/datPhong/thanhToanThuCong',[DatPhongController::class,'thanhToanThuCong']);
   


    //---------------------------------------------------------------------------------
    Route::post('/danhGia',[DanhGiaController::class,'store']);
    Route::post('/danhGia/sua/{id}',[DanhGiaController::class,'update']);
    Route::get('/danhGia/destroy/{id}',[DanhGiaController::class,'destroy']);

    
    Route::get('/danhGia/myHistory',[DanhGiaController::class,'myHistory']);
    Route::get('/danhGia/danhGiaKhachSan/{id}',[DanhGiaController::class,'danhGiaVeKS']);
    Route::get('/danhGia/toanBoDanhGiaVeQuanLy',[DanhGiaController::class,'toanBoDanhGiaVeQuanLy']);
    //------------------------------------------------------------------------------------------

});

