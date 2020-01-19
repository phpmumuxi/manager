<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>商品信息|<?php echo ($configcache['Title']); ?></title>
	<link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
	<link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
	<link rel="stylesheet" type="text/css" href="__JS__/layer/skin/layer.css" />
	
	<link rel="stylesheet" href="__PUBLIC__/jcrop/css/jquery.Jcrop.css" />

	
	<script type="text/javascript" src="__JS__/jquery.js"></script>
	
	
	<script type="text/javascript" src="__JS__/Public.js"></script>
	<script type="text/javascript" src="__JS__/winpop.js"></script>
	<script type="text/javascript" src="__JS__/check.js"></script>

	
	<script type="text/javascript" src="__PUBLIC__/jcrop/js/jquery.Jcrop.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/productkinds.js"></script>
	
	
    <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript"  src="__PUBLIC__/ueditor/ueditor.all.min.js"></script>

  <style type="text/css">
  .shop-1{border:1px solid #ccc; width:60%;margin-left: auto;margin-right: auto;}
  .shop-2{margin: 30px;}
  .imgbox img{max-width:90%;}
  .basetb td{ padding:6px; padding-top:28px; padding-bottom:8px; font-size:15px;text-align:left;}
  .label{text-align: center;}
  
  #target {
    background-color: #ccc;
    width: 500px;
    height: 330px;
    font-size: 24px;
    display: block;
  }
	.inputtd input{
  	 width: 100%;
    height: 100%;
    text-align:center;
  }
  
  #table tr td{
  	text-align:center;

  }
  
   
 .td1{

  	width:15%
  }
		


	
  </style>
</head>
<body>
  <dt>详情信息</dt>
  <div id="content" style="font-size:18px; color:#333; text-align:center;">
   <dl id="dl">
    <h2>
      <div class="h2_left">
        <a href="<?php echo U('Shop/index');?>" class="whole">商品</a>
        <a href="javascript:;" class="f5" onclick="f5();">刷新</a>
        <a href="javascript:history.back();" class="Retreat">后退</a>
        <a href="javascript:history.go(1);" class="Advance">前进</a>
        <input type="hidden" id="httpreferer" value="<?php echo ($httpreferer); ?>">
        <input type="hidden" id="consult_url" value="<?php echo ($url); ?>">
        
      </div>
    </h2><br/>

        <table style="width:80%" id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0" class="basetb">
        <tr><td colspan="2">管家经理资料信息</td></tr>
           <tr><td class="td1">管家类型</td>
	           <td>
	           <?php switch($info["manager_type"]): case "1": ?>A类<?php break;?>
			      	<?php case "2": ?>B类<?php break;?>
			      	<?php case "3": ?>C类<?php break;?>
		            <?php case "4": ?>D类<?php break;?>
		            <?php case "5": ?>E类<?php break;?>
		            <?php case "6": ?>F类<?php break;?>
		            <?php case "7": ?>联合体成员<?php break;?>
                <?php case "8": ?>企业管家<?php break;?>
		            <?php case "9": ?>签约管家<?php break;?>
	    			<?php default: ?>未知<?php endswitch;?>
	           </td>
           </tr>
           <tr ><td class="td1">支付金额</td><td class="td2"><?php echo ($info["pay_num"]); ?></td></tr>  
           <!-- <tr>
	           <td class="td1">业务内容</td>
	           <td class="td2">
		           <?php switch($info["manager_type"]): case "1": ?>缴纳10万费用，服务区域下特定行业，获取收益<?php break;?>
				      	<?php case "2": ?>缴纳3万6费用，服务区域下特定行业，获取收益<?php break;?>
				      	<?php case "3": ?>缴纳5千保证金，,提供服务获取收益并且可以赚取推荐奖励<?php break;?>
			            <?php case "4": ?>缴纳5千保证金，成为生产企业及贸易商，提供服务获取收益<?php break;?>
			            <?php case "5": ?>缴纳5千保证金，成为实体门店及各类服务商，提供服务获取收益<?php break;?>
			            <?php case "6": ?>平台消费满3万6，提供服务获取收益<?php break;?>
			            <?php case "7": ?>缴纳6000费用<?php break;?>
			            <?php case "8": ?>缴纳5000费用<?php break;?>
						<?php default: ?>未知<?php endswitch;?>
	           </td>
           </tr> -->
           <tr ><td class="td1">昵称</td><td class="td2"><?php echo ($info["user_nick_name"]); ?></td></tr>
           <tr ><td class="td1">姓名</td><td  class="td2"> <?php echo ($info["user_real_name"]); ?></td></tr>
           <tr ><td class="td1">身份证号</td><td  class="td2"><?php echo ($info["user_id_code"]); ?> </td></tr>
           <tr ><td class="td1">手机号</td><td  class="td2"> <?php echo ($info["user_telephone"]); ?></td></tr>
           <tr ><td class="td1">申请行业</td><td  class="td2"><?php echo ($info["user_type"]); ?> </td></tr>
           <tr ><td class="td1">服务地区</td><td  class="td2"><?php echo ($info["service_area"]); ?> </td></tr>
           <tr ><td class="td1">申请时间</td><td  class="td2"><?php echo ($info["create_time"]); ?> </td></tr>
           <tr ><td class="td1">审核时间</td><td  class="td2"><?php echo ($info["check_time"]); ?> </td></tr>
           <tr ><td class="td1">推荐时间</td><td  class="td2"><?php echo ($info["recommended_time"]); ?> </td></tr>
           <tr >
	           <td class="td1">状态</td>
	           <td  class="td2">         
	           	<?php switch($info["check_status"]): case "0": ?>待审核<?php break;?>
				    <?php case "1": ?>审核成功<?php break;?>
				    <?php case "2": ?>审核失败:<?php echo ($checkinfo["remark"]); break; endswitch;?>
	           </td>
           </tr>
           <tr><td class="td1">个人简介</td><td class="td2"><?php echo ($info["remark"]); ?> </td></tr>
      
           <tr ><td class="td1">操作员</td><td  class="td2"> <?php echo ($checkinfo["userName"]); ?></td></tr>
      </table>
</div>

<script type="text/javascript">

</script>

</body>
</html>