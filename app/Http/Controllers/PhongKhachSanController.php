<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\PhongKhachSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PhongKhachSanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PhongKhachSan::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    public function getMyRoom(Request $request){
        
        // return DB::table('dichvu')->where('maKS',$request->user()->id)->get();
        return DB::table('phongkhachsan')
                    ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
                    ->where('maTK',$request->user()->id)
                    ->select('phongkhachsan.*','khachsan.tenKhachSan')
                    ->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maKS' => 'numeric|required',
            'maPhong' => 'required|string',
            'dienTich' => 'numeric|required',
            'soGiuong' => 'numeric|required',
            'hinhAnh.*' => 'image',
            'donGia' => 'numeric|required',
            'trangThai' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $data = $request->all();
       
        if($request->hasFile('hinhAnh')){
            $images = [];
            $files =  $request->file('hinhAnh');
            foreach($files as $file){    
                $upload = Cloudinary::upload($file->getRealPath(),array("folder" => "DoAnTotNghiep")); 
                $key = $upload->getPublicId();
                $images[$key] = $upload->getSecurePath();               
            }
            $data['hinhAnh'] = json_encode($images);
        }
        if(PhongKhachSan::create($data))
            return "tao phong thanh cong";
        else return "tao phong that bai";

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PhongKhachSan  $phongKhachSan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DB::table('phongkhachsan')
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->where('phongkhachsan.id',$id)
        ->select('phongkhachsan.*','khachsan.tenKhachSan')
        ->get();

        // return PhongKhachSan::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhongKhachSan  $phongKhachSan
     * @return \Illuminate\Http\Response
     */
    public function edit(PhongKhachSan $phongKhachSan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhongKhachSan  $phongKhachSan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $phongKhachSan = PhongKhachSan::find($id);

        $validator = Validator::make($request->all(), [
            'maKS' => 'numeric|required',
            'maPhong' => 'required|string',
            'dienTich' => 'numeric|required',
            'soGiuong' => 'numeric|required',
            'hinhAnh.*' => 'image',
            'donGia' => 'numeric|required',
            'trangThai' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $data = $request->all();
        
        if($request->hasFile('hinhAnh')){
            // neu request co anh thi xoa tren couldinary vs DB
            if($phongKhachSan['hinhAnh']!=null){
                $image = json_decode($phongKhachSan['hinhAnh']);
                foreach($image as $key => $value){
                    Cloudinary::destroy($key);
                }            
            }
            
            $images = new stdClass();     
            $files =  $request->file('hinhAnh');
            foreach($files as $file){
                $upload = Cloudinary::upload($file->getRealPath(),array("folder" => "DoAnTotNghiep")); 
                $key = $upload->getPublicId();
                $images->$key = $upload->getSecurePath();     
            }
            $data['hinhAnh'] = json_encode($images);
        }

        $phongKhachSan->update($data);
        $response = [
            'message' => 'chinh sua thanh cong',
            'data' => $phongKhachSan
        ];
        return $response;


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhongKhachSan  $phongKhachSan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $phongKhachSan = PhongKhachSan::find($id);
            if(PhongKhachSan::destroy($id)){
                $image = json_decode($phongKhachSan['hinhAnh']);
                if($image != null){
                    foreach($image as $key => $value){
                        Cloudinary::destroy($key);
                    }  
                }              
                return "Xoa thanh cong";
            }
        }
        catch(\Exception $e){
            return "Khong the xoa ";
        }
    }

    public function roomForHotel($id){
        return DB::table('phongkhachsan')
                    ->where('phongkhachsan.maKS',$id)
                    ->get();
    }
}
