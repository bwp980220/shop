<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>登录</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
</head>
<div class="m-block-header" id="div-header">
    <strong id="m-title">登录</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
<div class="wrapper">
    <div class="registerCon">
        <div class="binSuccess5">
            <ul>
                <li class="accAndPwd">
                    <dl>
                        <div class="txtAccount">
                            <input id="txtAccount" type="text" placeholder="请输入您的手机号码/邮箱"><i></i>
                        </div>
                        <cite class="passport_set" style="display: none"></cite>
                    </dl>
                    <dl>
                        <input id="txtPassword" type="password" placeholder="密码" value="" maxlength="20" /><b></b>
                    </dl>
                    <dl>
                            <input id="verifycode" type="text" placeholder="请输入验证码"  maxlength="4" /><b></b>
                            <img src="{{url('/verify/create')}}" alt="" id="img">
                    </dl>
                </li>
            </ul>
            <input type="hidden" id="_token" value="{{csrf_token()}}">
            <a id="btnLogin" href="javascript:;" class="orangeBtn loginBtn">登录</a>
        </div>
        <div class="forget">
            <a href="https://m.1yyg.com/v44/passport/FindPassword.do">忘记密码？</a><b></b>
            <a href="{{url('/register')}}">新用户注册</a>
        </div>
    </div>
    <div class="oter_operation gray9" style="display: none;">
        
        <p>登录666潮人购账号后，可在微信进行以下操作：</p>
        1、查看您的潮购记录、获得商品信息、余额等<br />
        2、随时掌握最新晒单、最新揭晓动态信息
    </div>
</div>

<script src="{{url('js/jquery-1.8.3.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $(".footer").attr('style','display:none');
        layui.use(['layer'],function () {

            var layer=layui.layer;
            $("#btnLogin").click(function(){
                var verifycode=$('#verifycode').val();
                var txtAccount=$("#txtAccount").val();
                if(txtAccount==''){
                    layer.msg('账号不能为空');
                    return false;
                }
                var txtPassword=$("#txtPassword").val();
                if(txtPassword==''){
                    layer.msg('密码不能为空');
                    return false;
                }
                var _token=$("#_token").val();
                $.ajax({
                    type:'post',
                    url:"{{url('loginDo')}}",
                    data:{txtPassword:txtPassword,txtAccount:txtAccount,_token:_token,verifycode:verifycode},
                    dataType:'json'
                }).done(function (res) {
                    if(res.code==1){
                        layer.msg(res.font,{icon:res.code,time:2000},function(){
                            location.href="{{url('../')}}"
                        });
                    }else{
                        layer.msg(res.font,{icon:res.code});
                    }
                })
            })
            $("#img").click(function(){
                $(this).attr('src',"{{url('/verify/create')}}"+"?"+Math.random())
            })
        })
    })
</script>


