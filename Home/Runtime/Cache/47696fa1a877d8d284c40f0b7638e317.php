<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>公益管家|<?php echo ($configcache['Title']); ?></title>
  <link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
  <link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
  <script type="text/javascript" src="__JS__/jquery.js"></script>
  <script type="text/javascript" src="__JS__/Public.js"></script>
  <script type="text/javascript" src="__JS__/winpop.js"></script>
  <script type="text/javascript" src="__JS__/My97DatePicker/WdatePicker.js"></script>
  <script src="__PUBLIC__/js/layer/layer.js" type="text/javascript"></script>
  <style type="text/css">
  .select{padding:5px 10px;border:#ddd 1px solid;border-radius:4px;width:60%;margin:5% auto;font-size:12px}
  .select li{list-style:none;padding:10px 0 5px 80px}
  .select .formli{list-style:none;padding:10px 0 5px 20px}
  .select .select-list{border-bottom:#eee 1px dashed}
  .select dl{zoom:1;position:relative;line-height:24px;}
  .select dl:after{content:" ";display:block;clear:both;height:0;overflow:hidden}
  .select dt{width:100px;margin-bottom:5px;position:absolute;top:0;left:-100px;text-align:right;color:#666;height:24px;line-height:24px}
  .select dd{float:left;display:inline;margin:0 0 5px 5px;}
  .select a{display:inline-block;white-space:nowrap;height:24px;padding:0 10px;text-decoration:none;border-radius:2px;}
  .select .selected a{color:#169BD5;}
  .select-result dt{font-weight:bold}
  .select-no{color:#999}
  .select .select-result a{padding-right:20px;background:#f60 url("__PUBLIC__/image/close.gif") right 9px no-repeat}
  .select .select-result a:hover{background-position:right -15px}
  .tc{text-align:center; text-indent:0;font-family:"微软雅黑";font-size: 14px;color:#111;}
  .butclass{
  	background:#029be1;
  	color:white;
  	height:40px;
  	width:120px;
  	margin:40px;
  	padding:10px;
  	border-radius:5px;
  }
  .reasoncontent{
  margin-left:20%;
  margin-top:30px;
  border:1px solid #797979;
  width:60%;
  height:100px
  }
  
  td{
  	text-align:center;
  }
  th{
  	text-align:center;
  }
  .btnclick{
  height:20px;
  	 background: #080;
    cursor: pointer;
    color: #fff;
  }
	a:visited {color: black}  /* 已访问的链接 */
	a:active {color: black}   /* 被选择的链接 */
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
  <script>
  function myBrowser(){
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
    var isOpera = userAgent.indexOf("Opera") > -1;
    if (isOpera) {
      return "Opera"
    }; //判断是否Opera浏览器
    if (userAgent.indexOf("Firefox") > -1) {
      return "FF";
      /*firefox----这段js重新封装了event对象，经验证可以在火狐下支持！----*/
      function __firefox(){
        HTMLElement.prototype.__defineGetter__("runtimeStyle", __element_style);
        window.constructor.prototype.__defineGetter__("event", __window_event);
        Event.prototype.__defineGetter__("srcElement", __event_srcElement);
      }
      function __element_style(){
        return this.style;
      }
      function __window_event(){
        return __window_event_constructor();
      }
      function __event_srcElement(){
        return this.target;
      }
      function __window_event_constructor(){
        if(document.all){
          return window.event;
        }
        var _caller = __window_event_constructor.caller;
        while(_caller!=null){
          var _argument = _caller.arguments[0];
          if(_argument){
            var _temp = _argument.constructor;
            if(_temp.toString().indexOf("Event")!=-1){
              return _argument;
            }
          }
          _caller = _caller.caller;
        }
        return null;
      }
      if(window.addEventListener){
        __firefox();
      }
    } //判断是否Firefox浏览器
    if (userAgent.indexOf("Chrome") > -1){
      return "Chrome";
    }
    if (userAgent.indexOf("Safari") > -1) {
      return "Safari";
    } //判断是否Safari浏览器
    if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
      return "IE";
    }; //判断是否IE浏览器
  }
  </script>
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
  </h2>
  <div class="nav_div">
      <ul>
          <a href="<?php echo U('Client/housemanager');?>"><li>已申请管家</li></a>
          <a href="<?php echo U('Client/publicmanager');?>"><li class="nav_active">公益管家</li></a>
          <a href="<?php echo U('Client/noStatistic');?>"><li>待申请管家</li></a>
      </ul>
      <p style="clear: both"></p>
  </div>  
  <div class="fen" style="margin-top:15px;">
      <ul class="select" style="height:110px;margin-top: 20px;margin-bottom: 20px;">
        <li class="select-list List_more">
          <dl id="select" >
          	<dt>审核状态：</dt>
          	  <dd <?php if($auditStatus == ''): ?>class="selected"<?php endif; ?>>
                  <a href="<?php echo U('Client/publicmanager',['keyWord' => $keyWord]);?>">全部</a>
              </dd>
              <dd <?php if($auditStatus == '1'): ?>class="selected"<?php endif; ?>>
                  <a href="<?php echo U('Client/publicmanager',['auditStatus' => '1','keyWord' => $keyWord]);?>">待审核</a>
              </dd>
            	<dd <?php if($auditStatus == '2'): ?>class="selected"<?php endif; ?>>
              	  <a href="<?php echo U('Client/publicmanager',['auditStatus' =>'2','keyWord' => $keyWord]);?>">审核成功</a>
              </dd>
              <dd <?php if($auditStatus == '3'): ?>class="selected"<?php endif; ?>>
              	  <a href="<?php echo U('Client/publicmanager',['auditStatus' =>'3','keyWord' => $keyWord]);?>">审核失败</a>
              </dd>
          </dl>   
        </li>
        <li class="formli">
          <form method="get">
  	        <div>
  	            <input type="text" name="keyWord" class="tel orderno"  placeholder="小贴士：请输入姓名或者手机号" value="<?php echo ($keyWord); ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="hidden"  name="auditStatus" value="<?php echo ($auditStatus); ?>" /> 
  	            <input type="submit" class="sure" value="查询" /> 
  	        </div>
      	  </form>
        </li>     
  	  </ul>
  </div>
  <table id="table" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
     <tr>
       <th width="5%">来源</th>
       <th width="5%">管家级别</th>
       <th width="5%">昵称</th>
       <th width="5%">姓名</th>
       <th width="10%">身份证号</th>
       <th width="8%">手机号</th> 
       <th width="10%">申请时间</th>  
       <th width="10%">服务区域</th>
       <th width="10%">服务截止时间</th>
       <th width="5%">状态</th>       
     </tr>
     <?php if(empty($list)): ?><tr><td style='text-align:center' colspan='10'>暂时没有数据</td></tr>
     <?php else: ?>
       <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
          <td style="text-align:center" class='tc'>PC</td>
          <td style="text-align:center" class='tc'>公益管家</td>
          <td style="text-align:center" class='tc'><?php echo ($vo["userNickName"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["userRealName"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["userIdCode"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["userTelephone"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["applyTime"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["serviceArea"]); ?></td>
          <td style="text-align:center" class='tc'><?php echo ($vo["deadTime"]); ?></td>
          <td style="text-align:center" class='tc'>
    		      <?php switch($vo["auditEnrollStatus"]): case "1": ?>待审核<?php break;?>
    			      <?php case "2": ?>审核成功<?php break;?>
                <?php case "3": ?>审核失败<?php break; endswitch;?>
          </td>    
        </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
  </table>
<div id="page">
  <?php echo ($page); ?>每页显示<select id="selectpagesize" style="width:50px;display:inline-block;">
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