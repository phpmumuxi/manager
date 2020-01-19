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
        <tr><td colspan="2">网红管家资料信息</td></tr>
           <tr><td class="td1">管家类型</td><td>网红管家</td></tr>
           <tr ><td class="td1">图片</td>
             <td class="td2">
                <?php if(is_array($info["picture"])): $i = 0; $__LIST__ = $info["picture"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["picUrl"]); ?>" target='_blank'><img src="<?php echo ($vo["picUrl"]); ?>" width="100" height="100" style="margin:5px;"></a><?php endforeach; endif; else: echo "" ;endif; ?>
             </td>
           </tr>
           <tr ><td class="td1">手机号</td><td  class="td2"> <?php echo ($info["user"]["userTelephone"]); ?></td></tr>  
           <tr ><td class="td1">微信</td><td  class="td2"> <?php echo ($info["user"]["userWX"]); ?></td></tr>  
           <tr ><td class="td1">QQ</td><td  class="td2"> <?php echo ($info["user"]["userQQ"]); ?></td></tr>
           <?php if(is_array($info["platform"])): $i = 0; $__LIST__ = $info["platform"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr ><td class="td1">所在平台</td><td class="td2"><?php echo ($vo["platformSource"]); ?></td></tr>
             <tr ><td class="td1">平台ID</td><td  class="td2"> <?php echo ($vo["platformNo"]); ?></td></tr>
             <tr ><td class="td1">昵称</td><td  class="td2"><?php echo ($vo["platformName"]); ?> </td></tr>           
             <tr ><td class="td1">粉丝数</td><td  class="td2"><?php echo ($vo["platformFans"]); ?> </td></tr>
             <tr ><td class="td1">业务方向</td><td  class="td2"><?php echo ($vo["businessDire"]); ?> </td></tr><?php endforeach; endif; else: echo "" ;endif; ?>

           <tr ><td class="td1">申请时间</td><td  class="td2"><?php echo ($info["houseManager"]["createTime"]); ?> </td></tr>
           <tr ><td class="td1">推荐时间</td><td  class="td2"><?php echo ($info["houseManager"]["recommendedTime"]); ?> </td></tr>
           <tr >
	           <td class="td1">审核状态</td>
	           <td  class="td2">         
	           	<?php switch($info["houseManager"]["checkStatus"]): case "0": ?>待审核<?php break;?>
				    <?php case "1": ?>审核成功<?php break;?>
				    <?php case "2": ?>审核失败<?php break; endswitch;?>
	           </td>
           </tr>      
           <tr ><td class="td1">操作员</td><td  class="td2"> <?php echo ($info["houseManager"]["operator"]); ?></td></tr>
      </table>
</div>

<script type="text/javascript">

</script>

</body>
</html>