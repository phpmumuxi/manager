<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户信息|<?php echo ($configcache['Title']); ?></title>
<link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
<link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
<script type="text/javascript" src="__JS__/jquery.js"></script>
<script type="text/javascript" src="__JS__/Public.js"></script>
<script type="text/javascript" src="__JS__/winpop.js"></script>
<script type="text/javascript" src="__JS__/check.js"></script>
<script>
$(document).ready(function() {
    $('.button').click(function() {
      var id=$('#dl input:hidden').val();
      alert(id);
        $.ajax({
            url:'',
            dataType:'json',
            type:'POST',
            data:{id:id},
            success: function(data) {
                
            }
        });
    });
});
</script>
</head>
<body>
<div id="content">
	<dl id="dl">
    <dt>用户详情信息</dt>
        <a href="<?php echo ($user["headPicUrl"]); ?>" title="图片详情" target='_blank'><img src="<?php echo ($user["headPicUrl"]); ?>" width="190" height="180" style="margin:5px;"></a>
        <a href="<?php echo ($user["headSelfPicUrl"]); ?>" title="图片详情" target='_blank'><img src="<?php echo ($user["headSelfPicUrl"]); ?>" width="190" height="180" style="margin:5px;"></a>
        <a href="<?php echo ($user["idCodeFrontPic"]); ?>" title="图片详情" target='_blank'><img src="<?php echo ($user["idCodeFrontPic"]); ?>" width="190" height="180" style="margin:5px;"></a>
        <a href="<?php echo ($user["idCodeBackPic"]); ?>" title="图片详情" target='_blank'><img src="<?php echo ($user["idCodeBackPic"]); ?>" width="190" height="180" style="margin:5px;"></a>
        <dd>
            <span class="dd_left">昵 称：</span>
            <span class="dd_right"><font><?php echo ($user["userNickName"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">真实姓名：</span>
            <span class="dd_right"><font><?php echo ($user["userRealName"]); ?></font><?php if($user["userIsStar"] == 1 ): ?>(明星专员)<?php else: endif; ?></span>
        </dd>
        <dd>
            <span class="dd_left">身份证号：</span>
            <span class="dd_right"><font><?php echo ($user["userIdCode"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">地址：</span>
            <span class="dd_right"><font><?php echo ($user["userAddress"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">年龄：</span>
            <span class="dd_right"><font><?php echo ($user["userAge"]); ?></font></span>
        </dd>
        <dd>
        	<span class="dd_left">性 别：</span>
        	<span class="dd_right"><font>
            <?php switch($user["userSex"]): case "0": ?>男<?php break;?>
                <?php case "1": ?>女<?php break;?>
                <?php default: ?>未知<?php endswitch;?></font></span>
        </dd>
        <dd>
            <span class="dd_left">手机号：</span>
            <span class="dd_right"><font><?php echo ($user["userTelephone"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">微信号：</span>
            <span class="dd_right"><font><?php echo ($user["userWx"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">QQ号：</span>
            <span class="dd_right"><font><?php echo ($user["userQq"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">服务城市：</span>
            <span class="dd_right"><font><?php echo ($user["userServiceCity"]); ?></font></span>
        </dd>
       
        <dd>
            <span class="dd_left">服务状态：</span>
            <span class="dd_right"><font><?php if($user["userServiceStatus"] == 1 ): ?>开启<?php else: ?>关闭<?php endif; ?></font></span>
        </dd>

        <dd>
            <span class="dd_left">账号状态：</span>
            <span class="dd_right"><font>
            <?php switch($user["userApproveStatus"]): case "0": ?>未认证<?php break;?>
            <?php case "1": ?>待认证<?php break;?>
            <?php case "2": ?>已认证<?php break;?>
            <?php case "3": ?>认证未通过<?php break;?>
            <?php default: ?>未知<?php endswitch;?>
            </font></span>
        </dd>

 <!--        <dd>
            <span class="dd_left">公司名称：</span>
            <span class="dd_right"><font><?php echo ($user["userCompany"]); ?></font></span>
        </dd>
        -->
        <dd>
            <span class="dd_left">注册时间：</span>
            <span class="dd_right"><font><?php echo ($user["userCreateDate"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left" style="width:86px;">认证时间：</span>
            <span class="dd_right"><font><?php echo ($user["userApprovedDatetime"]); ?></font></span>
        </dd>
        <dd>
            <span class="dd_left">余额：</span>
            <span class="dd_right"><font><?php echo ($user["userBalance"]); ?></font></span>
        </dd>        
        <?php switch($user["userApproveStatus"]): case "3": ?><dd>
                    <span class="dd_left">拒绝原因：</span>
                    <span class="dd_right"><font><?php echo ($user["userRefusalReason"]); ?></font></span>
                </dd><?php break;?>
            <?php default: endswitch;?>        
        <dd>
            <span class="dd_left">是否付费：</span>
            <span class="dd_right">
                <?php switch($user["registerType"]): case "0": ?>老用户未付款<?php break;?>
                <?php case "1": ?>未支付<?php echo ($user["payMoney"]); ?>（订单号<?php echo ($user["payNo"]); ?>）<?php break;?>
                <?php case "2": ?>已付款<?php echo ($user["payMoney"]); ?> （订单号<?php echo ($user["payNo"]); ?>）<?php break;?>
                <?php default: endswitch;?>
            </span>
        </dd>
    </dl>
</div>
</body>
</html>