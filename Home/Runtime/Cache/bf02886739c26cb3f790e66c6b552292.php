<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>管家经理|<?php echo ($configcache['Title']); ?></title>
  <link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
  <link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
  <script type="text/javascript" src="__JS__/jquery.js"></script>
  <script type="text/javascript" src="__JS__/Public.js"></script>
  <script type="text/javascript" src="__JS__/winpop.js"></script>
  <script type="text/javascript" src="__JS__/My97DatePicker/WdatePicker.js"></script>
  <script src="__PUBLIC__/js/layer/layer.js" type="text/javascript"></script>
  <style type="text/css">
    td,th{
    	text-align:center;
    }
   .nav_div{
        border-bottom:1px solid #333333;
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
    .tel{left:0; top:10px; width:220px; height:26px; line-height:26px; color:#555; font-family:"微软雅黑"; repeat-x left top; border:solid 1px #ccc;}
    .sure{left:220px; top:10px; width:60px; height:28px; line-height:28px; border:none; background:#090; color:#fff; font-family:"微软雅黑"; cursor:pointer;}
  </style>
</head>
<body>
  <div id="content">
   <h1>首页 > 资料审核 >管家经理报名</h1>
   <h2>
     <div class="h2_left">
       <a href="__ACTION__" class="whole">全部</a>
       <a href="javascript:;" class="f5" onclick="f5();">刷新</a>
       <a href="javascript:history.back();" class="Retreat">后退</a>
       <a href="javascript:history.go(1);" class="Advance">前进</a>
     </div>
    <input type="hidden" id="url" value="<?php echo ($url); ?>">     
  </h2>
<div class="nav_div">
    <ul>
        <a href="<?php echo U('Client/housemanager');?>"><li>已申请管家</li></a>
        <a href="<?php echo U('Client/publicmanager');?>"><li>公益管家</li></a>
        <a href="<?php echo U('Client/noStatistic');?>"><li class="nav_active">待申请管家</li></a>
    </ul>
    <p style="clear: both"></p>
</div>
<ul style="height:60px;margin-top:25px;margin-bottom: 10px;">     
  <li>
    <form method="post">
      <div>
          <input type="text" name="user_telephone" class="tel"  placeholder="小贴士：请输入手机号" value="<?php echo ($user_telephone); ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" class="sure" value="查询" /> 
      </div>
    </form>
  </li> 
</ul>	
<table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
   <tr>
     <th width="10%">手机号</th>  
     <th width="6%">申请行业</th>     
     <th width="8%">服务地区</th>  
     <th width="10%">服务截止时间</th>  
     <th width="10%">提交时间</th>     
     <th width="10%">个人备注</th>     
   </tr>
   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_telephone"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["industry_type"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["service_area"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["service_time"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["create_time"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_remark"]); ?></td>
    </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>

<div id="page">
  <?php echo ($page); ?>   每页显示<select id="selectpagesize" style="width:50px;display:inline-block;">
 										<option ><?php echo ($pageSize); ?></option>
										<option value="10">10</option>
										<option value="5">5</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="30">30</option>
										<option value="40">40</option>
										<option value="50">50</option>
								</select>  条</div>
</div>
</body>
</html>