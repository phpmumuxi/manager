<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($configcache['Title']); ?></title>
<link rel="stylesheet" type="text/css" href="__CSS__/main.css"  />
<link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
<script type="text/javascript" src="__JS__/jquery.js"></script>
<script type="text/javascript" src="__JS__/winpop.js"></script>
<script type="text/javascript" src="__JS__/Public.js"></script>

<script>
window.onload=function() {
	//退出登录
	function quit() {
		$('body #quit').click(function(event) {
			event.preventDefault();
			if (confirm('确定要退出吗？')) {
				window.top.location.href='__APP__/Index/quit';
			}
		});
	}
	quit();
	$('#home').click(function() {
		window.top.location.href='__APP__/Index/main';
	});
    $('#main #user_save').click(function() {
		popload('修改密码',580,230,'__APP__/User/uedit/');
		addDiv($('#iframe_pop'));
	});
	//一键清空缓存
	$('#main #cache').click(function() {
		if (confirm('确定要清空所有缓存吗？')) {
			wintq('正在清除缓存，请稍后...',4,60000,0,'');
			$.ajax({
				url:'__APP__/Common/clearcache/',
				dataType:'JSON',
				type:'POST',
				data:'clear=ok',
				success: function(data) {
					if (data.s=='ok') {
						wintq('更新缓存成功！',1,1000,1,'');
					}else {
						wintq(data.s,3,1000,1,'');
					}
				}
			});
		}
	});

}
//绑定iframe加载事件
$(document).ready(function(e){   
	 var iframe = document.getElementById("ifdd");   
	if (!/*@cc_on!@*/0) { //如果不是IE，IE的条件注释  
	    iframe.onload = function(){     
	    	$("#iframeFather").hide();
	    };  
	} else {  
	    iframe.onreadystatechange = function(){ // IE下的节点都有onreadystatechange这个事件  
	        if (iframe.readyState === "complete" || iframe.readyState == "loaded"){  
	        	$("#iframeFather").hide(); 
	        }
	    };  
	}  
  });        

$(function() {
	//点击链接跳转的时候弹出加载层
	$('#left').find('a').click(function () {
 		if($(this).attr('href')!='javascript:;'){
 			$("#iframeFather").show();
 		}
    });

	for (i=0; i<$('#left dl').size(); i++) {
		$dldd=$('#left dl').eq(i);
		if ($dldd.find('dd').size() < 1) {
			$dldd.remove();
		}
	}
	$('#ul li .a').click(function() {
		$('#ul li .a').removeClass('lia');
		$(this).addClass('lia');
		$('#ul li dl').stop().slideUp('fast');
		var $dl = $(this).parents('li').find('dl');
		$dl.stop().slideToggle('fast');
		$dl.find('a').click(function() {
			$('#ul li dl dd a').removeClass('dla');
			$(this).addClass('dla');
		});
	});
});
</script>

</head>
<body>
<!-- <div id="Remindh">0</div> -->
<div id="main">
	<div id="header">
    	<div id="logo"><?php echo ($configcache['Title']); ?></div>
        <div id="cache" title="清空缓存"></div>
        <div id="home" title="首页"></div>
        <div id="all" title="全局导航"></div>
        <div id="quit" title="退出登录"></div>
    </div>
    <div id="top">
    	<div class="top_left">
        	<div id="jnkc"></div><script>setInterval("jnkc.innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);</script>
        	<div class="top_left1">
            欢迎您：<font><?php echo ($session['Username']); ?></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            本次是第 <font><?php echo ($session['Logincount']); ?></font> 次登录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            登录IP：<?php echo ($session['Loginip']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            登录位置：<font><?php echo ($session['Loginarea']); ?></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span id="user_save"><img src="__IMAGE__/user4.png" border="0" height="16" />修改密码</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span id="cache"><img src="__IMAGE__/cache.png" border="0" height="16" />清空缓存</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span id="quit"><img src="__IMAGE__/exit.png" border="0" height="16" />退出登录</span>
            </div>
        </div>
        <div class="top_right">
        	<a href="javascript:;" onclick="f5();"><img src="__IMAGE__/arrow_refresh.png" border="0" title="刷新" /></a>
        </div>
    </div>
    <div id="left">
    	<ul id="ul">
        	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><li><a class="a" href="<?php if($li["ModuleUrl"] == ''): ?>javascript:;<?php else: ?>__APP__/<?php echo ($li["ModuleUrl"]); endif; ?>" target="c"><img src="<?php echo ($li["ModuleImg"]); ?>" height="26" /><?php echo ($li["ModuleName"]); ?></a>
            	<dl>
            	<?php if(is_array($volist)): foreach($volist as $key=>$vo): if($li['ID'] == $vo['Sid']): ?><dd><a href="<?php if($vo["ModuleUrl"] == ''): ?>javascript:;<?php else: ?>__APP__/<?php echo ($vo["ModuleUrl"]); endif; ?>" target="c"><img src="<?php echo ($vo["ModuleImg"]); ?>" /><?php echo ($vo["ModuleName"]); ?></a></dd><?php endif; endforeach; endif; ?>
                </dl>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="left" title="收缩"></div>
    <div id="right">
	    <div id="iframeFather" style="z-index: 949; position: absoult; filter: alpha(opacity=60);
	        width: 100%; height: 100%; top: 0px; left: 0px; display:none;text-align:center;opacity: 0.6;background-color: #000;" >
	        
	       <div><img style="vertical-align:middle;margin-top:400px;"  src="__PUBLIC__/image/loading.gif"></div>
	       <div>正在加载中请稍后...</div>
	    </div>
    	<iframe id="ifdd" name="c" height="100%" width="100%" border="0" frameborder="0" src="__APP__/Index/content/">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
    </div>
</div>
</body>
</html>