<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Product;
use App\Entity\PatContent;
use App\Entity\PdtImages;
use Log;
class ProductController extends Controller
{
    public function product($category_id){
//        var_dump($category_id);
        $products=Product::where('category_id',$category_id)->get();
        Log::info($products);
        return view('product')->with('products',$products);
    }
    public function toPdtContent(Request $request,$parent_id){
        $products=Product::find($parent_id);
        $pdt_content=PatContent::where('product_id',$parent_id)->first();
        $pdt_images=PdtImages::where('product_id',$parent_id)->get();
        
        //保存购物车里面的值
        $bk_car=$request->cookie('bk_car');//假设用cookie 获取的值是1：1，2：1
        $bk_car_arr=($bk_car!=null?explode(',', $bk_car):array());//explode遇到，把字符串拆分成数组
        $count=0;
        foreach ($bk_car_arr as $value){//这里是值引用
            $index=strpos($value, ':');
            $first=substr($value, 0, $index);
            if($first==$parent_id){
                $count=((int)substr($value,$index+1))+1;
                break;
            }
        }
//        var_dump($products->name);
        return view('pdt_content')->with('product',$products)
                                  ->with('pdt_content',$pdt_content)
                                  ->with('pdt_images',$pdt_images)
                                  ->with('count',$count);
    }
}
