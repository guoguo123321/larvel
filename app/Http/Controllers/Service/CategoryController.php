<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Category;
use App\Models\M3Result;

class CategoryController extends Controller
{
    public function twoCategory($parent_id){
//        $parent=$request->input('$parent_id');
//        var_dump($parent);
        $categorys=Category::where('parent_id',$parent_id)->get();
         $m3result=new M3Result;

            $m3result->status=0;
            $m3result->message='返回成功';
            $m3result->categorys=$categorys;//把$categorys的数据一并返回  脚本语言的特性 js和php都是可以随时定义成员变量的
            return $m3result->toJson();
    }
}
