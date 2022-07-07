<?php

namespace App\Http\Controllers;
use stdClass;
use App\Models\DatPhong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DatPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DatPhong::all();
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
        $datPhong = $request->all();
        $datPhong['maTK'] = $request->user()->id;
        $datPhong['trangThai'] = 'xetDuyet';

        $danhSachDangXai = DB::table('datphong')
                                    ->where('trangThai',"xetDuyet")
                                    ->orWhere("trangThai","chuaThanhToan")
                                    ->orWhere("trangThai","daThanhToan")
                                    ->get();
                                
        if(!count($danhSachDangXai)){

            $dptc = DatPhong::create($datPhong);
            
            return "Dat phong thanh cong";
            
           
        }
        else{
           
            foreach($danhSachDangXai as $a)
            {
                if($a->maPhong == $datPhong['maPhong']){
                    if(
                        !($datPhong['ngayNhan'] < $a->ngayNhan && $datPhong['ngayTra'] < $a->ngayNhan)
                        &&
                        !($datPhong['ngayNhan'] > $a->ngayTra && $datPhong['ngayTra'] > $a->ngayTra)
                        ){
                            return "Khong the dat phong vi trung lich";
                    }
                }
            }
                if($dptc = DatPhong::create($datPhong))
                    {

                        
                        
                       

                        return "Dat phong thanh cong";
                       
                    }
                            
                return "Dat phong that bai";        
            
        }


        // $a = $request->all()['ngayBD'];
        // $b = $request->all()['ngayKT'];
        // return $a>$b?"BD lon hon":"KT lon hon";
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DatPhong  $datPhong
     * @return \Illuminate\Http\Response
     */
    public function show(DatPhong $datPhong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DatPhong  $datPhong
     * @return \Illuminate\Http\Response
     */
    public function edit(DatPhong $datPhong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DatPhong  $datPhong
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatPhong $datPhong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DatPhong  $datPhong
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatPhong $datPhong)
    {
        // 
    }
    public function myHistory(Request $request){
        return DB::table('datphong')
                        ->join('phongkhachsan','datphong.maPhong','=','phongkhachsan.id')
                        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
                        ->join('users','khachsan.maTK','=','users.id')
                        ->where('datphong.maTK',$request->user()->id)
                        ->select('datphong.*','phongkhachsan.maPhong','phongkhachsan.id',
                        'khachsan.tenKhachSan','users.endpoint','users.partnerCode','users.accessKey','users.secretKey','khachsan.diaChi','khachsan.soDienThoai')
                        ->get();
    }

    public function huyDat($id){
        $hoaDon = DatPhong::find($id);
        if($hoaDon['trangThai'] === "xetDuyet"){
            DatPhong::destroy($id);
            return "Xoa thanh cong";
        }
        return "Xoa that bai";
    }

    public function request(Request $request){
        return DB::table('datphong')
                        ->join('phongkhachsan','datphong.maPhong','=','phongkhachsan.id')
                        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
                        ->join('users','datphong.maTK','=','users.id')
                        ->where('khachsan.maTK',$request->user()->id)
                        ->where('datphong.trangThai','xetDuyet')
                        ->select('datphong.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.hoTen','users.email','users.soDienThoai')
                        ->get();
    }

    public function agree($id){
        $datPhong = DatPhong::find($id);
        $a['trangThai'] = "chuaThanhToan";
       
        if($datPhong->update($a))
            return "chinh sua dong y dat phong thanh cong";
        return "dong y that bai";
       
    }


    public function deny($id){
        $datPhong = DatPhong::find($id);
        $a['trangThai'] = "daHuy";
       
        if($datPhong->update($a))
            return "chinh sua tu choi dat phong thanh cong";
        return "tu choi that bai";
    }

    public function historyOfHotelDirector(Request $request){
        return DB::table('datphong')
        ->join('phongkhachsan','datphong.maPhong','=','phongkhachsan.id')
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->join('users','datphong.maTK','=','users.id')
        ->where('khachsan.maTK',$request->user()->id)
        ->where('datphong.trangThai','hoanTat')
        ->orWhere('datphong.trangThai','daThanhToan')
        ->select('datphong.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.hoTen','users.email','users.soDienThoai','users.diaChi')
        ->get();
    }

    public function danhSachNo(Request $request){
        return DB::table('datphong')
        ->join('phongkhachsan','datphong.maPhong','=','phongkhachsan.id')
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->join('users','datphong.maTK','=','users.id')
        ->where('khachsan.maTK',$request->user()->id)
        ->where('datphong.trangThai','chuaThanhToan')
       
        ->select('datphong.*','phongkhachsan.maPhong','khachsan.tenKhachSan','users.hoTen','users.email','users.soDienThoai','users.diaChi')
        ->get();
    }



    public function phongTrongThuocKhachSan(Request $request, $id){
        $ngayNhan = $request->all()['ngayNhan'];
        $ngayTra = $request->all()['ngayTra'];

        $phongThuocKhachSanDangDat =  DB::table('datphong')
        ->join('phongkhachsan','datphong.maPhong','=','phongkhachsan.id')
        ->join('khachsan','phongkhachsan.maKS','=','khachsan.id')
        ->where('khachsan.id',$id)
        ->where('datphong.trangThai',"xetDuyet")
        ->orWhere("datphong.trangThai","chuaThanhToan")
        ->orWhere("datphong.trangThai","daThanhToan")
        ->select('datphong.*')
        ->get();

        $phongKhachSan = DB::table('phongkhachsan')
                            ->where('phongkhachsan.maKS',$id)
                            ->get();
      
        $dapSo = [];

        foreach($phongKhachSan as $phong){
            $kt = 0;
            
            foreach($phongThuocKhachSanDangDat as $phongDat){
               
                if($phongDat->maPhong === $phong->id){
                    if($phongDat->ngayNhan > $ngayTra || $phongDat->ngayTra < $ngayNhan ){
                        $kt++;                        
                    }
                }
                else    
                    $kt++;
            }

            if($kt === count($phongThuocKhachSanDangDat)){
                $dapSo[] = $phong;
            }

        }
        
            return $dapSo;
        
    }


   


    public function thanhToan(Request $request){
        
        function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            return $result;
        }


        $endpoint = $request->all()['endpoint'];
        
        
        $partnerCode = $request->all()['partnerCode'];
        $accessKey = $request->all()['accessKey'];
        $secretKey = $request->all()['secretKey'];
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $request->all()['tongTien'];
        // $amount = '2000';
        $orderId = time() ."";
        
        $redirectUrl = "https://fierce-peak-31965.herokuapp.com/api/datPhong/thanhToanThanhCong";
        // $redirectUrl = "http://localhost:8000/api/datPhong/thanhToanThanhCong";
        $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        $extraData = $request->all()['ngayNhan'];;
        
        
        
         
        
            $requestId = time() . "";
            $requestType = "captureWallet";
           
            //before sign HMAC SHA256 signature

            $rawHash = "accessKey=" . $accessKey . "&amount=" 
            . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl 
            . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" 
            . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId="
            . $requestId . "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = array('partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature);
            $result = execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
        
            //Just a example, please check more in there
        
            // header('Location: ' . $jsonResult['payUrl']);
        return $jsonResult;
        // return $request->all();
        
    }
    public function thanhToanThuCong(Request $request){
        $id = $request->all()['id'];
        $hoaDon = DatPhong::find($id);

        $a['trangThai'] = "hoanTat";
        if($hoaDon->update($a)){
            return "thanh toan thanh cong";
        }
        return "thanh toan that bai";
    }

    public function thanhToanThanhCong(Request $request){
        $mess = $_GET['message'];
        $ngayNhan = $_GET['extraData'];

        if($mess === "Giao dịch thành công."){
            DB::table('datphong')
                ->where('ngayNhan',$ngayNhan)
                ->update(['trangThai'=>'daThanhToan']);
        
            return "Giao dịch thành công";
          
       }
       return "Giao dịch thất bại";
    
    }
}
