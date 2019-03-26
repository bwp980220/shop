<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>填写收货地址</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="css/comm.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{url('css/writeaddr.css')}}">
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{url('dist/css/LArea.css')}}">
</head>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">填写收货地址</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="javascript:;" class="m-index-icon">保存</a>
</div>
<div class=""></div>
<!-- <form class="layui-form" action="">
  <input type="checkbox" name="xxx" lay-skin="switch">  
  
</form> -->
<form class="" action="">
  <div class="addrcon">
    <ul>
      <li><em>收货人</em><input type="text" placeholder="请填写真实姓名" name="address_name" id="address_name"></li>
      <li><em>手机号码</em><input type="number" placeholder="请输入手机号" name="address_tel" id="address_tel"></li>
      <li>
      <em>地址</em>
        <select class="area" style="background-color:#f6f6f6;" id="province">
          <option value="" selected="selected">--请选择--</option>
          @foreach($provinceInfo as $v)
          <option value="{{$v->id}}">{{$v->name}}</option>
          @endforeach
        </select>
        <select class="area" id="city">
            <option value="" selected="selected">--请选择--</option> 
        </select>
        <select class="area" id="area">
            <option value="" selected="selected">--请选择--</option>
        </select>
        （必填）
    </li>
      <li class="addr-detail"><em>详细地址</em><input type="text" name="address_desc" placeholder="20个字以内" id="addr"></li>
      <li>
        是否设置为默认收货地址
        <input type="radio" id="address_default" value="1">
      </li>
    </ul>
    @csrf
</form>

<!-- SUI mobile -->
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>

<script>
  //Demo
layui.use('form', function(){
  var form = layui.form();
  
  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});
</script>
<script>
    $(function(){
      layui.use('layer',function(){
        var layer = layui.layer;
    //三级联动        
    $(".area").change(function(){
        
          var _this=$(this);
          //console.log(_this);  
          var _id=_this.val();
          //console.log(id);
          var _option="<option selected value=''>--请选择--</option>"
          _this.nextAll('select').html(_option);
          $.get(
            "{{url('getarea')}}"+"/"+_id,
            {id:_id,_token:'{{csrf_token()}}'},
            function(res){
              if(res.icon==1){
                for(var i in res['areaInfo']){
                  _option+="<option value='"+res['areaInfo'][i]['id']+"'>"+res['areaInfo'][i]['name']+"<option>"
                }
                // console.log(_option);
                _this.next('select').html(_option);
              }else{
                layer.msg(res.font,{icon:res.icon})
              }
              //console.log(res);
            }
            ,'json'
          )
        })
        /* 默认 */
    })
    //添加
    $(document).on('click','.m-index-icon',function(){
            var obj={};
            //var province=$('#province').val();
            obj.province=$('#province').val();
            //console.log(obj.province);
            obj.city=$('#city').val();
            obj.area=$('#area').val();
            obj.address_name=$('#address_name').val();
            obj.address_tel=$('#address_tel').val();
            obj.address_detail=$('#address_desc').val();
            var address_default=$('#address_default').prop('checked');
            if(address_default==true){
              obj.address_default=1;
            }else{
              obj.address_default=2; 
            }
            //console.log(obj);
            //验证
            if(obj.province==''){
                layer.msg('请选择完整的配送地区');
                return false;  
            }
            //添加
            $.get(
              "{{url('writeaddrdo')}}",
              obj,
              function(res){
                //console.log(res);
                  if(res==1){
                    layer.msg('添加成功',{icon:res},function(){  
                      location.href="{{url('address')}}";                 
                    });
                  }else{
                    layer.msg('添加失败',{icon:res},function(){                     
                    });
                  }
              }
              ,'json'
            )        
        })
  })

</script>


</body>
</html>
