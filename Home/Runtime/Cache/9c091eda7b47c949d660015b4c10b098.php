<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>用户管理|<?php echo ($configcache['Title']); ?></title>
  <link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
  <link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
  <script type="text/javascript" src="__JS__/jquery.js"></script>
  <script type="text/javascript" src="__JS__/Public.js"></script>
  <script type="text/javascript" src="__JS__/winpop.js"></script>
  <script type="text/javascript" src="__JS__/My97DatePicker/WdatePicker.js"></script>
  <style type="text/css">
  .select{padding:5px 10px;border:#ddd 1px solid;border-radius:4px;width:60%;margin:5% auto;font-size:12px}
  .select li{list-style:none;padding:10px 0 5px 100px}
  .select .select-list{border-bottom:#eee 1px dashed}
  .select dl{zoom:1;position:relative;line-height:24px;}
  .select dl:after{content:" ";display:block;clear:both;height:0;overflow:hidden}
  .select dt{width:100px;margin-bottom:5px;position:absolute;top:0;left:-100px;text-align:right;color:#666;height:24px;line-height:24px}
  .select dd{float:left;display:inline;margin:0 0 5px 5px;}
  .select a{display:inline-block;white-space:nowrap;height:24px;padding:0 10px;text-decoration:none;color:#039;border-radius:2px;}
  .select a:hover{color:#f60;background-color:#f3edc2}
  .select .selected a{color:#fff;background-color:#f60}
  .select-result dt{font-weight:bold}
  .select-no{color:#999}
  .select .select-result a{padding-right:20px;background:#f60 url("__PUBLIC__/image/close.gif") right 9px no-repeat}
  .select .select-result a:hover{background-position:right -15px}
  .tc{text-align:center; text-indent:0;font-family:"微软雅黑";font-size: 14px;color:#111;}
  </style>
</head>
<body>
  <div id="content">
   <h1>首页 > 客户管理 > 客户库</h1>
   <h2>
     <div class="h2_left">
       <a href="__ACTION__" class="whole">全部</a>
       <a href="javascript:;" class="f5" onclick="f5();">刷新</a>
       <a href="javascript:history.back();" class="Retreat">后退</a>
       <a href="javascript:history.go(1);" class="Advance">前进</a>
     </div>
     <div class="search">
      <form method="get" id="search">
        <input type="text" name="k" class="text" value="<?php echo ($data['key']); ?>"/>
        <input type="submit" class="so" value="搜 索" />
      </form>
      <font>小贴士：输入昵称/手机号</font>
    </div>
  </h2>

  <div class="fen">
    <ul class="select" style="height:310px;margin-top: 20px;margin-bottom: 20px;">
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>用户角色：</dt>
            <dd <?php if($data['userRole'] == -1): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/-1">全部</a>
            </dd>          
            <dd <?php if($data['userRole'] == 1): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/1">区域总代理</a>
            </dd>
            <dd <?php if($data['userRole'] == 2): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/2">贸易管家代理商</a>
            </dd>
            <dd <?php if($data['userRole'] == 3): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/3">贸易管家贸易工厂</a>
            </dd>
            <dd <?php if($data['userRole'] == 4): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/4">社区管家代理商</a>
            </dd>
            <dd <?php if($data['userRole'] == 5): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userRole/5">社区管家商铺</a>
            </dd>          
        </dl>
      </li>
      <li class="select-list List_more">
        <dl id="select">
          <dt>实名认证：</dt>
            <dd <?php if(($data['userApproveStatus']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userApproveStatus/-1">全部</a>
            </dd>
            <dd <?php if(($data['userApproveStatus']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userApproveStatus/0">未认证</a>
            </dd>
            <dd <?php if(($data['userApproveStatus']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userApproveStatus/1">待认证</a>
            </dd>
            <dd <?php if(($data['userApproveStatus']) == "2"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userApproveStatus/2">已认证</a>
            </dd>
            <dd <?php if(($data['userApproveStatus']) == "3"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userApproveStatus/3">认证未通过</a>
            </dd>            
        </dl>
      </li>
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>成为管家：</dt>
            <dd <?php if(($data['isHouseManager']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isHouseManager/-1/isHousekeeperNetred/-1">全部</a>
            </dd>
            <dd <?php if(($data['isHouseManager']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isHouseManager/0/isHousekeeperNetred/0">普通用户</a>
            </dd>
            <dd <?php if(($data['isHouseManager']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isHouseManager/1">私人管家</a>
            </dd>
            <dd <?php if(($data['isHouseManager']) == "2"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isHousekeeperNetred/1">网红管家</a>
            </dd>
        </dl>
      </li>
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>成为明星逸专员：</dt>
            <dd <?php if(($data['userIsStar']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userIsStar/-1">全部</a>
            </dd>
            <dd <?php if(($data['userIsStar']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userIsStar/0">未成为明星逸专员</a>
            </dd>
            <dd <?php if(($data['userIsStar']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/userIsStar/1">已成为明星逸专员</a>
            </dd>            
        </dl>
      </li>
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>成为代理商：</dt>
            <dd <?php if(($data['isAgent']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isAgent/-1">全部</a>
            </dd>
            <dd <?php if(($data['isAgent']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isAgent/0">未成为代理商</a>
            </dd>
            <dd <?php if(($data['isAgent']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/isAgent/1">已成为代理商</a>
            </dd>            
        </dl>
      </li>
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>视频上传权限：</dt>
            <dd <?php if(($data['whiteListStatus']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/whiteListStatus/-1">全部</a>
            </dd>
            <dd <?php if(($data['whiteListStatus']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/whiteListStatus/0">无权限</a>
            </dd>
            <dd <?php if(($data['whiteListStatus']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/whiteListStatus/1">有权限</a>
            </dd>            
        </dl>
      </li>
      <li class="select-list List_more"> 
        <dl id="select">
          <dt>用户状态：</dt>
            <dd <?php if(($data['registerType']) == "-1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/registerType/-1">全部</a>
            </dd>
            <dd <?php if(($data['registerType']) == "0"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/registerType/0">老用户</a>
            </dd>
            <dd <?php if(($data['registerType']) == "1"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/registerType/1">新用户（非会员）</a>
            </dd>
            <dd <?php if(($data['registerType']) == "2"): ?>class="selected select-all"<?php endif; ?>>
              <a href="__APP__/Client/index/registerType/2">新用户（会员）</a>
            </dd>            
        </dl>
      </li>      
    </ul>
  </div>
  <table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
   <tr>
     <th>昵称</th>
     <th>手机号</th>
     <th>真实姓名</th>
     <th>实名认证</th>
     <th>持股金额</th>
     <th>积分</th>
     <th>保证金</th>
     <th>用户角色</th>
     <th>成为管家</th>
     <th>协议状态</th>
     <th>荣誉值</th>
     <th>账号状态</th>
     <th>是否为明星逸专员</th>
     <th>代理商</th>
     <th>注册时间</th>
     <th>操作</th>       
   </tr>
  <?php if(count($user)): if(is_array($user)): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td style="text-align:center" class='tc'><?php echo ($vo["userNickName"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["userTelephone"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["userRealName"]); ?></td>
      <td style="text-align:center" class='tc'>
        <!-- 使用switch标签判断输出 -->
        <?php switch($vo["userApproveStatus"]): case "0": ?>未认证<?php break;?>
          <?php case "1": ?>待认证<?php break;?>
          <?php case "2": ?>已认证<?php break;?>
          <?php case "3": ?>认证未通过<?php break;?>
          <?php default: endswitch;?>
      </td>
      <td class="tc" style="text-align: center"><?php echo ($vo["userStockNum"]); ?></td>
      <td class="tc" style="text-align: center"><?php echo ($vo["memberPoint"]); ?></td>
      <td class="tc" style="text-align: center"><?php echo ($vo["memberPoint"]); ?></td>
      <td style="text-align:center" class='tc'>
        <?php switch($vo["userRole"]): case "1": ?>区域总代理<?php break;?>
          <?php case "2": ?>贸易管家代理商<?php break;?>
          <?php case "3": ?>贸易管家贸易工厂<?php break;?>
          <?php case "4": ?>社区管家代理商<?php break;?>
          <?php case "5": ?>社区管家商铺<?php break;?>
          <?php default: endswitch;?>
      </td>
      <td style="text-align:center" class='tc'>
        <?php if($vo["isHouseManager"] == 1): ?>私人管家<?php endif; ?> 
        <?php if($vo["isHousekeeperNetred"] == 1): ?>网红管家<?php endif; ?>
      </td>
      <td style="text-align:center" class='tc'>
        <?php switch($vo["isSign"]): case "0": ?>未签署<?php break;?>
          <?php case "1": ?>已签署<?php break;?>
          <?php default: endswitch;?>
      </td>
      <td class="tc" style="text-align: center"><?php echo ($vo["memberGlory"]); ?></td>
      <td class='tc' style="text-align:center">
        <?php switch($vo["userApproveStatus"]): case "0": ?>未认证<?php break;?>
          <?php case "1": ?>待认证<?php break;?>
          <?php case "2": ?>已认证<?php break;?>
          <?php case "3": ?>认证未通过<?php break;?>
          <?php default: endswitch;?>
      </td>
      <td class='tc' style="text-align:center">
        <?php switch($vo["userIsStar"]): case "0": ?><a href="javascript:;" class="star" approveStatus="3" style="color:#009900" sid='<?php echo ($vo["sid"]); ?>'>设为VIP管家</a><?php break;?>
          <?php case "1": ?><a href="javascript:;" class="nostar" approveStatus="3" style="color:#009900" sid='<?php echo ($vo["sid"]); ?>'>取消VIP管家</a><?php break;?>
          <?php default: ?><a href="javascript:;" class="star" approveStatus="3" style="color:#009900" sid='<?php echo ($vo["sid"]); ?>'>设为VIP管家</a><?php endswitch;?>
      </td>
      <td class="tc" style="text-align: center">
        <?php if($vo['isAgent'] == 0): ?><a href="javascript:;" class="isagent" style="color:#FF9933"  userId='<?php echo ($vo["sid"]); ?>' agent='1'>设置为代理商</a>
          <?php elseif($vo['isAgent'] == 1): ?>
          <a href="javascript:;" class="isagent"  style="color:#FF9933"  userId='<?php echo ($vo["sid"]); ?>' agent='0'>取消代理商</a>
          <?php else: endif; ?>
      </td>
      <td class="tc" style="text-align: center"><?php echo ($vo["userCreateDate"]); ?></td>
      <td class='tc' style="text-align:center">
        <a href="<?php echo U('Client/edit');?>?sid=<?php echo ($vo["sid"]); ?>" class="" style="color:orange;" sid='<?php echo ($vo["sid"]); ?>'>编辑</a>&nbsp;&nbsp;
        <!-- 使用switch标签判断输出 -->
        <?php switch($vo["userApproveStatus"]): case "0": ?><a href="javascript:;" class="details" approveStatus="4" style="color:#0000FF" sid='<?php echo ($vo["sid"]); ?>' name='<?php echo ($vo["userNickName"]); ?>'>详情</a><?php break;?>
         <?php case "1": ?><a href="javascript:;" class="details" approveStatus="4" style="color:#0000FF" sid='<?php echo ($vo["sid"]); ?>' name='<?php echo ($vo["userNickName"]); ?>'>详情</a>&nbsp;&nbsp;<a href="javascript:;" class="pass" approveStatus="2" style="color:#FF9933" sid='<?php echo ($vo["sid"]); ?>' userId='<?php echo ($admin["ID"]); ?>' userName='<?php echo ($admin["Username"]); ?>'>通过</a>&nbsp;&nbsp;<a href="javascript:;" class="nopass" approveStatus="3" style="color:#FF9933" sid='<?php echo ($vo["sid"]); ?>' userId='<?php echo ($admin["ID"]); ?>' userName='<?php echo ($admin["Username"]); ?>'>不通过</a><?php break;?>
         <?php case "2": ?><a href="javascript:;" class="details" approveStatus="4" style="color:#0000FF" sid='<?php echo ($vo["sid"]); ?>' name='<?php echo ($vo["userNickName"]); ?>'>详情</a><?php break;?>
         <?php case "3": ?><a href="javascript:;" class="details" approveStatus="4" style="color:#0000FF" sid='<?php echo ($vo["sid"]); ?>' name='<?php echo ($vo["userNickName"]); ?>'>详情</a><?php break;?>
         <?php default: ?>未知<?php endswitch;?>
     </td>
   </tr><?php endforeach; endif; else: echo "" ;endif; ?>
  <?php else: ?>
      <tr><td style="text-align:center" colspan="16" class='tc'>当前请求无数据</td></tr><?php endif; ?>
</table>
<div id="page">
  <?php echo ($page); ?>
</div>
</div>
<script type="text/javascript">
$(function (){
 $('.nopass').click(function(){
  var approveStatus = parseInt($(this).attr('approveStatus'));
  var sid= ($(this).attr('sid'));
  var checkUserId = $(this).attr('userId');
  var checkUserName = $(this).attr('Username');
  popload('拒绝原因',600,300,'__APP__/Client/nopa/sid/'+sid+'/approveStatus/'+approveStatus+'/checkUserId/'+checkUserId+'/checkUserName/'+checkUserName);
  addDiv($('#iframe_pop'));
  popclose();
 // location.reload();
});

 $('.pass').click(function(){
  var approveStatus = parseInt($(this).attr('approveStatus'));
  var sid= ($(this).attr('sid'));
  var userId = $(this).attr('userId');
  var Username = $(this).attr('Username');
  $.ajax({
   type: "POST",
   url: "<?php echo ($url); ?>/manager/user/applyUser",
   data: {approveStatus:approveStatus,sid:sid,userId:userId,Username:Username}, 
   success: function(msg){
    alert(msg.message);
    f5();
  }
});
});



 $('.details').click(function(){
  var approveStatus = parseInt($(this).attr('approveStatus'));
  var sid= ($(this).attr('sid'));
  event.preventDefault();
  popload('详情信息',860,800,'__APP__/Client/user/sid/'+sid);
  addDiv($('#iframe_pop'));
  popclose();
});


//登录日志
$('.login').click(function(){
  var sid= ($(this).attr('sid'));
  var userNickName = $(this).attr('userNickName');
  event.preventDefault();
  popload('登录日志',960,800,'__APP__/Client/login/userNickName/'+userNickName);
  addDiv($('#iframe_pop'));
  popclose();
});
});


//设为明星专员
$('.star').click(function(){
  var sid= ($(this).attr('sid'));

  $.ajax({
   type: "POST",
   url: "<?php echo ($url); ?>/manager/user/changeUserIsStar",
   data: {userId :sid,isStar:'true'}, 
   success: function(msg){
    if(msg.code == "2000"){
      alert(msg.message);
      f5();
    }
  },
  error:function(){
    alert('异常!');
  }
});

});

//取消明星专员
$('.nostar').click(function(){
  var sid= ($(this).attr('sid'));

  $.ajax({
   type: "POST",
   url: "<?php echo ($url); ?>/manager/user/changeUserIsStar",
   data: {userId :sid,isStar:'false'}, 
   success: function(msg){
    if(msg.code == "2000"){
      alert(msg.message);
      f5();
    }

  },
  error:function(){
    alert('异常!');
  }
});

});

$('.isagent').click(function(){
  var userId= ($(this).attr('userId'));
  var isAgent = ($(this).attr('agent'));
  $.ajax({
   type: "POST",
   url: "<?php echo ($url); ?>/manager/user/setAgent",
   data: {userId:userId,isAgent:isAgent}, 
   success: function(msg){
    alert('操作成功');
    f5();
  }
});
});
</script>
</body>
</html>