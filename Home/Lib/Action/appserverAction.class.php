<?php
class appserverAction extends Action {
	
	public function _initialize() {
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
	}
	
	
	
	/**
	 *@author  zyl
	 *下载合同之前输入客户地址信息 
	 */
	
	public function insertAreaInfo(){
		if($_POST){
			//添加
			$addData['sign_user'] = $_POST['signUser'];
			$addData['sign_user_tel'] = $_POST['signUserTel'];
			$addData['business_img'] = $_POST['businessImg'];//营业执照
			$addData['recom_img'] = $_POST['recomImg'];//推荐函
			$addData['title'] = $_POST['title'];//标题
			$addData['create_time'] = date("Y-m-d H:i:s",time());
			$addData['status'] = 0;
			if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9]|19[0-9])\d{8}$/',$addData['sign_user_tel']))
				exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
		
			if (!$addData['sign_user'] || !$addData['sign_user_tel'] || !$addData['business_img']){
				$data = ['success' =>false , "message" => "参数不完整"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}
		
			$addResult = M("signContract")->add($addData);
			if($addResult){
				$data = ['success' =>true , "message" => "签署成功" ];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}else{
				$data = ['success' =>false , "message" => "签署失败"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}
		}
	}
	/**
	 * @author zyl
	 * 在线签署
	 */
	public function signCompact (){
		if($_POST){
				//添加
				$addData['sign_user'] = $_POST['signUser'];
				$addData['sign_user_tel'] = $_POST['signUserTel'];
				$addData['business_img'] = $_POST['businessImg'];//营业执照
				$addData['recom_user_tel'] = $_POST['recomUserTel'];//推荐人手机号
				$addData['recom_img'] = $_POST['recomImg'];//推荐函
				$addData['title'] = $_POST['title'];//标题
				$addData['user_id'] = $_POST['userId'];//登录人用户id
				$addData['sid'] = $_POST['sid'];//合同id
				$addData['create_time'] = date("Y-m-d H:i:s",time());
				$addData['status'] = 0;
				
				// if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9])\d{8}$/',$addData['sign_user_tel']))
				// 	exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
				// if($addData['recom_user_tel']){
				// 	if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9])\d{8}$/',$addData['recom_user_tel']))
				// 	exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
				// }
				if (!$addData['sign_user'] || !$addData['sign_user_tel'] || !$addData['business_img'] || !$addData['user_id'] || !$addData['sid']){
					$data = ['success' =>false , "message" => "参数不完整"];
					exit(json_encode($data, JSON_UNESCAPED_UNICODE));
				}
				$where['sign_user_tel'] = $addData['sign_user_tel'];
				$where['sid'] = $addData['sid'];
				$where['status']  = array('lt' ,3 );
				$res = M("signContract")->where($where)->find();
				if($res){
					$addData['update_time'] = date("Y-m-d H:i:s",time());
					unset($addData['create_time']);
					$addResult = M("signContract")->where($where)->save($addData);
				}else{
					$addResult = M("signContract")->add($addData);
				}
				if($addResult){
					$data = ['success' =>true , "message" => "签署成功" ];
					exit(json_encode($data, JSON_UNESCAPED_UNICODE));
				}else{
					$data = ['success' =>false , "message" => "签署失败"];
					exit(json_encode($data, JSON_UNESCAPED_UNICODE));
				}
		}
	}
	
	//管家经理报名 待申请数据统计
	public function registerManager (){
		if($_POST){
			$addData['user_telephone'] = $_POST['user_telephone'];
			$addData['industry_type'] = $_POST['industry_type'];
			$addData['industry_code'] = $_POST['industry_code'];
			$addData['service_area'] = $_POST['service_area'];
			$addData['service_time'] = $_POST['service_time'];
			$addData['user_remark'] = $_POST['user_remark'];
			$addData['create_time'] = date("Y-m-d H:i:s",time());
			
			if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9]|19[0-9])\d{8}$/',$addData['user_telephone']))
				exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
				
