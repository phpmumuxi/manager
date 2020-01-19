<?php

//公共函数

//生成查询URL
function createsearchurl($action, $code, $value, $tag='add', $code2, $value2){
	$param = array();
	foreach($_GET as $k=>$v){
		if(!is_array($v) && $k!='pn'){
			$param[$k] = $v;
		}
	}
	if(array_key_exists($code, $param)){
		$param[$code] = $value;
	}else{
		$param[$code] = $value;
	}
	if($tag != 'add'){
		unset($param[$code]);
	}
	if(array_key_exists($code2, $param)){
		$param[$code2] = $value2;
	}else{
		$param[$code2] = $value2;
	}
	
	return U($action, $param);
}
/**http请求
 * @author zyl
 * @param  author zyl
 * @param  string $url      请求的url地址
 * @param  string $timeout  请求的url超时的时间
 * @param  array  $postData post请求的数据
 * @return string           请求获取的数据
 */

function httpRequest($url,$timeout=30, $postData=[])
{

	// 初始化
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
	$ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
	if($ssl){
		//避开ssl验证
		// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		//证书-start
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, C('SSLFILEPEM'));//根证书
		curl_setopt($ch, CURLOPT_COOKIEJAR,C('SSLCOOKIE')); //保存
		curl_setopt($ch, CURLOPT_COOKIEFILE,C('SSLCOOKIE'));//读取
	}
	//echo C('SSLFILEPEM');exit;
	curl_setopt($ch, CURLOPT_HEADER, 0);//不获取header头信息
	//echo $url.'<br/>';
	// 发送post请求
	if(!empty($postData)){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	}
	// 设置请求url
	curl_setopt($ch, CURLOPT_URL, $url);
	// 设置结果以返回值形式
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 执行
	$reuslt = curl_exec($ch);
	if(curl_errno($ch)) {
		//print_r(curl_getinfo($ch));
		print_r(curl_errno($ch));
	}
	curl_close($ch);
	return json_decode($reuslt,true);//返回数据，json格式
}

/**
  * 发送HTTP请求方法
  * @param  string $url    请求URL
  * @param  array  $params 请求参数
  * @param  string $method 请求方法GET/POST
  * @return array  $data   响应数据
  */
function http($url, $params, $method = 'GET', $header = array(), $multi = false){
     $opts = array(
             CURLOPT_TIMEOUT        => 30,
             CURLOPT_RETURNTRANSFER => 1,
             CURLOPT_SSL_VERIFYPEER => false,
             CURLOPT_SSL_VERIFYHOST => false,
             CURLOPT_HTTPHEADER     => $header
    );
    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
             $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
             $params = $multi ? $params : http_build_query($params);
             $opts[CURLOPT_URL] = $url;
             $opts[CURLOPT_POST] = 1;
             $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
     $ch = curl_init();
     curl_setopt_array($ch, $opts);
     $data  = curl_exec($ch);
     $error = curl_error($ch);
     curl_close($ch);
    if($error) throw new Exception('请求发生错误：' . $error);
    return  $data;
}


//调用
// //定义一个要发送的目标URL；
// $url = "https://www.xxx.com";
// //定义传递的参数数组；
// $data['aaa']='aaaaa';
// $data['bbb']='bbbb';
// //定义返回值接收变量；
// $httpstr = http($url, $data, 'POST', array("Content-type: text/html; charset=utf-8"));

function get($name = null){
    if($name) return isset($_GET[$name]) ? $_GET[$name] : '';
    else return $_GET;
}

/**
 *
 * @param number $page_len 显示页码数
 * @param string $Pagesize 每页显示数量
 *  @param string $count 总记录数
 */
