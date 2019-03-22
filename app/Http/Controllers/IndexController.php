<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Cate;
use Illuminate\Support\Facades\DB;
class IndexController extends Controller
{
    /*
     * @content 主页
     */

    public function index()
    {
        //轮播图
        $goodsmodel=new Goods;
        $data=$goodsmodel->orderBy('update_time','desc')->select('goods_img')->paginate(5);
        //最热商品
        $goodshost=$goodsmodel->where(['is_hot'=>1])->orderBy('update_time','desc')->paginate(2);
        //猜你喜欢商品列表
        $goodsinfo=$goodsmodel->where(['is_new'=>1])->orderBy('update_time','desc')->get();
        //分类
        $catemodel=new Cate;
        $cate=$catemodel->where('pid','=',0)->get();
        return view('index',['data'=>$data],['goodshost'=>$goodshost])
            ->with('goodsinfo',$goodsinfo)
            ->with('cate',$cate);
    }
    /*
     * @content 我的潮购
     */

    public function userpage()
    {
        return view('userpage');
    }

    /*
     * @contetn 购物车
     */
    public function shopcart()
    {
        $user_id=session('user_id');
        $res=DB::table('cart')
            ->join('goods','goods.goods_id','=','cart.goods_id')
            ->where(['user_id'=>$user_id,'cart_status'=>1])
            ->orderBy('cart_id','desc')
            ->get();
        return view('shopcart',['res'=>$res]);
    }


    /*
     * @content 所有商品
     */
    public function allshops()
    {
        //分类
        $catemodel=new Cate;
        $cate=$catemodel->where('pid','=',0)->get();
        //商品
        $goodsmodel=new Goods;
        $data=$goodsmodel->where('is_up','=',1)->orderBy('update_time','desc')->get();
        return view('allshops',['data'=>$data],['cate'=>$cate])
            ->with('id',0);
    }

    /*
     * @content 商品详情
     */
    public function shopcontent($id)
    {
        $goodsmodel=new Goods;
        $goods=$goodsmodel->where('goods_id','=',$id)->first()->toArray();
        $goods['goods_imgs']=rtrim($goods['goods_imgs'],'|');
        $goods['goods_imgs']=explode('|',$goods['goods_imgs']);
        return view('shopcontent',['goods'=>$goods]);
    }
}
