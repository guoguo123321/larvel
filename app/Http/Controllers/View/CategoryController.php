<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Category;

class CategoryController extends Controller
{
    public function toCategory(){
        $categorys=Category::whereNull('parent_id')->get();
        return view('category')->with('categorys',$categorys);
    }
}
