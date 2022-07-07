<?php

namespace App\Http\Controllers;

use App\Models\User;
use stdClass;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [         
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'hoTen' => 'required|string',
            'diaChi' => 'required|string',
            'anhDaiDien' => 'image',
            'soDienThoai' => 'string|required',
            'vaiTro'      => 'required',
            
        ]);

        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $user = $request->all();
        if($request->hasFile('anhDaiDien')){
            $image = [];

            $upload = Cloudinary::upload(
            $request->file('anhDaiDien')->getRealPath(),
            array("folder" => "DoAnTotNghiep"));
            
            $key = $upload->getPublicId();
            $images[$key] = $upload->getSecurePath();   
            $user['anhDaiDien'] = json_encode($images);     
        }

        if($user['vaiTro'] == "hotelManager"){
            $user['trangThai'] = "choDuyet";
        }
        else{
            $user['trangThai'] = "dangHoatDong";
        }
        
        
        $user['password'] = bcrypt($user['password']);
        User::create($user);
        
        return response([
            "message" => "dang ky thanh cong"
        ],201);

    }



    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response([
                'message' => 'ten dang nhap hoac mat khau sai'
            ]);
        }


        //--------------------------------------------------------------
        if($user['trangThai'] == "dangHoatDong"){
            $token = $user->createToken('ptoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response,201);
        }
        else if($user['trangThai'] == "choDuyet"){
            return "Tai khoan chua duoc duyet";
        }
        else if($user['trangThai'] == "biKhoa")
            return "Tai khoan da bi khoa";
        return "Tu choi dang ky";

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }



    public function show($id){
        return User::find($id);
    }

    public function getAllAccount(){
        return DB::table('users')->where('trangThai','dangHoatDong')->get();
    }

    public function getAllBlocked(){
        return DB::table('users')->where('trangThai','biKhoa')->get();
    }

    public function getAllRefuse(){
        return DB::table('users')->where('trangThai','tuChoi')->get();
    }

    public function update(Request $request,$id){
        $user = User::find($id);

        $validator = Validator::make($request->all(), [         
           
            'password' => 'string|confirmed',
            'hoTen' => 'required|string',
            'diaChi' => 'required|string',
            'anhDaiDien' => 'image',
            'soDienThoai' => 'string|required',
            
            
        ]);

        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $data = $request->all();
        if($request->hasFile('anhDaiDien')){
            // neu co anh thi xoa tren couldinary
            if($user['anhDaiDien']!=null){
                $image = json_decode($user['anhDaiDien']);
                foreach($image as $key => $value){
                    Cloudinary::destroy($key);
                }            
            }
            
            $images = new stdClass();     
            $file =  $request->file('anhDaiDien');

            $upload = Cloudinary::upload($file->getRealPath(),array("folder" =>"DoAnTotNghiep")); 
            $key = $upload->getPublicId();
            $images->$key = $upload->getSecurePath();     
  
            $data['anhDaiDien'] = json_encode($images);
        }

        $data['password'] = bcrypt($data['password']);
        if($user->update($data)!=null){
           
            $response = [
                'message' => 'chinh sua thanh cong',
                'data' => $user
            ];
            return $response;
        }
        return "that bai";
    }

    public function danhSachXetDuyet(){
        return DB::table('users')->where('trangThai','choDuyet')->get();
    }

    public function dongY($id){
        $user = User::find($id);
        
        $data['trangThai'] = 'dangHoatDong';

        if($user->update($data)!=null){
            return "thanh cong";
        }
        return "that bai";
    }

    public function tuChoi($id){
        $user = User::find($id);

        
        $data['trangThai'] = 'tuChoi';

        if($user->update($data)!=null){
            return "thanh cong";
        }
        return "that bai";
    }

    public function khoaTK($id){
        $user = User::find($id);

        
        $data['trangThai'] = 'biKhoa';

        if($user->update($data)!=null){
            return "thanh cong";
        }
        return "that bai";
    }

    public function moKhoa($id){
        $user = User::find($id);

        
        $data['trangThai'] = 'dangHoatDong';

        if($user->update($data)!=null){
            return "thanh cong";
        }
        return "that bai";
    }
}
