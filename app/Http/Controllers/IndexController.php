<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Cate;
use App\Models\Address;
use App\Models\Area;
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
    /*
     * @content 收货地址
     */
    public function address()
    {
        $addressmodel=new Address;
        $data=$addressmodel->get();
        return view('address',['res'=>$data]);
    }
    /*
     * @content 添加收货地址
     */
    public function writeaddr()
    {
        //查询所有省收货地址
        $provinceInfo=$this->getAreaInfo(0);
        //dump($provinceInfo);die;
        //展示添加视图
        $addressInfo=$this->getAddressInfo();
        return view('writeaddr',['provinceInfo'=>$provinceInfo,'addressInfo'=>$addressInfo]);
    }
    public function getAreaInfo($pid){
        $where=[
            'pid'=>$pid,
        ];
        $area_model=new Area;
        $data=$area_model->where($where)->get();
        if(!empty($data)){
            return $data;
        }else{
            return false;
        }    
    }
    public function getAddressInfo(){
        $where=[
            'user_id'=>$this->getUserId(),
            'address_status'=>1
        ];
        $address_model=new Address;
        $area_model=new Area;
        $addressInfo=$address_model->where($where)->get();
        if(!empty($addressInfo)){
            //处理收货地址的省市区
            foreach($addressInfo as $k=>$v){
                $addressInfo[$k]['province']=$area_model->where(['id'=>$v['province']])->value('name');
                $addressInfo[$k]['city']=$area_model->where(['id'=>$v['city']])->value('name');
                $addressInfo[$k]['area']=$area_model->where(['id'=>$v['area']])->value('name');    
            }
            return $addressInfo;
        }else{
            return false;
        }

    } 
    //获取下一级区域信息
    public function getarea(Request $request){
        $id=$request->id;
        //dump($id);die;
        if(empty($id)){
            fail('请必须选择一项');
        }
        $areaInfo=$this->getAreaInfo($id);
        //print_r($areaInfo);exit;
        echo json_encode(['areaInfo'=>$areaInfo,'icon'=>1]);   
    }
    //获取用户id
    public function getUserId(){
        return session('user_id');
    }
    /**
    * $goods_id 商品id
    * $num 已购买的数量
    * $buy_num 新购买的数量
    *  */
   //添加收货地址
   public function writeaddrdo(Request $request){
        $data=$request->all();
        //dump($data);die;
        $address_model=new Address;
        if($data['address_default']==1){
            $where=[
                'user_id'=>$this->getUserId()
            ];
            $address_model->startTrans();
            $result=$address_model->save(['address_default'=>2],$where);//改
            $res=$address_model->isupdate(false)->save($data);//增
            //dump($res);exit;
            if($result!==false&&$res){
               echo 1;
            }else{
                echo 2;
            }
        }else{
            $res=DB::table('address')->insert($data);
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }
    }
}
