<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>合同管理|<?php echo ($configcache['Title']); ?></title>
    <link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
    <link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
    <script type="text/javascript" src="__JS__/jquery.js"></script>
    <script type="text/javascript" src="__JS__/Public.js"></script>
    <script type="text/javascript" src="__JS__/winpop.js"></script>
    <script type="text/javascript" src="__JS__/My97DatePicker/WdatePicker.js"></script>
    <style>
    .sure{left:220px; top:10px; width:60px; height:28px; line-height:28px; border:none; background:#090; color:#fff; font-family:"微软雅黑"; cursor:pointer;}
   .tel{left:0; top:10px; width:220px; height:26px; line-height:26px; color:#555; font-family:"微软雅黑"; repeat-x left top; border:solid 1px #ccc;}
    .nav_div{
        border-bottom:1px solid #333333;    
        /*width:100%;*/
        height:50px;
        margin-top: 30px;    
    }
    .nav_div ul{
        list-style:none;
        text-align:center;
        cursor:pointer; 
        margin-left: 0;
    }
    .nav_div ul li{
        float:left;
        height:49px;
        width:128px;
        line-height:50px;
    }
    .nav_active{
        color: #169BD5;
        border-top: 1px solid #333333;
        border-left: 1px solid #333333;
        border-right: 1px solid #333333;
        border-bottom: 2px solid white;
    }
    .sign_button{
        border:1px solid #ddd;
        padding: 6px 14px;
        border-radius: 5px;
        cursor: pointer;
        margin:0 10px;
        background: #058BC6;
        color: white;
    }
  </style> 
</head>
<body>
<div id="content">
    <h1>首页 > 资料审核管理 > 合同管理</h1>
    <h2>
        <div class="h2_left">
            <a href="__ACTION__" class="whole">全部</a>
            <a href="javascript:;" class="f5" onclick="f5();">刷新</a>
            <a href="javascript:history.back();" class="Retreat">后退</a>
            <a href="javascript:history.go(1);" class="Advance">前进</a>
        </div>
    </h2>
    <div class="nav_div">
        <ul>
            <a href="<?php echo U('Client/contractmanager');?>"><li class="nav_active">已签署</li></a>
             <a href="<?php echo U('Client/contractList');?>"><li >合同管理</li></a>
        </ul>
        <p style="clear: both"></p>
    </div>
    <div style="margin:30px 0;">
        <form method="post">
            <input type="text" name="keyWord" class="tel" value="<?php echo ($keyWord); ?>" placeholder='手机号/发起人'>
            &nbsp;&nbsp;&nbsp;发起时间: &nbsp;&nbsp;&nbsp;
            <input value="<?php echo ($start_date); ?>" type="text" id="start_date" name="start_date" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2012-01-01',maxDate:&quot;#F{$dp.$D('end_date')||'2038-01-01'}&quot;})" class="Wdate sBeginTime"/>&nbsp;~
            <input value="<?php echo ($end_date); ?>" type="text" id="end_date" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss', minDate:&quot;#F{$dp.$D(\'start_date\')}&quot;,maxDate:'2038-01-01'})" name="end_date" class="Wdate sEndTime"/>&nbsp;&nbsp;&nbsp;
            <input type="submit" class="sure" value="搜索" />
        </form>
    </div>
    <?php if($_SESSION['ThinkUser']['ID']!= 39): ?><div style="margin:20px 0;">
            <button vb="1" class="sign_button sure_sign">同意签署</button>
            <button vb="2" class="sign_button cancel_sign">作废</button>
            <button vb="3" class="sign_button delete_sign">删除</button>
        </div><?php endif; ?>
    <table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
        <tr>
            <th><input type="checkbox" id="check_all"> 全选</th>
            <th>合同标题</th>
            <th>发起人</th>
            <th>发起人手机号</th>
            <th>营业执照</th>
            <th>发起时间</th>
            <th>签署时间</th>
            <th>推荐人</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td style="text-align:center"><input type="checkbox" vd="<?php echo ($vo["id"]); ?>" vs="<?php echo ($vo["status"]); ?>" class="check_list"></td>
                <td style="text-align:center" class='tc'><?php echo ($vo["title"]); ?></td>
                <td style="text-align:center" class='tc'><?php echo ($vo["sign_user"]); ?></td>
                <td style="text-align:center" class='tc'><?php echo ($vo["sign_user_tel"]); ?></td>
                <td style="text-align:center" class='tc'><img src="<?php echo ($vo["business_img"]); ?>" style="width: 100px;height: 100px;margin-top: 10px"/></td>
                <td style="text-align:center" class='tc'><?php echo ($vo["create_time"]); ?></td>
                <td style="text-align:center" class='tc'><?php if($vo['status'] != 0): echo ($vo["sign_time"]); endif; ?></td>
                <td style="text-align:center" class='tc'><?php echo ($vo["recom_user_tel"]); ?></td>
                <td class='tc' style="text-align:center">
                    <?php switch($vo["status"]): case "0": ?>待签署<?php break;?>
                    <?php case "1": ?>已签署<?php break;?>
                    <?php case "2": ?>已作废<?php break;?>
                    <?php default: endswitch;?>
                </td>
                <td class='tc' style="text-align:center">
                    <a href="__APP__/Client/signInfo?id=<?php echo ($vo["id"]); ?>" class="look" style="margin-right: 5px;color:blue">签署信息</a>
                </td>
            </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
    </table>
    <div id="page">
        <?php echo ($page); ?>
    </div>
</div>
<script src="__PUBLIC__/js/layer/layer.js"></script>
<script>
//全选中
$("#check_all").click(function() {
    $("input[class='check_list']").prop('checked', $(this).prop("checked"));
});

$('.sure_sign,.cancel_sign,.delete_sign').click(function(){  
    var checked_ids = [];
    var checked_status = [];
    $.each($("input[class='check_list']:checked"),function(){
         checked_ids.push($(this).attr("vd"));       
         checked_status.push($(this).attr("vs"));       
    });
    
    if(checked_ids.length==0){
        layer.alert('当前未选中任何一个合同!',{icon: 5});
        return false;
    }
    var _type=$(this).attr('vb');
    if(_type == '1'){ 
        if($.inArray("1", checked_status) >= 0 || $.inArray("2", checked_status) >= 0){
            layer.alert('只能签署待签署的合同!',{icon: 2});
            return false;
        }
        layer.confirm('确认签署？确认后双方将完成在线签约!', function(index){
            changeAllStatus(checked_ids,_type)
            layer.close(index);
        });       
    }else if(_type == '2'){
        if($.inArray("2", checked_status) >= 0){
            layer.alert('只能作废待签署和已签署的合同!',{icon: 2});
            return false;
        }
        layer.confirm('确认作废？作废后则双方签约失败!', function(index){
            changeAllStatus(checked_ids,_type)
            layer.close(index);
        });
    }else if(_type == '3'){
        if($.inArray("0", checked_status) >= 0 || $.inArray("1", checked_status) >= 0){
            layer.alert('只能删除已作废的合同!',{icon: 2});
            return false;
        }
        layer.confirm('确认删除？删除后不可恢复!', function(index){
            changeAllStatus(checked_ids,_type)
            layer.close(index);
        });
    }else{

    }
 })  

 function changeAllStatus(_ids,_ty){    
    $.ajax({
        type: "POST",
        url: "__APP__/Client/changeAllStatus",
        data: {ids :_ids,status:_ty},
        success: function(res){
          if(res.success){
            f5();
          }else{
            layer.alert(res.message);
          }
        },
        error:function(){
            layer.alert('网络异常!');
        }
    });
 } 

</script>
</body>
</html>