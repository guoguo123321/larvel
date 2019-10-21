<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Category;
use App\Models\M3Result;

class CarController extends Controller
{
    public function toCar(Request $request,$parent_id){
        $bk_car=$request->cookie('bk_car');//假设用cookie 获取的值是1：1，2：1
//        return $bk_car;
        $bk_car_arr=($bk_car!=null?explode(',', $bk_car):array());//explode遇到，把字符串拆分成数组
        
        $count=1;
        foreach ($bk_car_arr as &$value){//这里是值引用
            $index=strpos($value, ':');
            $first=substr($value, 0, $index);
            if($first==$parent_id){
                $count=((int)substr($value,$index+1))+1;
                $value=$parent_id.":".$count;//$value重新构造
                break;
            }
        }
        //如果后面的数量等于1，则添加到数组里面
        if($count==1){
            array_push($bk_car_arr, $parent_id.":".$count);
        }
        
        $m3result=new M3Result;
        $m3result->status=0;
        $m3result->message='返回成功';
        return response($m3result->toJson())->withCookie('bk_car',  implode(',', $bk_car_arr));
    }
}