function ownpage($count=0,$Pagesize,$page_len=7){
	$page_count = ceil($count/$Pagesize);
	$p = 'page';
	$request_url =$_SERVER['REQUEST_URI'];
	if(strpos($request_url,'?')){
		$url  =  $request_url;
	}else{
		$url  =  $request_url.'?';
	}

	$parse = parse_url($url);
	if(isset($parse['query'])) {
		parse_str($parse['query'],$_GET);
		unset($params[$p]);
		$url   =  $parse['path'].'?'.http_build_query($_GET);
	}
	$init=1;
	$max_p=$page_count;
	$pages=$page_count;
	//判断当前页码
	if(empty($_GET['page'])||$_GET['page']<0){
		$page=1;
	}else {
		$page=$_GET['page'];
	}
	$page_len = ($page_len%2)?$page_len:$pagelen+1;//页码个数
	$pageoffset = ($page_len-1)/2;//页码个数左右偏移量

	$pagekey='<div id="page" class="page">';
	$pagekey.="<span>共".$count."条记录</span> "; //总记录
	$pagekey.="<span>$page/$pages</span> "; //第几页,共几页
	if($page!=1){
		$pagekey.="<a href=\"".$url."&page=1 \">第一页</a> "; //第一页
		$pagekey.="<a href=\"".$url."&page=".($page-1)."\">上一页</a>"; //上一页
	}else {
		$pagekey.="第一页 ";//第一页
		$pagekey.="上一页"; //上一页
	}
	if($pages>$page_len){
		//如果当前页小于等于左偏移
		if($page<=$pageoffset){
			$init=1;
			$max_p = $page_len;
		}else{//如果当前页大于左偏移
			//如果当前页码右偏移超出最大分页数
			if($page+$pageoffset>=$pages+1){
				$init = $pages-$page_len+1;
			}else{
				//左右偏移都存在时的计算
				$init = $page-$pageoffset;
				$max_p = $page+$pageoffset;
			}
		}
	}
	for($i=$init;$i<=$max_p;$i++){
		if($i==$page){
			$pagekey.=' <span>'.$i.'</span>';
		} else {
			$pagekey.=" <a href=\"".$url."&page=".$i."\">".$i."</a>";
		}
	}
	if($page!=$pages){
		$pagekey.=" <a href=\"".$url."&page=".($page+1)."\">下一页</a> ";//下一页
		$pagekey.="<a href=\"".$url."&page=".$pages."\">最后一页</a>"; //最后一页
	}else {
		$pagekey.="下一页 ";//下一页
		$pagekey.="最后一页"; //最后一页
	}
	//选择每页显示数目
	$pagekey .= '  每页显示   <select style="width:50px;display:inline-block;" id="selectpagesize" data-date='.$date.'>
 										<option >'.$Pagesize.'</option>
										<option value="10">10</option>
										<option value="5">5</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="30">30</option>
										<option value="40">40</option>
										<option value="50">50</option>
								</select>  条</div>';
	$pagekey.='</div>';

	return $pagekey;
}
/**
 * 截取中文字符串
 */
function mbsubstr($str, $start=0, $length, $charset="utf-8", $suffix=false){
 if(function_exists("mb_substr")){
  if($suffix)
   return mb_substr($str, $start, $length, $charset)."...";
  else
   return mb_substr($str, $start, $length, $charset);
 }elseif(function_exists('iconv_substr')) {
  if($suffix)
   return iconv_substr($str,$start,$length,$charset)."...";
  else
   return iconv_substr($str,$start,$length,$charset);
 }
 $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
 $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
 $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
 $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
 preg_match_all($re[$charset], $str, $match);
 $slice = join("",array_slice($match[0], $start, $length));
 if($suffix) return $slice."…";
 return $slice;
}


function comment($list){
  foreach ($list as $val) {
      //echo($val);
      if(is_array($val)){
        $img = $val['url'];
        //echo  "<img src=$img>";
      }
  }
}


