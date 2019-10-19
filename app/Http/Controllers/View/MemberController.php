<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function tologin(){
        return view('login');
    }
    public function toregister(Request $request){
        return view('register');
    }
}
