<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($configcache['Title']); ?></title>
<link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
<link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
<script type="text/javascript" src="__JS__/jquery.js"></script>
<script type="text/javascript" src="__JS__/winpop.js"></script>
</head>
<body>
<div id="content">
	<div id="con1" style="width:95%;height:300px;">
    	<h6>系统信息</h6>
        <ul>
        	<li><span>ThinkPHP框架版本：</span><?php echo ($info['THINK_VERSION']); ?></li>
            <li><span>服务器操作系统：</span><?php echo ($info['PHP_OS']); ?></li>
            <li><span>运行环境：</span><?php echo ($info['SERVER_SOFTWARE']); ?></li>
            <li><span>Mysql版本：</span><?php echo ($info['mysql']); ?></li>
            <li><span>在线人数：</span><font><?php echo ($usercount); ?> 人</font></li>
            <li><span>当前版本：</span><font>v4.8.1</font></li>
                    
        </ul>
    </div>
    <div id="con3">
    	<h6><span>通知公告</span><a href="__APP__/News/news">更多>></a></h6>
        <ul>
        	<?php if(is_array($newslist)): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span><?php echo ($vo["Dtime"]); ?></span><a href="__APP__/News/article?id=<?php echo ($vo["ID"]); ?>"><?php echo ($vo["Title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
</body>
</html>