function ftype($filename){
  $file = fopen($filename, "rb");
  $bin  = fread($file, 2); //只读2字节
  fclose($file);
  $strInfo  = @unpack("C2chars", $bin);
  $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
  $fileType = '';
  switch($typeCode){
    case 7790:
      $fileType = 'exe';
      break;
    case 7784:
      $fileType = 'midi';
      break;
    case 8297:
      $fileType = 'rar';
      break;
    case 255216:
      $fileType = 'jpg';
      break;
    case 7173:
      $fileType = 'gif';
      break;
    case 6677:
      $fileType = 'bmp';
      break;
    case 13780:
      $fileType = 'png';
      break;
    default:
      $fileType = 'unknown';
  }
  return $fileType;
}


/*

a   NUL-padded string(NUL填充字符串)
A   SPACE-padded string(空格填充字符串)
h   Hex string, low nibble first(十六进制字符串，先写低四位)
H   Hex string, high nibble first(十六进制字符串，先读高四位)
c signed char(有符号字符)
C   unsigned char(无符号字符)
s   signed short(有符号短整型) (always 16 bit, machine byte order)(总是16位，机字节顺序)
S   unsigned short(无符号短整型) (always 16 bit, machine byte order)(总是16位，机字节顺序)
n   unsigned short(无符号短整型) (always 16 bit, big endian byte order)(总是16位，大endian字节顺序)
v   unsigned short(无符号短整型) (always 16 bit, little endian byte order)(总是16位，little endian字节顺序)
i   signed integer(有符号整数) (machine dependent size and byte order)(取决于机器的大小和字节顺序)
I   unsigned integer(无符号整型) (machine dependent size and byte order)(取决于机器的大小和字节顺序)
l   signed long(有符号长整型) (always 32 bit, machine byte order)(总是32位，机字节顺序)
L   unsigned long(无符号长整型) (always 32 bit, machine byte order)(总是32位，机字节顺序)
N   unsigned long(无符号长整型) (always 32 bit, big endian byte order)(总是32位，大endian字节顺序)
V   unsigned long(无符号长整型) (always 32 bit, little endian byte order)(总是32位，little endian字节顺序)
f   float(单精度浮点型) (machine dependent size and representation)(取决于机器的大小和代表性)
d   double(双精度浮点型) (machine dependent size and representation)(取决于机器的大小和代表性)
x   NUL byte(NUL字节)
X   Back up one byte(备份一个字节)
@   NUL-fill to absolute position(NUL填充到绝对位置)


*/


/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 2 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $result = json_decode($result,true);
    return $result;
}

function zhiru($val){
  switch ($val) {
     case '0':
       $data = '支出';
       break;
     case '1':
       $data = '支出';
       break;
     case '2':
       $data = '支入';
       break;  
     case '3':
       $data = '支出';
       break;
     case '4':
       $data = '支入';
       break;
     case '5':
       $data = '支入';
       break;  
     default:
       $data = '';
       break;
    }
    return $data;
}

function zhengfu($val){
  
}
function zhichu($val){
  switch ($val) {
     case '0':
       $data = '提现';
       break;
     case '1':
       $data = '逸管家自营服务';
       break;
     case '2':
       $data = '在线收款';
       break;  
     case '3':
       $data = '在线支付';
       break;
     case '4':
       $data = '在线退款';
       break;
     case '5':
       $data = '充值';
       break;  
     default:
       $data = '';
       break;
    }
    return $data;
}

//user_id 生成规则
function getuid(){
	mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	$charid = strtoupper(md5(uniqid(rand(), true)));
	$hyphen = chr(45);// "-"
	$uuid = //chr(123)// "{"
	substr($charid, 0, 8)
	.substr($charid, 8, 4)
	.substr($charid,12, 4)
	.substr($charid,16, 4)
	.substr($charid,20,12);
	return $uuid;
}

//user_id 生成规则
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = //chr(123)// "{"
        substr($charid, 0, 8)
        .substr($charid, 8, 4)
        .substr($charid,12, 4)
        .substr($charid,16, 4)
        .substr($charid,20,12);
        //.chr(125);// "}"
        return $uuid;
    }
}

?>