<?php
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Tool\ValidateCode\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Entity\TempPhone;
use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\TempEmail;
use App\Entity\Member;

class ValidateController extends Controller
{
    //验证码
    public function test(Request $request){
        $validate=new ValidateCode();
        $request->session()->put('validate_code',$validate->getCode());
//        $request->session()->put('validate_code',666);
        return $validate->doimg();
    }
    //短信验证
    public function sendSMS(Request $request){
        $phone=$request->input('phone','');
        //连接对象，获取手机然后通过外部调用一个类来判断里面的状态
        $M3Result=new M3Result;
        if($phone==''){
            $M3Result->status='1';
            $M3Result->message='输入的电话号码为空！';
            return $M3Result->toJson();
        }
        //生成六位数的随机数
        $sendTemplateSMS=new SendTemplateSMS;
        $charset='1234567890';
        $code='';
         $_len = strlen($charset) - 1;
        for ($i = 0;$i < 6;++$i) {
            $code .= $charset[mt_rand(0, $_len)];
        }
        $m3_result=$sendTemplateSMS->sendTemplateSMS($phone, array($code, 6), 1);
        if($m3_result->status==0){
            //连接tem_phone数据库，并存入数据
            //这一步是避免验手机证码反复插入出现众多验证码的情况
            $tempPhone = TempPhone::where('phone', $phone)->first();
            if($tempPhone == null) {
              $tempPhone = new TempPhone;
            }
            $tempPhone->phone=$phone;
            $tempPhone->code=$code;
            $tempPhone->deadline= date('Y-m-d H-i-s', time()+60*60);
            $tempPhone->save();
        }
//        $M3Result->status='0';
//        $M3Result->message='发送电话号码！';
//        return $M3Result->toJson();
        return $m3_result->toJson();
    }
    //邮箱验证
    public function validateEmail(Request $request){
        $memberid=$request->input('member_id','');
        $code=$request->input('code','');
        if($memberid==''||$code==''){
            return '验证异常';
        }
        $tempEmail=TempEmail::where('member_id',$memberid)->first();
        if($tempEmail==null){
            return '验证异常';
        }
        if($tempEmail->code==$code){
            if(time()>strtotime($tempEmail->deadline)){
                return '验证码失效';
            }
            $member=  Member::find($memberid);
            $member->active=1;
            $member->save();
            return redirect('/login');
        }else{
            return '验证码异常';
        }
    }
}