			if (!$addData['user_telephone'] || !$addData['industry_type'] || !$addData['industry_code'] || !$addData['service_area'] || !$addData['service_time']){
				$data = ['success' =>false , "message" => "参数不完整"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}
			
			$datas['telephone']=$addData['user_telephone'];
			//判断该用户是否已经报名管家经理
			$res = send_post(C('URL').'/appserver/houseManager/queryHouseManagerByTelephone',$datas);
			
			if($res['message']){
				$data = ['success' =>false , "message" => "你已申请为管家经理"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}else{
				$where['user_telephone'] = $addData['user_telephone'];
				$res = M("register_manager")->where($where)->find();
				if($res){	//更新
					$re = M("register_manager")->where($where)->save($addData);
					if($re){
						$data = ['success' =>true , "message" => "申请更新成功" ];
						exit(json_encode($data, JSON_UNESCAPED_UNICODE));
					}else{
						$data = ['success' =>false , "message" => "申请更新失败"];
						exit(json_encode($data, JSON_UNESCAPED_UNICODE));
					}
				}else{					
					$addResult = M("register_manager")->add($addData);
					if($addResult){
						$data = ['success' =>true , "message" => "申请成功" ];
						exit(json_encode($data, JSON_UNESCAPED_UNICODE));
					}else{
						$data = ['success' =>false , "message" => "申请失败"];
						exit(json_encode($data, JSON_UNESCAPED_UNICODE));
					}
				}

			}
		}
	}

