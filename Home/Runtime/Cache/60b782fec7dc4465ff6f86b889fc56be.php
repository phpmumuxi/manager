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
    <input type="hidden" id="url" value="<?php echo ($url); ?>">     
  </h2>
<div class="nav_div">
    <ul>
        <a href="<?php echo U('Client/housemanager');?>"><li class="nav_active">已申请管家</li></a>
        <a href="<?php echo U('Client/publicmanager');?>"><li>公益管家</li></a>
        <a href="<?php echo U('Client/noStatistic');?>"><li>待申请管家</li></a>
    </ul>
    <p style="clear: both"></p>
</div>  
<div class="fen" style="margin-top:15px;">
    <ul class="select" style="height:280px;margin-top: 20px;margin-bottom: 20px;">
     	 <li class="select-list List_more">
         <dl id="select" >
        	<dt>来源：</dt>
        	<dd <?php if($platform == '-1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>-1,'managerType' => $managerType,'checkStatus' => $checkStatus,'recommendStatus'=>$recommendStatus,'recommendStatus'=>$recommendStatus]);?>">全部</a>
                </dd>
           
              	<dd <?php if($platform == '0'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>0,'managerType' => $managerType,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">APP</a>
                </dd>
                 <dd <?php if($platform == '1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>1,'managerType' => $managerType,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">H5</a>
                
                </dd>
                
                 <dd <?php if($platform == '2'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>2,'managerType' => $managerType,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">PC</a>
                
                </dd>
                
         
               </dl>
        </li>
        
    	 <li class="select-list List_more">
         <dl id="select" >
        	<dt>管家级别：</dt>
        	<dd <?php if($managerType == '-1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => -1,'checkStatus' => $checkStatus,'recommendStatus'=>$recommendStatus,'recommendStatus'=>$recommendStatus]);?>">全部</a>
                </dd>
           
           <!--    	<dd <?php if($managerType == '1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 1,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">A类</a>
                </dd>
                 <dd <?php if($managerType == '2'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 2,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">B类</a>
                
                </dd>
                
                 <dd <?php if($managerType == '3'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 3,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">C类</a>
                
                </dd>
                <dd <?php if($managerType == '4'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 4,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">D类</a>
                </dd>
                 <dd <?php if($managerType == '5'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 5,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">E类</a>
                
                </dd>
                
                 <dd <?php if($managerType == '6'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 6,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">F类</a>
                
                </dd> -->
           		<dd <?php if($managerType == '7'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 7,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">联合体成员</a>
              </dd>
              <dd <?php if($managerType == '8'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 8,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">企业管家</a>
              </dd>
              <dd <?php if($managerType == '9'): ?>class="selected"<?php endif; ?>>
                  <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 9,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">签约管家</a>
              </dd>
              <dd <?php if($managerType == '10'): ?>class="selected"<?php endif; ?>>
                  <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'managerType' => 10,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'recommendStatus'=>$recommendStatus]);?>">网红管家</a>
              </dd>
          </dl>
        </li>
        <li class="select-list List_more">
         <dl id="select" >
        	<dt>管家类别：</dt>
        	<dd <?php if($recommendType == '-1'): ?>class="selected"<?php endif; ?>>
                	<!-- <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'recommendType' => -1,'checkStatus' => $checkStatus,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">全部</a> -->
                  <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'recommendType' => -1,'checkStatus' => $checkStatus,'managerType'=>$managerType]);?>">全部</a>
                </dd>
           
              	<dd <?php if($recommendType == '1'): ?>class="selected"<?php endif; ?>>
                	<!-- <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'recommendType' => 1,'checkStatus' => $checkStatus,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">行业管家</a> -->
                  <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'recommendType' => 1,'checkStatus' => $checkStatus,'managerType'=>$managerType]);?>">行业管家</a>
                </dd>
                 <!-- <dd <?php if($recommendType == '1'): ?>class="selected"<?php endif; ?>> -->
                 <dd <?php if($recommendStatus == '1'): ?>class="selected"<?php endif; ?>>
                	<!-- <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'recommendStatus' => 1,'checkStatus' => $checkStatus,'recommendType'=>$recommendType,'managerType'=>$managerType]);?>">推荐管家</a> -->
                  <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'checkStatus' => $checkStatus,'recommendStatus'=>1,'managerType'=>$managerType]);?>">推荐管家</a>
                </dd>
               </dl>
        </li>
        
    
         <li class="select-list List_more">
         <dl id="select" >
        	<dt>审核状态：</dt>
        	 <dd <?php if($checkStatus == '-1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'checkStatus' => -1,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">全部</a>
                </dd>
              <dd <?php if($checkStatus == '0'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'checkStatus' => 0,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">待审核</a>
                </dd>
              	<dd <?php if($checkStatus == '1'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'checkStatus' => 1,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">审核通过</a>
                </dd>
                <dd <?php if($checkStatus == '2'): ?>class="selected"<?php endif; ?>>
                	<a href="<?php echo U('Client/housemanager',['platform' =>$platform,'checkStatus' => 2,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">审核失败</a>
                </dd>
               </dl>
               
               
        </li>
         <li class="select-list List_more">
         <dl id="select" >
        	<dt>支付状态：</dt>
        	 <dd <?php if($payStatus == '-1'): ?>class="selected"<?php endif; ?>>
              <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'payStatus' => -1,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">全部</a>
            </dd>
            <dd <?php if($payStatus == '0'): ?>class="selected"<?php endif; ?>>
              <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'payStatus' => 0,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">待支付</a>
            </dd>
            <dd <?php if($payStatus == '1'): ?>class="selected"<?php endif; ?>>
              <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'payStatus' => 1,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">支付成功</a>
            </dd>
            <dd <?php if($payStatus == '2'): ?>class="selected"<?php endif; ?>>
              <a href="<?php echo U('Client/housemanager',['platform' =>$platform,'payStatus' => 2,'recommendType' => $recommendType,'recommendStatus'=>$recommendStatus,'managerType'=>$managerType]);?>">无需支付</a>
            </dd>
          </dl>
        </li>
        <li class="formli">
  
	         <form method="post">
		        <div>
		            <input type="text" name="keyWord" class="tel orderno"  placeholder="小贴士：请输入姓名或者手机号" value="<?php echo ($keyWord); ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
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
   <th width="5%">支付金额</th>
     <th width="6%">推荐位</th>
     <th width="5%">昵称</th>
     <th width="5%">姓名</th>
     <th width="10%">身份证号</th>
     <th width="5%">申请行业</th>
     <th width="8%">手机号</th>  
     <th width="7%">推荐人手机号</th>  
     <th width="9%">申请时间</th>  
     <th width="9%">推荐时间</th>
     <th width="4%">支付状态</th>
     <th width="4%">状态</th>
     <th width="22%">操作</th>     
   </tr>
   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
    <td style="text-align:center" class='tc'>
     <?php switch($vo["platform"]): case "0": ?>APP<?php break;?>
      	<?php case "1": ?>H5<?php break;?>
      	<?php case "2": ?>PC<?php break; endswitch;?>
     </td>
     
     <td style="text-align:center" class='tc'>
     <?php switch($vo["manager_type"]): case "1": ?>A类<?php break;?>
      	<?php case "2": ?>B类<?php break;?>
      	<?php case "3": ?>C类<?php break;?>
      	<?php case "4": ?>D类<?php break;?>
      	<?php case "5": ?>E类<?php break;?>
      	<?php case "6": ?>F类<?php break;?>
      	<?php case "7": ?>联合体成员<?php break;?>
        <?php case "8": ?>企业管家<?php break;?>
        <?php case "9": ?>签约管家<?php break;?>
	      <?php case "10": ?>网红管家<?php break; endswitch;?>
     </td>
      <td style="text-align:center" class='tc'><?php echo ($vo["pay_num"]); ?></td>
      <td style="text-align:center" class='tc'>
   
      <?php switch($vo["recommended_type"]): case "1": ?>行业管家<?php break; endswitch;?>
       <?php switch($vo["recommended_status"]): case "1": ?>推荐管家<?php break; endswitch;?>
      </td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_nick_name"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_real_name"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_id_code"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_type"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["user_telephone"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["recommended_user_tel"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["create_time"]); ?></td>
      <td style="text-align:center" class='tc'><?php echo ($vo["recommended_time"]); ?></td>
      <td style="text-align:center" class='tc'>
     <?php switch($vo["pay_status"]): case "0": ?>待支付<?php break;?>
        <?php case "1": ?>支付成功<?php break;?>
        <?php default: ?>
        无需支付<?php endswitch;?>
     </td>
      <td style="text-align:center" class='tc'>
	     <?php if(($vo['recommended_type'] == '1') or ($vo['recommended_status'] == '1')): ?>已推荐
		      <?php else: ?>
		      <?php switch($vo["check_status"]): case "0": ?>待审核<?php break;?>
			      <?php case "1": ?>审核成功<?php break;?>
            <?php case "2": ?>审核失败<?php break;?>
			      <?php case "3": ?>待审核<?php break; endswitch; endif; ?>
      </td>

      <td style="text-align:center" class='tc'>
        <?php switch($vo["manager_type"]): case "10": ?><a href="<?php echo U('/Client/housemanagerinfo',['sid' => $vo['user_id'],'manager_type' => 1]);?>" class="look" style="margin-right: 5px;">详情</a><?php break;?>
           <?php default: ?>
              <a href="<?php echo U('/Client/housemanagerinfo',['sid' => $vo['sid'],'manager_type' => 0]);?>" class="look" style="margin-right: 5px;">详情</a><?php endswitch;?>
        <?php switch($vo["check_status"]): case "0": ?><a  href="javascript:;"  class="checkpass" style="margin-right: 5px;" data-recommendedusertel ="<?php echo ($vo["recommended_user_tel"]); ?>" data-userid="<?php echo ($vo["userId"]); ?>" data-sid="<?php echo ($vo["sid"]); ?>" data-type="<?php echo ($vo["manager_type"]); ?>">审核通过</a>
	             <a  href="javascript:;"  class="checknopass" style="margin-right: 5px;" data-recommendedusertel ="<?php echo ($vo["recommended_user_tel"]); ?>" data-userid="<?php echo ($vo["userId"]); ?>" data-sid="<?php echo ($vo["sid"]); ?>" data-type="<?php echo ($vo["manager_type"]); ?>">审核不通过</a><?php break;?>
	         <?php case "1": ?><a  href="<?php echo U('/Client/housemanagerreset',['sid' => $vo['sid']]);?>" style="margin-right: 5px;"  data-sid="<?php echo ($vo["sid"]); ?>">推荐设置</a><?php break;?>
	         <?php case "2": break;?>
	  		   <?php case "3": ?><a  href="javascript:;"  class="checkpass" style="margin-right: 5px;" data-recommendedusertel ="<?php echo ($vo["recommended_user_tel"]); ?>" data-userid="<?php echo ($vo["userId"]); ?>" data-sid="<?php echo ($vo["sid"]); ?>" data-type="<?php echo ($vo["manager_type"]); ?>">审核通过</a>
               <a  href="javascript:;"  class="checknopass" style="margin-right: 5px;" data-recommendedusertel ="<?php echo ($vo["recommended_user_tel"]); ?>" data-userid="<?php echo ($vo["userId"]); ?>" data-sid="<?php echo ($vo["sid"]); ?>" data-type="<?php echo ($vo["manager_type"]); ?>">审核不通过</a><?php break; endswitch;?>
      </td>
      
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<div style="display:none" class="watch"></div>

<!-- 审核记录 -->
<div style="display:none" class="recorddiv">
<table id="tablerecord" border="1" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0" style="width:80%;margin-left:10%;">
	<tr>
		<th width="25%">审核时间</th>
		<th width="20%">操作人</th>
		<th width="15%">审核结果</th>
		<th width="40%">备注</th>
	</tr>	

</table>

</div>

<!-- 不通过 -->
<div style="display:none" class="notpassdiv">

	<span style="float:left;width:100%;text-align: center;">审核不通过，请发送消息给用户</span>
	<span><textarea class="reasoncontent" placeholder=""></textarea></span>
	<span style="float:left;width:100%;text-align: center;">
	<button type="button" class=" butclass notpasssave">确认发送</button>
	</span>
</div>

<!-- 审核 -->
<div style="display:none" class="checkdiv">
	<!-- <span style="float:left;width:100%;text-align: center;">审核通过并设置为管家经理。</span> -->
	<span style="float:left;width:100%;text-align: center;">
	<button type="button" class=" butclass pass">确认</button>
	<button type="button" class=" butclass notpass">取消</button>
	</span>
</div>
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
</div>
<script>


	
//推荐
$('.recommendstatus').click(function(){
	  var recommendedStatus = $(this).data('recommendstatus');
	  var sid = $(this).data('sid');
	  var recommendedUserTel = $(this).data('recommendedusertel');//推荐人手机号
	  $.ajax({
	   type: "POST",
	  url: "<?php echo ($url); ?>/manager/seUserHouseManager/updateUserHouseManager",
	  //url: "http://192.168.1.28:8080/manager/seUserHouseManager/updateUserHouseManager",
	   data: {recommendedStatus :recommendedStatus,sid:sid,recommendedType:recommendedType}, 
	   success: function(msg){
		   if(msg.success){
			   alert(msg.message);
			   window.location.href = window.location.href;
		   }
		   
	  },
	  error:function(){
	    alert('网络异常!');
	  }
	});
	})
	
	
	
$("#selectpagesize").on('change',function(){
    var pageSize=$(this).val();
    window.location.href = "__APP__/Client/housemanager?pageSize="+pageSize;
});
//审核通过弹出
$(".checkpass").click(function(){
	var userId = $(this).data('userid');
  var sid = $(this).data('sid');
	var type = $(this).data('type');
	var recommendedUserTel = $(this).data('recommendedusertel');//推荐人手机号
	//debugger
	layer.open({
		type: 1,
		title: "<div style='text-align:center;'><strong>审核通过</strong></div>",
		content: $('.checkdiv').html(),
		skin: 'layer-ext-moon',
		area: ['26%','20%'],
		resize: false,
		shadeClose: true,
		success:function(){
			//通过
			$(".pass").click(function(){	
				$.post("<?php echo U('Client/housemanagerpass');?>",{'recommendedUserTel':recommendedUserTel,'sid':sid,'userId':userId,'checkStatus':1,'type':type},function(result){	
					if(result.success){
						alert(result.message);
						window.location.href = result.refererurl;
					}else{
						alert(result.message);
						window.location.href = result.refererurl;
					}
				})
			})	
		//取消设置
			$(".notpass").click(function(){
				layer.closeAll();
			})
			//end
		
		}
	});
})
//审核不通过
$(".checknopass").click(function(){
	var userId = $(this).data('userid');
	var sid = $(this).data('sid');
  var type = $(this).data('type');
	var recommendedUserTel = $(this).data('recommendTel');//推荐人手机号
	layer.open({
		type: 1,
		title: "<div style='text-align:center;'><strong>审核不通过</strong></div>",
		content: $('.notpassdiv').html(),
		skin: 'layer-ext-moon',
		area: ['40%','40%'],
		resize: false,
		shadeClose: true,
		success:function(){
				$(".notpasssave").click(function(){
					var remark =  $(this).parent().prev().find(".reasoncontent").val();
					$.post("<?php echo U('Client/housemanagerpass');?>",{'recommendedUserTel':recommendedUserTel,'sid':sid,'userId':userId,'checkStatus':2,'remark':remark,'type':type},function(result){
						if(result.success){
							alert(result.message);
							window.location.href = result.refererurl;
						}else{
							alert(result.message);
						}
					})
				});
		}
	});
})
//推荐





//审核记录
$(".record").click(function(){
	var sid = $(this).data('sid');
	var url = $("#url").val();
	
	$.post(url+'/manager/houseManagerCheck/queryHouseManagerCheckLogList',{"houseManagerId":sid},function(result){
		if(result.success){
			if(result.data.list.length == 0){
				return alert("该用户无审核记录，请先审核")
			}
			$("#tablerecord").html("<tr><th width='25%'>审核时间</th><th width='20%'>操作人</th><th width='15%'>审核结果</th><th width='40%'>备注</th></tr>")
			for(var i = 0 ; i < result.data.list.length ; i++){
				if( result.data.list[i]['checkStatus'] == 1){
					result.data.list[i]['checkStatus'] = "通过";
				}else{
					result.data.list[i]['checkStatus'] = "未通过";
					
				}
				
				$("#tablerecord").append("<tr><td>" + result.data.list[i]['createTime']+ "</td><td>" + result.data.list[i]['checkUserName']+ "</td><td>" + result.data.list[i]['checkStatus']+ "</td><td>" + result.data.list[i]['remark']+ "</td></tr>");
			}
			
			layer.open({
				type: 1,
				title: "<div style='text-align:center'><strong>审核记录</strong></div>",
				content: $('.recorddiv').html(),
				skin: 'layer-ext-moon',
				area: ['60%','70%'],
				resize: false,
				shadeClose: true,
				success:function(){}
				});
			
		}else{
				alert("请求失败");
			}
		
	});
})
	



</script>
</body>
</html>