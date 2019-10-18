<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\TempPhone;
use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\Member;
use App\Tool\UUID;
use App\Entity\TempEmail;
use Mail;
use App\Models\M3Email;
use App\Tool\ValidateCode\ValidateCode;

class MemberController extends Controller
{
        public function register(Request $request){
            $email = $request->input('email','');//获取传过来的值，没有默认为空
            $phone = $request->input('phone','');
            $password = $request->input('password','');
            $confirm = $request->input('confirm','');
            $phone_code = $request->input('phone_code','');
            $validate_code = $request->input('validate_code','');
            
            //后端校验
            if($password == '' || $confirm == '') {
              $ms_result->status=2;
              $ms_result->message='密码不能为空';
              return $ms_result->toJson();
            }
            if(strlen($password) < 6 || strlen($confirm) < 6) {
                 $ms_result->status=3;
                 $ms_result->message='密码不能少于6位';
                 return $ms_result->toJson();
                }
            if($password != $confirm) {
              $ms_result->status=4;
              $ms_result->message='两次密码不相同';
              return $ms_result->toJson();
            }
//            
             //校验验证码
            $tem_phone=new TempPhone;
             $ms_result=new M3Result;
             
             // 手机号不为空
            if($phone != '') {
//                 // 手机号格式
                if(strlen($phone)!= 11 || $phone[0] != '1') {
                  $ms_result->status=1;
                  $ms_result->message='手机格式不正确';
                  return $ms_result->toJson();
                }
                if($phone_code == ''||  strlen($phone_code)!=6) {//验证码
                    $ms_result->status=5;
                    $ms_result->message='手机验证码不能为空';
                    return $ms_result->toJson();
                  }
                  //首先对验证码数据表进行验证
                $result=$tem_phone::where('phone',$phone)->first();
                if($result->code==$phone_code){
                    if(time()>strtotime($result->deadline)){//验证码过期
                        $ms_result->status=5;
                        $ms_result->message='验证码错误';
                        return $ms_result->toJson();
                    }
                    //插入数据库
                    $member=new Member;
                    $member->phone=$phone;
                    $member->password=  md5($password);
                    $member->save();
                    $ms_result->status=0;
                    $ms_result->message='注册成功';
                    return $ms_result->toJson();
                }else{
                    $ms_result->status=5;
                    $ms_result->message='验证码错误';
                    return $ms_result->toJson();
                }
            //邮箱验证    
            }else{
                //首先判断验证码是否相等
                $validate_code_session = $request->session()->get('validate_code','');
//                var_dump($request->session()->get('validate_code'));
//                var_dump($validate_code);
                if($validate_code_session != $validate_code) {
                    $ms_result->status=8;
                    $ms_result->message='验证码不正确';
                    return $ms_result->toJson();
                }

                //插入数据库 注册
                $member=new Member;
                $member->email=$email;
                $member->password=md5($password);
                $member->save();
                    
                $uuid = UUID::create();
                
                $m3_email = new M3Email;
                $m3_email->to = $email;
                $m3_email->cc = '1793040084@qq.com';
                $m3_email->subject = '凯恩书店验证';
                $m3_email->content = '请于24小时点击该链接完成验证. http://www.test.com/service/validate_code/validateEmail'
                                  . '?member_id=' . $member->id
                                  . '&code=' . $uuid;
                //数据库保存
                $tempEmail = new TempEmail;
                $tempEmail->member_id = $member->id;
                $tempEmail->code = $uuid;
                $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24*60*60);
                $tempEmail->save();
                //emails.reminder邮件发送给用户对应得视图(resource下面得view)；['m3_email' => $m3_email]里面得数据供视图使用;
                //由于function 是闭包函数，需要使用外面得参数，需要用到use()方法来传递外部传进来得参数
                Mail::send('email_register', ['m3_email' => $m3_email], function ($m) use ($m3_email) {
                    // $m->from('hello@app.com', 'Your Application');
                    $m->to($m3_email->to, '尊敬的用户')
                      ->cc($m3_email->cc, '尊敬的用户')
                      ->subject($m3_email->subject);// $m3_email->content没有又显示是因为，在email_register里面写了
                });
                $ms_result->status=0;
                $ms_result->message='注册成功';
                return $ms_result->toJson();
            }
      
    }
        public function login(Request $request){
            $username=$request->input('username','');
            $password=$request->input('password','');
            $code=$request->input('validate_code','');
//            var_dump($code);
            
            $m3result=new M3Result;
            
            //优先校验验证码
            $validate_code_sesion=$request->session()->get('validate_code','');
//            var_dump($validate_code_sesion);
            if($code!=$validate_code_sesion){
                $m3result->status=1;
                $m3result->message='验证码错误';
                return $m3result->toJson();
            }
            $member=null;
            //判断
            if(strpos($username,'@')==true){
                //用户名是邮箱的
                $member=Member::where('email',$username)->first();
            }else{
                //用户名是电话的
                $member=Member::where('phone',$username)->first();
            }
            if($member !=null){
                if(md5($password)!=$member->password){
                    $m3result->status=2;
                    $m3result->message='密码错误';
                    return $m3result->toJson();
                }
                $m3result->status=0;
                $m3result->message='登录成功';
                return $m3result->toJson();
            }else{
                $m3result->status=2;
                $m3result->message='没有该用户';
                return $m3result->toJson();
            }
        }
        
}
