<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\KhachSan;
use App\Models\PhongKhachSan;
use App\Models\DatPhong;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class KhachSanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('khachsan')
                    ->join('users','khachsan.maTK','=','users.id')
                    ->select('khachsan.*','users.email')
                    ->get();
    }

    public function getMyHotel(Request $request){
        return DB::table('khachsan')->where('maTK',$request->user()->id)->get();
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
        $validator = Validator::make($request->all(),[  
            'soDienThoai' => 'numeric|required',
            'dienTich' => 'numeric|required', 
            'tenKhachSan' => 'required|string',            
            'diaChi' => 'required|string',
            'hinhAnh.*' => 'image',
            'chatLuong' => 'numeric|required',
            'moTa'      => 'string|required',
        ]);

        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        
        $data = $request->all();
        $data['maTK'] = $request->user()->id;
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
        if(KhachSan::create($data))
            return "tao khach san thanh cong";
        else return "tao khach san that bai";
       

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KhachSan  $khachSan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DB::table('khachsan')
                        ->join('users','khachsan.maTK','=','users.id')
                        ->where('khachsan.id',$id)
                        ->select('khachsan.*','users.email')
                        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KhachSan  $khachSan
     * @return \Illuminate\Http\Response
     */
    public function edit(KhachSan $khachSan)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KhachSan  $khachSan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $khachSan = KhachSan::find($id);
        
        $validator = Validator::make($request->all(),[               
            'soDienThoai' => 'numeric|required',
            'dienTich' => 'numeric|required', 
            'tenKhachSan' => 'required|string',            
            'diaChi' => 'required|string',
            'hinhAnh.*' => 'image',
            'moTa'      => 'string|required',
        ]);

        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        // $data = $request->all();
        // if($request->hasFile('hinhAnh')){
        //     $images = json_decode($KhachSan['hinhAnh']);
        //     if($images==null){
        //         $images = new stdClass();
        //     } 
        //     $files =  $request->file('hinhAnh');
        //     foreach($files as $file){
        //         $upload = Cloudinary::upload($file->getRealPath(),array("folder" => "DoAnTotNghiep")); 
        //         $key = $upload->getPublicId();
        //         $images->$key = $upload->getSecurePath();               
        //     }
            
        //     $data['hinhAnh'] = json_encode($images);

        // }

        $data = $request->all();
        if($request->hasFile('hinhAnh')){
            // neu request co anh thi xoa tren couldinary
            if($khachSan['hinhAnh']!=null){
                $image = json_decode($khachSan['hinhAnh']);
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

        $khachSan->update($data);
        $response = [
            'message' => 'chinh sua thanh cong',
            'data' => $khachSan
        ];
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KhachSan  $khachSan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $khachSan = KhachSan::find($id);
            if(KhachSan::destroy($id)){
                $image = json_decode($khachSan['hinhAnh']);
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

    public function deleteImage($id,$key){
        $image = json_decode(DB::table('KhachSan')->select('hinhAnh')->where('id',$id)->get());
        foreach($image as $k=>$value){
            if($k == $key){
                Cloudinary::destroy($key);
                return response()->json("Xoa thanh cong",413);
            }
        }

    }
    public function search(Request $request){
        
        // if($request->all()['tenKhachSan'] )

        $danhSachKhachSan = DB::table('khachsan')
        ->join('users','khachsan.maTK','=','users.id')
        ->where('khachsan.diaChi','like','%'.$request->all()['diaChi'].'%')
        ->where('khachsan.tenKhachSan','like','%'.$request->all()['tenKhachSan'].'%')
        ->select('khachsan.*','users.email')
        ->get();
       
      
        $dapSo = [];
        if($request->all()['ngayNhan']){

            $danhSachDatPhong = DB::table('datphong')
            ->where('datphong.trangThai',"xetDuyet")
            ->orWhere("datphong.trangThai","chuaThanhToan")
            ->orWhere("datphong.trangThai","daThanhToan")
            ->select('datphong.*')
            ->get();

            foreach($danhSachKhachSan as $dsks){
                
                $danhSachPhong = DB::table('phongkhachsan')
                        ->where('phongkhachsan.maKS',$dsks->id)
                        ->get();
                foreach($danhSachPhong as $dsp){
                    $kt = 0;
                    foreach($danhSachDatPhong as $dsdp){
                        if($dsp->id === $dsdp->maPhong){
                            if( !($dsdp->ngayNhan > $request->all()['ngayTra']) && !($dsdp->ngayTra < $request->all()['ngayNhan']) ){
                                $kt = -1;
                                break;
                            }
                        }
                    }
                    if($kt==0){
                        $dapSo[] = $dsks;
                        break;
                    }
                   
                }

            }

           return $dapSo;
        }

        return $danhSachKhachSan;


    }

    
  
}
