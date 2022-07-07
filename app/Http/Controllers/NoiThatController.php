<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\NoiThat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class NoiThatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return NoiThat::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }
    
    public function getMyFurniture(Request $request){
        return DB::table('noithat')
                    ->join('phongkhachsan','noithat.maPhong','=','phongkhachsan.id')
                    ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
                    ->where('khachsan.maTK',$request->user()->id)
                    ->select('noithat.*','khachsan.tenKhachSan','phongkhachsan.maPhong')
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
        $validator = Validator::make($request->all(),[  
            'maPhong'   => 'numeric|required',             
            'tenNoiThat' => 'required|string',
            'soLuong'   => 'numeric|required',
            'hinhAnh' => 'image',
            'moTa'      => 'string|required',
        ]);

        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        
        $noiThat = $request->all();
        if($request->hasFile('hinhAnh')){
            $image = [];

            $upload = Cloudinary::upload(
            $request->file('hinhAnh')->getRealPath(),
            array("folder" => "DoAnTotNghiep"));
            
            $key = $upload->getPublicId();
            $images[$key] = $upload->getSecurePath();   
            $noiThat['hinhAnh'] = json_encode($images);     
        }
       
        NoiThat::create($noiThat);

        return response([
            "message" => "tao noi that thanh cong"
        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoiThat  $noiThat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return NoiThat::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoiThat  $noiThat
     * @return \Illuminate\Http\Response
     */
    public function edit(NoiThat $noiThat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoiThat  $noiThat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $noiThat = NoiThat::find($id);
        
        $validator = Validator::make($request->all(), [     
            'tenNoiThat' => 'required|string',
            'soLuong'   => 'numeric|required',
            'hinhAnh' => 'image',
            'moTa'      => 'string|required',
        ]);
        
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $data = $request->all();
        if($request->hasFile('hinhAnh')){
            // neu co anh thi xoa tren couldinary
            if($noiThat['hinhAnh']!=null){
                $image = json_decode($noiThat['hinhAnh']);
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
        
        if($noiThat->update($data)!=null){
            $response = [
                'message' => 'chinh sua thanh cong',
                'data' => $noiThat
            ];
            return $response;
        }
        return "that bai";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoiThat  $noiThat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $noiThat = NoiThat::find($id);
            if(NoiThat::destroy($id)){
                $image = json_decode($noiThat['hinhAnh']);
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
    
    public function furnitureForRoom($id){
        return DB::table('noithat')->where('maPhong','=',$id)->get();
    }
}