	//管家经理报名成功，删除php库临时数据
	public function delRegisterManager (){
		$datas = $_POST['telephone'];
		
		if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9]|19[0-9])\d{8}$/',$datas))
			exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
			
		if (!$datas){
			$data = ['success' =>false , "message" => "参数不完整"];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));
		}

		$where['user_telephone'] = $datas;
		$res = M("register_manager")->where($where)->delete();	
		if($res){				
			$data = ['success' =>true , "message" => "删除成功" ];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));
		}else{				
			$data = ['success' =>false , "message" => "删除失败"];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));	
		}	
	}

	//根据手机号查询所有管家经理数据
	public function selectRegisterManagerData (){
		$datas = $_POST['telephone'];
		
		if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9]|19[0-9])\d{8}$/',$datas))
			exit(json_encode(["success" => false,"message" => '手机号码格式不正确'], JSON_UNESCAPED_UNICODE));
			
		if (!$datas){
			$data = ['success' =>false , "message" => "参数不完整"];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));
		}
		
		$where['user_telephone'] = $datas;
		$res = M("register_manager")->where($where)->find();	
		if($res){				
			$data = ['success' =>true , "message" => "查询成功" , "data" => $res];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));
		}else{				
			$data = ['success' =>false , "message" => "查询失败", "data" => ''];
			exit(json_encode($data, JSON_UNESCAPED_UNICODE));	
		}	
	}

	/**
	 * @author zyl
	 * 图片上传接口
	 */
	public function uploadImg(){
		$imgFormatArr = ["jpeg","jpg","bmp","png"];
		if($_FILES){
			if(!is_dir('./Public/Upload/checkImgs')){
				mkdir('./Public/Upload/checkImgs');
			}
			$oldName = $_FILES["file"]["name"];
			$imgFormat =  substr(strrchr($oldName, '.'), 1);//图片格式(只允许gps)
			if(!in_array(strtolower($imgFormat), $imgFormatArr)){
				$data = ['success' =>false , "message" => "图片格式不正确,支持格式为（jpeg,jpg,bmp,png）"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}
			$imgName = time().".".$imgFormat;//把名称格式化为时间戳
			$path = "./Public/Upload/checkImgs/".$imgName;
			if(move_uploaded_file($_FILES["file"]["tmp_name"],$path)){
				$data = ['success' =>true , "message" => "上传成功" ,"data" => ['imagePath' => __ROOT__.'/'.$path]];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}else{
				$data = ['success' =>false , "message" => "上传失败"];
				exit(json_encode($data, JSON_UNESCAPED_UNICODE));
			}
		}
	}
	
	
	/**@author zyl
	 * 新闻列表
	 */
	public function newslist(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$output = array();
		$news = D('news');
		//接受数据
		$Sid = $_REQUEST['Sid'];
		$start = empty($_REQUEST['start'])?0:$_REQUEST['start'];
		$pageSize = $_REQUEST['pageSize'];
		if (empty($pageSize)||empty($Sid)) {
			$output = array('data'=>NULL, 'message'=>'参数不完整!', 'code'=>-400);
			exit(json_encode($output));
		}
		$where['Sid'] = $_REQUEST['Sid'];
		$count = $news->where($where)->count();
		$volist = $news->where($where)->order('Dtime desc')->limit($start.','.$pageSize)->field('ID,Sid,Uid,Title,Description,Content,Sortid,View,Dtime,url,img,source,push')->cache(true,300)->select();
		$list = array();
		foreach ($volist as $v) {
			if ($v['url'] =='') {
				$v['url'] = C('ADMIN_URL').'/index.php/appserver/newarticle.html?id='. $v['ID'];
				if(substr($v['img'],0,4) !== 'http'){
					$v['img'] = C('ADMIN_URL').'/Public/Upload/news/'.$v['img'];
				}else{
					$v['img'] = $v['img'];
				}
				array_push($list,$v);
			}
		}
		//输出数据
		$output = array(
				'code' => 2000, //成功与失败的代码，一般都是正数或者负数
				'message' => 'success', //消息提示，客户端常会用此作为给弹窗信息。
				'count'=> $count,
				'data' => array('newslist' => $list),
				);
		exit(json_encode($output));
	}


	//文章详情
	public function newarticle() {
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$id = I('get.id','','htmlspecialchars');
		if ($id =='' && !is_numeric($id)) {
			$this->error('参数错误');
		}
		$id = intval($id);
		$news = D('News');
		$result=$news->relation(true)->where("ID = $id")->find();
		$news->where("ID = $id")->setInc('View');
		//分类数据
		$newsclass = M('newsclass');
		$clist = $newsclass->order('ID asc')->select();
		$this->assign('result',$result);
		$this->assign('clist',$clist);
		$this->display();
	}

	//文章详情
	public function article() {
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$id = I('post.id','','htmlspecialchars');
		if(!$id){
			$output = array('data'=>NULL, 'message'=>'参数错误', 'code'=>5000);
			exit(json_encode($output));
		}
		$news = D('News')->where('ID = "%d"',$id)->field('Sid,Title,Description,Content,Dtime,img,source')->find();
		if($news){
			$output = array(
				'code' => 2000,
				'message' => 'success',
				'data' => $news,
				);
		}else{
			$output = array(
				'code' => 5000,
				'message' => 'error',
				'data' => $news,
				);
		}
		exit(json_encode($output));
	}


	//新闻点击量
	public function clicks(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$id = I('post.id','','htmlspecialchars');
		if(!$id){
			$output = array('data'=>NULL, 'message'=>'参数错误', 'code'=>5000);
			exit(json_encode($output));
		}
		$data = M('news')->where('id = "%d"',$id)->setInc('View','1');
		if($data){
			$output = array(
				'code' => 2000, //成功与失败的代码，一般都是正数或者负数
				'message' => 'success', //消息提示，客户端常会用此作为给弹窗信息。
				'data' => NULL,
				);
			exit(json_encode($output));
		}else{
			$output = array(
				'code' => 5000, //成功与失败的代码，一般都是正数或者负数
				'message' => 'error', //消息提示，客户端常会用此作为给弹窗信息。
				'data' => NULL,
				);
			exit(json_encode($output));
		}
	}

	//热点资讯
	public function newshot(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$map['Dtime'] = array(array('egt',date('Y-m-d H:i:s',(time()-432000))), array('elt',date('Y-m-d H:i:s',time())));
		$news = M('news')->where($map)->order('View DESC')->limit(10)->field('ID,Title')->select();
		if($news){
			$output = array(
				'code' => 2000, //成功与失败的代码，一般都是正数或者负数
				'message' => 'success', //消息提示，客户端常会用此作为给弹窗信息。
				'data' => $news,
				);
			exit(json_encode($output));
		}else{
			$output = array(
				'code' => 5000, 
				'message' => 'error',
				'data' => NULL,
				);
			exit(json_encode($output));
		}
	}

	public function doapply(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$apply = M("apply");
		$apply->name = $_REQUEST['name'];
		$apply->telephone = $_REQUEST['telephone'];
		$apply->email = $_REQUEST['email'];
		$apply->area = $_REQUEST['area'];
		$apply->parent_id = $_REQUEST['parent_id'];
		$apply->comment = isset($_REQUEST['comment'])?$_REQUEST['comment']:'';
		$apply->user_id = guid();
		$apply->time = time();
		$apply->ip = $_SERVER["REMOTE_ADDR"];
		//验证同一个手机号只能提交一次
		$data = $apply->where("telephone = '%s'",$_REQUEST['telephone'])->field('name,telephone,email')->select();
		if($data){
			$output = array('data'=>NULL,'message'=>'手机号已被申请','code'=>5000);
			exit(json_encode($output));
		}
		if($_REQUEST['name'] && $_REQUEST['telephone'] && $_REQUEST['email'] && $_REQUEST['area'] && $_REQUEST['parent_id']){
			if($apply->add()){
				$output = array('data'=>success,'message'=>'提交成功','code'=>2000);
				exit(json_encode($output));
			}else{
				$output = array('data'=>NULL,'message'=>'提交失败','code'=>5000);
				exit(json_encode($output));
			}
		}else{
			$output = array('data'=>NULL,'message'=>'参数不完整','code'=>5000);
			exit(json_encode($output));
		}
	}

	//搜索代理商
	public function apply(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$mysql = "mysql://eachonline_crm:Eachonline_crm@2016@192.168.0.5:33066/eachonline_crm";
		$_REQUEST['key'] = empty($_REQUEST['key'])?'江苏':$_REQUEST['key'];
		//$mysql = "mysql://root:root@192.168.1.206:3306/crm";
		$sql = "SELECT u.user_id as userId,u.name FROM crm_user u,crm_role r WHERE r.role_id = u.role_id AND r.position_id = '21' AND u.name like '%$_REQUEST[key]%'";
		$users = M("user","crm_",$mysql)->query($sql);
		if($users){
			$output = array(
				'code' => 2000, 
				'message' => 'success', 
				'data' => $users,
				);
			exit(json_encode($output));
		}else{
			$output = array('data'=>NULL,'message'=>'error','code'=>5000);
			exit(json_encode($output));
		}
	}

	//判断代理是否已经被申请
	public function checkapply(){
		header('Access-Control-Allow-Origin:*');
		if(!$_REQUEST['userId']){
			$output = array('data'=>NULL,'message'=>'参数不完整','code'=>5000);
			exit(json_encode($output));
		}
		//$mysql = "mysql://root:root@192.168.1.206:3306/crm";
		$mysql = "mysql://eachonline_crm:Eachonline_crm@2016@192.168.0.5:33066/eachonline_crm";
		$sql = "SELECT name FROM crm_user u WHERE  parent_id = '$_REQUEST[userId]'";
		$user = M("user","crm_",$mysql)->query($sql);
		if($user){
			$output = array(
				'code' => 5000, 
				'message' => '该区域已有代理商', 
				'data' => $users,
				);
			exit(json_encode($output));
		}else{
			$output = array(
				'code' => 2000, 
				'message' => 'success', 
				'data' => null,
				);
			exit(json_encode($output));
		}
	}


	//战略合作伙伴(线上模式)
	public function partnership(){
		header('content-type:application:json;charset=utf8');
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with,content-type');
		$photos = M('photo')->where("iscoop = '3' and isshow = '1'")->field('id,img,time,desc')->select();
		
		$list = array();
		foreach ($photos as $v) {
			$v['img'] = 'http://admin.yiguanjiaclub.net/Public/Upload/photo/'.$v['img'];
			$v['time'] = date('Y-m-d H:i:s',$v['time']);
			array_push($list,$v);
		}
		if($photos){
			$output = array(
				'code' => 2000, 
				'message' => 'success', 
				'data' => $list,
				);
			exit(json_encode($output));
		}else{
			$output = array(
				'code' => 5000, 
				'message' => 'error', 
				'data' => null,
				);
			exit(json_encode($output));
		}
	}
}
?>