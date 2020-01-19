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
    <style>
      /*遮罩层*/
		#mask {
			background-color: #000;
			position: absolute;
			z-index: 9999;
			display: none;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			opacity: 0.6;
			filter: alpha(opacity=60);
		}
		
		.dialog {
			position: fixed;
			z-index: 99999;
			text-align: center;
			top: 40%;
			left: 41%;
			background: #fff;
			padding: 100px;
		}
		
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
    .typeSelected{
        color: #169BD5;
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
            <a href="<?php echo U('Client/contractmanager');?>"><li>已签署</li></a>
             <a href="<?php echo U('Client/contractList');?>"><li class="nav_active">合同管理</li></a>
        </ul>
        <p style="clear: both"></p>
    </div>
    <?php if($_SESSION['ThinkUser']['ID']!= 39): ?><div style='border:1px solid white;'>
            <div style="margin-top:30px;float: left;">
                平台类型:&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Client/contractList',array('type'=>''));?>" <?php if(($type) == ""): ?>class="typeSelected"<?php endif; ?> >全部</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Client/contractList',array('type'=>A));?>" <?php if(($type) == "A"): ?>class="typeSelected"<?php endif; ?> >逸管家官网</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Client/contractList',array('type'=>B));?>" <?php if(($type) == "B"): ?>class="typeSelected"<?php endif; ?>>临时网签平台</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Client/contractList',array('type'=>C));?>" <?php if(($type) == "C"): ?>class="typeSelected"<?php endif; ?>>注册会员协议</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Client/contractList',array('type'=>D));?>" <?php if(($type) == "D"): ?>class="typeSelected"<?php endif; ?>>网红签约收费协议</a>
            </div>
            <div style="margin-right:40px;margin-top:30px;float: right;">
                <a href="<?php echo U('Client/contractEdit');?>"><button  class="sign_button ">添加</button></a>
            </div>
            <p style="clear: both"></p>
        </div><?php endif; ?>
    <table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
        <tr>
            <th>合同标题</th>
            <th>合同状态</th>
            <th>平台类型</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>操作</th>
        </tr>
        <?php if(empty($list)): ?><tr><td style='text-align:center' colspan='6'>暂时没有数据</td></tr>
        	<?php else: ?>
		        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                <td style="text-align:center" class='tc'><?php echo ($vo["contractTitle"]); ?></td> 
		                <td class='tc' style="text-align:center">
		                    <?php switch($vo["isUse"]): case "0": ?>禁用<?php break;?>
		                    <?php case "1": ?>执行<?php break;?>
		                    <?php default: endswitch;?>
		                </td>
                        <td class='tc' style="text-align:center">
                            <?php switch($vo["remarks"]): case "A": ?>逸管家官网<?php break;?>
                            <?php case "B": ?>临时网签平台<?php break;?>
                            <?php case "C": ?>注册会员协议<?php break;?>
                            <?php case "D": ?>网红签约收费协议<?php break;?>
                            <?php default: endswitch;?>
                        </td>
                        <td style="text-align:center" class='tc'><?php echo ($vo["createTime"]); ?></td>
		                <td style="text-align:center" class='tc'><?php echo ($vo["updateTime"]); ?></td>
		                <td class='tc' style="text-align:center">
                            <?php if($_SESSION['ThinkUser']['ID']!= 39): ?><a target="_blank" href="<?php echo (C("YGJ_URL")); ?>newAgent/bargain.html?contractDownloadPath=<?php echo ($vo["contractDownloadPath"]); ?>&sid=<?php echo ($vo["sid"]); ?>&contractTitle=<?php echo ($vo["contractTitle"]); ?>" style="margin-right: 5px;color:blue">预览</a>
    		                    <a href="__APP__/Client/contractEdit?sid=<?php echo ($vo["sid"]); ?>" class="look" style="margin-right: 5px;color:blue">编辑</a>
    		                    <a  href="#" id="del" sid="<?php echo ($vo["sid"]); ?>" onClick="del(this)" style="margin-right: 10px;color:blue">删除</a><?php endif; ?>
		                </td>
		            </tr><?php endforeach; endif; else: echo "$empty" ;endif; endif; ?>
    </table>
    <div id="page">
        <?php echo ($page); ?>
    </div>
</div>

<div id="mask" style="height:100%"></div>
	<div class="dialog" style="display:none; ">
		<img  src="__PUBLIC__/image/loading.gif">正在上传……
	</div>
	
<script>
/**
 * 遮罩显示
 */
function maskshow() {
	$("#mask").css({
		display: "block",
		height: $(document).height()
	});
	$(".dialog").css("display", "block");
}
/**
 * 遮罩隐藏
 */
function maskhide() {
	$("#mask").css({
		display: "none",
		height: $(document).height()
	});
	$(".dialog").css("display", "none");
}

/**
 * 删除
 */
function del(a){
    if(confirm("确定要删除嘛？")){        
    	var sid = $(a).attr('sid');
    	$.ajax({
    		url:"<?php echo (C("URL")); ?>" + "/manager/contractManagement/deleContractManagement",
    		type:"POST",
    		dataType:"json",
    		data:{'sid':sid},
    		success:function(re){
    			f5();
    		},
    		error:function(re){
    			alert('请求异常');
    		}
    		
    	})
    }
}
</script>
</body>
</html>