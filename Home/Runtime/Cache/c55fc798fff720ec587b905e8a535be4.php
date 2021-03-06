<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>app管理|<?php echo ($configcache['Title']); ?></title>
    <link rel="stylesheet" type="text/css" href="__CSS__/content.css"  />
    <link rel="stylesheet" type="text/css" href="__CSS__/public.css"  />
    <script type="text/javascript" src="__JS__/jquery.js"></script>
    <script type="text/javascript" src="__JS__/Public.js"></script>
    <script type="text/javascript" src="__JS__/winpop.js"></script>
    <script type="text/javascript" src="__JS__/check.js"></script>
    <script type="text/javascript" src="__JS__/jquery-form.js"></script>
    <link rel="stylesheet" href="__JS__/Validform/images/style.css" type="text/css" media="all" /> 
    <script type="text/javascript" src="__JS__/Validform/images/jquery-1.9.1.min.js"></script> 
    <script type="text/javascript" src="__JS__/Validform/images/Validform_v5.3.2.js"></script> 
    <script type="text/javascript" src="__JS__/Validform/plugin/passwordStrength/passwordStrength-min.js"></script> 
    <script src="__JS__/jquery-form.js" type="text/javascript"></script>
    <script type="text/javascript" src="__JS__/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="__JS__/layer/skin/layer.css" />
    <style type="text/css" >
        .dd_left{margin-top: 5px;}
        .dtext{text-align: center;}
        #preview, .img, img{width:150px;height:150px;}
        #preview{border:1px solid #000;}
        #view{} .imgbox{float:left;position:relative;width:150px;height: 150px;margin-left: 10px;margin-top: 10px;
          padding:5px;border:solid 1px #ccc;border-radius: 5px;;}
        #view{} .imgbox .close{border: solid 1px #ccc;position: absolute;top: -15px;
              width: 20px;height: 20px; display: inherit;cursor: pointer;text-align: center;background: red;color: #fff;right: -10px;z-index: 20;border-radius: 100%;}
        #view{} .imgbox input{width:100%;height: 100%;z-index: 10;}
        .tablecon{width: 800px; margin-left:80px;}
        .registerform{width:700px;height:465px;margin:20px;border:1px solid #bcd4e5;}
    </style> 
    <script type="text/javascript">
          $(function(){
            $(".registerform").Validform({
                tiptype:2,
                /*表单验证*/
                tiptype:function(msg,o,cssctl){
                    if(!o.obj.is("form")){
                        var objtip=o.obj.parent().find(".Validform_checktip");
                        cssctl(objtip,o.type);
                        objtip.text(msg);

                        var infoObj=o.obj.parent().find(".info");
                        if(o.type==2){
                            infoObj.fadeOut(200);
                        }else{
                            if(infoObj.is(":visible")){return;}
                            layer.closeAll('loading');
                            infoObj.css({
                                left:0,
                                top:-30
                            }).show().animate({
                                top:-27
                            },200);
                        }  
                    }   
                },  
            });
        })
</script> 
</head>
<body>
    <div id="content">
        <h1>首页 > 资料审核管理 > 管家经理报名</h1>
        <h2>
            <div class="h2_left">
                <a href="__ACTION__" class="whole">全部</a>
                <a href="javascript:;" class="f5" onclick="f5();">刷新</a>
                <a href="javascript:history.back();" class="Retreat">后退</a>
                <a href="javascript:history.go(1);" class="Advance">前进</a>
            </div>
        </h2>
        <form action="" method="post" class="registerform" id="form">
            <dl id="cdl">
                <dd>
                    <span class="dd_left">申请行业:</span>
                    <span class="dd_right">
                        <input type="text" class="qtext" name="title" value="<?php echo ($info["user_type"]); ?>" />
                       
                    </span>
                </dd>
                 <dd>
                    <span class="dd_left">管家昵称:</span>
                    <span class="dd_right">
                        <input type="text" class="qtext" name="title" value="<?php echo ($info["user_nick_name"]); ?>"    />
                       
                    </span>
                </dd>

				 <dd>
                    <span class="dd_left">头像:</span>
                    <span class="dd_right">
                       <?php if(substr($info['user_head_pic'],0,4) == 'http' ): ?><img src="<?php echo ($info["user_head_pic"]); ?>" height="50px" width="50px"/>
                       <?php else: ?>
                       <img src="<?php echo ($qiniuUrl); ?>/<?php echo ($info["user_head_pic"]); ?>" height="50px" width="50px"/><?php endif; ?>
                       
                    </span>
                </dd>
                
                <dd>
                    <span class="dd_right" style="margin-top: 5px;" id="clientStatus">
                        <span class="dd_left" >推荐位:</span>
                  
                        <label><input property="site" class="recommengmanager" onclick="clickmanager(this)"  name="recommendedStatus" type="checkbox"  value="<?php echo ($info["recommended_status"]); ?>" <?php if($info['recommended_status'] == '1'): ?>checked<?php endif; ?> />推荐管家</label>
                        <label><input property="site" class="trademanager" onclick="clickmanager(this)" name="recommendedType" type="checkbox" value="<?php echo ($info["recommended_type"]); ?>" <?php if($info['recommended_type'] == '1'): ?>checked<?php endif; ?>/>行业管家</label>
                    </span>
                </dd>
                 
                <dd class="backgroundimgdiv"  style="display:none">
                    <div id="reader_box"></div>
                    <span class="dd_left">背景小图:</span>
                    <span class="dd_right">
                        <input type="file" name="file" onchange="load(this)" class='myfile' data-imgname="smallbackgroundimg"  ></input>
                        <div class="view" ><img class="imagespace" src="<?php echo ($qiniuUrl); ?>/<?php echo ($info["background_img"]); ?>"></div>
                        <div class="smallbackgroundimg"></div>
                    </span>
                </dd>

                   <dd class="backgroundimgdiv"  style="display:none">
                <div id="reader_box"></div>
                <span class="dd_left">背景大图:</span>
                <span class="dd_right">
                <input type="file" name="file"  onchange="load(this)" class='myfile' data-imgname="bigbackgroundimg" ></input>
                <div class="view" ><img class="imagespace" src="<?php echo ($qiniuUrl); ?>/<?php echo ($info["background_big_img"]); ?>"></div>
                <div class="bigbackgroundimg"></div>
               </span>
                </dd> 
                 <dd class="sortdiv" style="display:none">
                  <span class="dd_left">排序号:</span>
                  <span class="dd_right">
                      <input type="text" class="dtext" name="sort" id="sort" value="<?php echo ($info["sort"]); ?>"/>
                  </dd>
                  <input type="hidden" name="referer" value="<?php echo ($referer); ?>" id="jump">
                 <input type="hidden" class="checktype" name="type" value="">
                 <input type="hidden" class="checktype" name="sid" value="<?php echo ($sid); ?>">
                <dd style="margin-top: 10px;margin-bottom: 160px;"><input type="submit" class="so submit" value="提 交" /></dd>
                <input type="hidden" name="" value="<?php echo U('Client/housemanager');?>" id="jump">
                
            </dl>
        </form>
    </div>

<script type="text/javascript">
var imgkey = [];
function load(file) {
    var num=1;
    $(file).parent().find('.imgbox').remove();
    $(file).parent().find('.imagespace').remove();
    if (file.files) {
        for (var i = 0; i < file.files.length; i++) {
            var reader = new FileReader();
            reader.readAsDataURL(file.files[i]);
            reader.onload = function(evt) {
                var imgbox=$('<div></div>');
                imgbox.addClass('imgbox');
                $(file).parent().find('.view').append(imgbox);
                var imgs = $('<img />');
                imgs.attr('name','mum'+num);
                num++;
                imgs.appendTo(imgbox);
                imgs.attr('src', evt.target.result);
            }
        }
    }
}

//小背景图片上传

$(document).on("change", ".myfile", logosub);
function logosub() {
    //模拟form表单提交数据
// var filepath=$("input[name='file']").val();
    var imagename = $(this).data("imgname");
    var filepath=$(this).val();
    var extStart=filepath.lastIndexOf("."); 
    var ext=filepath.substring(extStart,filepath.length).toUpperCase(); 
    if(ext!=".jpeg"&&ext!=".jpg"&&ext!=".bmp"&&ext!=".png" &&ext!=".JPEG"&&ext!=".JPG"&&ext!=".BMP"&&ext!=".PNG"){ 
        alert("文件选择错误,图片类型必须是jpeg,jpg,bmp,png中的一种"); 
        return false; 
    }

    var index = layer.load(1, {shade: [0.1,'#fff']});
    var fileObj = this.files[0];
    
    var FileController = '<?php echo ($url); ?>/manager/banner/uploadBannerImage';
    var form = new FormData();
    form.append("bannerFile", fileObj);
    var xhr = new XMLHttpRequest();
    xhr.open("post", FileController, true);
    xhr.onload = function (json) {
        var obj = jQuery.parseJSON(json.target.responseText);
        console.log(obj);
        layer.closeAll('loading');
        if(obj.code == 2000){
            // console.log(obj.data.key);return;
            var html = '<input type="hidden" name="'+imagename+'" value="'+obj.data.picKey+'">';
            
            $("."+imagename).html(html);
    //$(this).parent().find('.imgkey').html(html);
            alert(obj.message);
        }
    };
    xhr.send(form);
}

//初始化复选框
$(document).ready(function() {
	if($(".trademanager").val()=="1"){
		$(".trademanager").attr("checked","checked");
		$(".registerform").css("height","865px")
		$(".backgroundimgdiv").show();
		$(".sortdiv").show();
	}else{
		$(".trademanager").attr("checked",false);
		$(".registerform").css("height","465px")
		$(".backgroundimgdiv").hide();
		$(".sortdiv").hide();
	}
	if($(".recommengmanager").val()=="1"){
		$(".recommengmanager").attr("checked","checked")

	};

})
//点击复选框显示隐藏事件
function clickmanager(checkbox){
	
	if(checkbox.checked)
	{
		$(".registerform").css("height","865px")
		$(checkbox).val("1");
		if($(checkbox).attr("name") == "recommendedType"){
			$(".backgroundimgdiv").show();
			$(".sortdiv").show();
		}
	
	}
	else
	{
		$(".registerform").css("height","465px")
		$(checkbox).val("0");
		if($(checkbox).attr("name") == "recommendedType"){
			$(".backgroundimgdiv").hide();
			$(".sortdiv").hide();
		}
	}
}


</script>
</body>
</html>