<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\DichVu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DichVuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DichVu::all();
    }

  

    public function getMyService(Request $request){
        
        // return DB::table('dichvu')->where('maKS',$request->user()->id)->get();
        return DB::table('dichvu')
                    ->join('khachsan','dichvu.maKS','=','khachsan.id')
                    ->where('maTK',$request->user()->id)
                    ->select('dichvu.*','khachsan.tenKhachSan')
                    ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'tenDichVu' => 'string|required',
            'hinhAnh' => 'image',
            'moTa' => 'string|required'
        ]);
        
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $dichVu = $request->all();
        if($request->hasFile('hinhAnh')){
            $image = [];

            $upload = Cloudinary::upload(
            $request->file('hinhAnh')->getRealPath(),
            array("folder" => "DoAnTotNghiep"));
            
            $key = $upload->getPublicId();
            $images[$key] = $upload->getSecurePath();   
            $dichVu['hinhAnh'] = json_encode($images);     
        }
    
        // $dichVu['maKS'] = $id;
        DichVu::create($dichVu);

        return response([
            "message" => "tao dich Vu thanh cong"
        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DichVu  $dichVu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DichVu::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DichVu  $dichVu
     * @return \Illuminate\Http\Response
     */
    public function edit(DichVu $dichVu)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DichVu  $dichVu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dichVu = DichVu::find($id);
        
        $validator = Validator::make($request->all(), [
            'maKS' => 'numeric|required',
            'tenDichVu' => 'string|required',
            'hinhAnh' => 'image',
            'moTa' => 'string|required'
        ]);
        
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $data = $request->all();
        if($request->hasFile('hinhAnh')){
            // neu co anh thi xoa tren couldinary
            if($dichVu['hinhAnh']!=null){
                $image = json_decode($dichVu['hinhAnh']);
                foreach($image as $key => $value){
                    Cloudinary::destroy($key);
                }            
            }
            
            $images = new stdClass();     
            $file =  $request->file('hinhAnh');

            $upload = Cloudinary::upload($file->getRealPath(),array("folder" => "DoAnTotNghiep")); 
            $key = $upload->getPublicId();
            $images->$key = $upload->getSecurePath();     
  
            $data['hinhAnh'] = json_encode($images);
        }
        
        if($dichVu->update($data)!=null){
            $response = [
                'message' => 'chinh sua thanh cong',
                'data' => $dichVu
            ];
            return $response;
        }
        return "that bai";
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DichVu  $dichVu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try{
            $dichVu = DichVu::find($id);
            if(DichVu::destroy($id)){
                $image = json_decode($dichVu['hinhAnh']);
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

    public function serviceForHotel($id){
        return DB::table('dichvu')
                        ->join('khachsan','dichvu.maKS','=','khachsan.id')
                        ->where('maKS',$id)
                        ->select('dichvu.*','khachsan.tenKhachSan')
                        ->get();
    }
    
}
