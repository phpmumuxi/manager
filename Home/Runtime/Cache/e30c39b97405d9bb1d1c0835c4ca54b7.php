<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理|<?php echo ($configcache['Title']); ?></title>
<link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
<link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
<script type="text/javascript" src="__JS__/jquery2.js"></script>
<script type="text/javascript" src="__JS__/Public.js"></script>
<script type="text/javascript" src="__JS__/winpop.js"></script>
<script>
$(document).ready(function() {
    $('#content h2 .add').click(function() {
		popload('添加管理用户',600,380,'__APP__/User/useradd/');
		addDiv($('#iframe_pop'));
		popclose();
	});
	$('#content #table .tr .edit').click(function(event) {
		event.preventDefault();
		var id=$(this).attr('href');
		if (id=='' || isNaN(id)) {
			wintq('ID参数不正确',3,1000,1,'');
			return false;
		}else {
			popload('修改用户信息',740,420,'__APP__/User/useredit/id/'+id);
			addDiv($('#iframe_pop'));
			popclose();
		}
	});
	$('#content #table .tr .del').click(function(event) {
		event.preventDefault();
		if (!confirm('确定要删除该数据吗？')) {
			return false;
		}
		var id=$(this).attr('href');
		if (id=='' || isNaN(id)) {
			wintq('ID参数不正确',3,1000,1,'');
			return false;
		}else {
			wintq('正在删除，请稍后...',4,20000,0,'');
			$.ajax({
				url:'__APP__/User/userdel/',
				dataType:'json',
				type:'POST',
				data:'post=ok&id='+id,
				success: function(data) {
					if (data.s=='ok') {
						wintq('删除成功',1,1500,0,'?');
					}else {
						wintq(data.s,3,1500,1,'');
					}
				}
			});
		}
	});
	$('#dely').click(function(event) {
		event.preventDefault();
		if (!confirm('确定要删除选择项吗？')) {
			return false;
		}
		var delid='';
		for (i=0; i<$('#table .delid').size(); i++) {
			if (!$('#table .delid').eq(i).attr('checked')==false) {
				delid=delid+$('#table .delid').eq(i).val()+',';
			}
		}
		if (delid=='') {
			wintq('请选中后再操作',2,1500,1,'');
		}else {
			wintq('正在删除，请稍后...',4,20000,0,'');
			$.ajax({
				url:'__APP__/User/in_user_del/',
				dataType:'JSON',
				type:'POST',
				data:'delid='+delid,
				success: function(data) {
					if (data.s=='ok') {
						wintq('删除成功',1,1500,0,'?');
					}else {
						wintq(data.s,3,1500,1,'');
					}
				}
			});
		}
	});
});
</script>
</head>
<body>
<div id="content">
	<h1>首页 > 用户管理 > 用户管理</h1>
    <h2>
    	<div class="h2_left">
        	<a href="__ACTION__" class="whole">全部</a>
        	<a href="javascript:;" class="f5" onclick="f5();">刷新</a>
            <a href="javascript:;" class="add">新增</a>
            <a href="javascript:history.back();" class="Retreat">后退</a>
            <a href="javascript:history.go(1);" class="Advance">前进</a>
        </div>
        <div class="search">
        	<form action="__ACTION__" method="get">
        	<input type="text" name="keyword" class="text" value="<?php echo ($keyword); ?>" />
            <input type="submit" class="so" value="搜 索" />
            </form>
        </div>
    </h2>
    <table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
    	<tr>
        	<th><input type="checkbox" class="indel" value="del" /></th>
        	<th>编号</th>
            <th>用户角色</th>
            <th>用户名</th>
            <th>Email</th>
            <th>状态</th>
            <th>登录次数</th>
            <th>登录地点</th>
            <th>登录IP</th>
            <th>日期</th>
            <th>操作</th>
        </tr>
        <?php if($co == 0): ?><tr class="tr"><td class="tc" colspan="11">暂无数据，等待添加～！</td></tr><?php endif; ?>
        <!--顶级数据-->
        <?php if(is_array($volist)): $i = 0; $__LIST__ = $volist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="tr <?php if(($mod) == "1"): ?>tr2<?php endif; ?>">
        	<td class="tc"><input type="checkbox" class="delid" value="<?php echo ($vo["ID"]); ?>" /></td>
            <td class="tc"><?php echo ($vo["ID"]); ?></td>
            <td class="tc"><?php echo ($vo["Rolename"]); ?></td>
            <td><?php echo ($vo["Username"]); ?></td>
            <td><?php echo ($vo["Email"]); ?></td>
            <td class="tc">
            <?php if($vo["Status"] == 0): ?><img src="__IMAGE__/yes.png" border="0" title="正常" />
            <?php else: ?>
            <img src="__IMAGE__/no.png" border="0" title="锁定" /><?php endif; ?>
            </td>
            <td class="tc"><?php echo ($vo["Logincount"]); ?></td>
            <td class="tc"><?php echo ($vo["Loginarea"]); ?></td>
            <td class="tc"><?php echo ($vo["Loginip"]); ?></td>
            <td class="tc"><?php echo ($vo["Dtime"]); ?></td>
            <td class="tc fixed_w"><a href="<?php echo ($vo["ID"]); ?>" class="edit"><img src="__IMAGE__/edit.png" border="0" title="修改" /></a><a href="<?php echo ($vo["ID"]); ?>" class="del"><img src="__IMAGE__/delete.png" border="0" title="删除" /></a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <div id="page"><a href="javascript:;" class="selbox">全选</a><a href="javascript:;" class="anti">反选</a><a href="javascript:;" class="unselbox">全不选</a>&nbsp;&nbsp;对选中项进行&nbsp;&nbsp;<a href="javascript:;" id="dely">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($page); ?></div>
</div>
</body>
</html>