<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    /*
     * @content 生成随机验证码
     * @params $len  int   需要生成验证码的长度
     * @return  $code  string  生成的验证码
     * */

    public static function createcode($len)
    {
        $code = '';
        for($i=1;$i<=$len;$i++){
            $code .=mt_rand(0,9);
        }

        return $code;
    }
    public function CURLGET($url)
    {
        // 初始化一个新会话
        $ch = curl_init();
        // 设置要求请的url
        curl_setopt($ch, CURLOPT_URL, $url);

        // 是否验证SSL证书
        // 一般不验证 ( 默认为验证 需设置fasle关闭 )
        // 如果设置false报错 尝试改为 0
        // 某些CURL版本不只true和fasle两种状态 可能是0,1,2...等
        // 如果选择验证证书 将参数设置为ture或1
        // 然后在使用CURLOPT_CAPATH和CURLOPT_CAINFO设置证书信息
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // 验证证书中域名是否与当前域名匹配 和上面参数配合使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 是否将数据已返回值形式返回
        // 1 返回数据
        // 0 直接输出数据 帮你写了: echo $output;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行CURL请求
        $output = curl_exec($ch);

        // 关闭CURL资源
        curl_close($ch);

        // 输出返回信息
        echo $output;
    }

    public function CURLPOST($url,$data)
    {
        //初使化init方法
        $ch = curl_init();

        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);

        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //发送请求
        $output = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        //返回数据
        return $output;
    }
}
