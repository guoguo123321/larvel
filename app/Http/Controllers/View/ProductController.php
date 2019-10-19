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
    public function toPdtContent($category_id){
        $products=Product::find($category_id);
        $pdt_content=PatContent::where('product_id',$category_id)->first();
        $pdt_images=PdtImages::where('product_id',$category_id)->get();
//        var_dump($products->name);
        return view('pdt_content')->with('product',$products)
                                  ->with('pdt_content',$pdt_content)
                                  ->with('pdt_images',$pdt_images);
    }
}
