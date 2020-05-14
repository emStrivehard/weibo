<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function story(Request $request)
    {
        $credentials = $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required|min:6|max:15'
        ]);
        if(Auth::attempt($credentials,$request->has('remember'))){
            if(Auth::user()->activated){
                session()->flash('success','欢迎回来');
                $fallback = route('users.show',[Auth::user()]);
                return redirect()->intended($fallback);
            }else{
                Auth::logout();
                session()->flash('warning','您的账户未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        }else{
            session()->flash('danger','很抱歉,您的邮箱和尼玛不匹配');
            return redirect()->back()->withInput();
        }

    }

    public function destory()
    {
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect('login');
    }
}
