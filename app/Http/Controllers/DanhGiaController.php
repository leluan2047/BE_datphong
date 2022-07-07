<?php

namespace App\Http\Controllers;

use App\Models\KhachSan;
use App\Models\PhongKhachSan;
use App\Models\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DanhGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('danhgia')
        ->join('phongkhachsan','danhgia.maPhong', '=','phongkhachsan.id') 
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->join('users','danhgia.maTK','=','users.id')
        
        ->select('danhgia.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.email','users.anhDaiDien')
        ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['maTK'] = $request->user()->id;

        $danhGiaTrung = DB::table('danhgia')
                            ->where('maTK',$request->user()->id)
                            ->where('maPhong', $data['maPhong'])
                            ->where('ngayDanhGia',$data['ngayDanhGia'])
                            ->get();
        
        if(count($danhGiaTrung)){
            return "Ban da danh gia roi";
        }
        else{
            if(DanhGia::create($data)){

                $thongSo = DB::table('phongkhachsan')
                ->join('khachsan','phongkhachsan.maKS', '=','khachsan.id') 
                ->where('phongkhachsan.id',$data['maPhong'])
                ->select('khachsan.luotDanhGia','khachsan.chatLuong','khachsan.id')
                ->get();
    
                $trungBinh = ($thongSo[0]->luotDanhGia*$thongSo[0]->chatLuong + $data['chatLuong'])/($thongSo[0]->luotDanhGia+1);
    
                $khachSan = KhachSan::find($thongSo[0]->id);
                $a['luotDanhGia'] =$thongSo[0]->luotDanhGia+1;
                $a['chatLuong'] =round($trungBinh,1);
                $khachSan->update($a);
                
                return "phan hoi thanh cong";
                
            }
                
            else    
                return "Phan hoi that bai";
    
            return "da co loi xay ra";
        }
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DanhGia  $danhGia
     * @return \Illuminate\Http\Response
     */
    public function show(DanhGia $danhGia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DanhGia  $danhGia
     * @return \Illuminate\Http\Response
     */
    public function edit(DanhGia $danhGia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DanhGia  $danhGia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $danhGia = DanhGia::find($id);
        $data = $request->all();
        if($danhGia->update($data)){
            return "sua danh gia thanh cong";
        }
        else
            return "sua danh gia that bai";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DanhGia  $danhGia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DanhGia::destroy($id)){
            return "Xoa thanh cong";
        }
        else    
            return "Xoa that bai";
    }

    public function myHistory(Request $request){
        return DB::table('danhgia')
                        ->join('phongkhachsan','danhgia.maPhong', '=','phongkhachsan.id') 
                        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
                        ->join('users','danhgia.maTK','=','users.id')
                        ->where('danhgia.maTK',$request->user()->id)
                        ->select('danhgia.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.email','users.anhDaiDien')
                        ->get();
    }

    public function danhGiaVeKS($id){
        return DB::table('danhgia')
        ->join('phongkhachsan','danhgia.maPhong', '=','phongkhachsan.id') 
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->join('users','danhgia.maTK','=','users.id')
        ->where('khachsan.id',$id)
        ->select('danhgia.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.email','users.anhDaiDien')
        ->get();
    }

    public function toanBoDanhGiaVeQuanLy(Request $request){
        return DB::table('danhgia')
        ->join('phongkhachsan','danhgia.maPhong', '=','phongkhachsan.id') 
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->join('users','danhgia.maTK','=','users.id')
        ->where('khachsan.maTK',$request->user()->id)
        ->select('danhgia.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.email','users.anhDaiDien')
        ->get();
    }
}
