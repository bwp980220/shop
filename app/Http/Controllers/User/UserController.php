<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Common;
class UserController extends Controller
{
    //登录
    public function login()
    {
        return view('login');
    }
    //登录
    public function loginDo(Request $request)
    {
        $usermodel=new User;
        if(empty($request->txtAccount)){
            echo json_encode(['font'=>'账户不能为空', 'code'=>2]);exit;
        }
        if(empty($request->txtPassword)){
            echo json_encode(['font'=>'密码不能为空', 'code'=>2]);exit;
        }
        $verifycode=session('verifycode');
        $code=$request->verifycode;
        if($verifycode!=$code){
            echo json_encode(['font'=>'验证码错误', 'code'=>2]);exit;
        }
        $where=[
            'user_name'=>$request->txtAccount
        ];
        $data=$usermodel->where($where)->first();
        //$pwd=$data->user_pwd;

        //$pwd=decrypt($pwd);
        //dd(decrypt($data->user_pwd));
        if(!empty($data)){
            if(decrypt($data->user_pwd)==$request->txtPassword){
                // 存储数据到 session...
                session(['user_id' =>$data->user_id,'user_name'=>$data->user_name]);
                echo json_encode(['font'=>'登陆成功', 'code'=>1]);
            }else{
                echo json_encode(['font'=>'账号密码错误', 'code'=>2]);exit;
            }
        }else{
            echo json_encode(['font'=>'账号密码错误', 'code'=>2]);exit;
        }
    }
    //注册
    public function register()
    {
        return view('register');
    }

    public function regdo(Request $request)
    {
        $user_name=$request->user_name;
        $verifycode=$request->verifycode;
        $code=session('code');
        if($verifycode!=$code){
            return 4;exit;
        }
        $pwd=$request->pwd;
        $where=[
            'user_name'=>$user_name
        ];
        $usermodel=new User;
        $res=$usermodel->where($where)->first();
        if($res){
            return 1;
            //用户名已存在
        }else{
            $arr=$request->all();
            unset($arr['verifycode']);
            unset($arr['_token']);
            $arr['user_pwd']=encrypt($arr['user_pwd']);
            $data=$usermodel->insert($arr);
            if($data){
                return 2;
                //注册成功
            }else{
                return 3;
                //注册失败
            }
        }
    }
    //发送短信验证码
    public function code(Request $request)
    {
        $tel=$request->reg_tel;
        $code=rand(1000,9999);
        session(['code'=>$code]);
        $this->sendMobile($code,$tel);
    }
    //验证码
    public function regauth()
    {
        return view('regauth');
    }
    /*
     * @content 发送手机验证码
     * @params  $mobile  要发送的手机号
     *
     * */
    private function sendMobile($code,$mobile)
    {
        $host = env("MOBILE_HOST");
        $path = env("MOBILE_PATH");
        $method = "POST";
        $appcode = env("MOBILE_APPCODE");
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "content=【创信】你的验证码是：".$code."，3分钟内有效！&mobile=".$mobile;
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        var_dump(curl_exec($curl));
    }

}
