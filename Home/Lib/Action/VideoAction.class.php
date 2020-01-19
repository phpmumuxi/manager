<?php
//视频类
class VideoAction extends CommonAction {

	//视频内容管理
	public function index(){
		$start = $_GET['page']?$_GET['page']:1;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		$data['start'] = ((int)$start-1)*(int)$data["pageSize"];
		if($_GET["userNickName"]){
			$data["userNickName"] = trim($_GET["userNickName"]);
			$this->assign("userNickName",$data["userNickName"]);
		}
		if($_GET["userTelephone"]){
			$data["userTelephone"] = trim($_GET["userTelephone"]);
			$this->assign("userTelephone",$data["userTelephone"]);
		}
		if($_GET["serviceTitle"]){
			$data["serviceTitle"] = trim($_GET["serviceTitle"]);
			$this->assign("serviceTitle",$data["serviceTitle"]);
		}
		if($_GET["sid"]){
			$data["sid"] = trim($_GET["sid"]);
			$this->assign("sid",$data["sid"]);
		}		
		if($_GET["isRecommend"]=='1'){
			$data["isRecommend"] = '1';
			$this->assign("isRecommend",$data["isRecommend"]);
		}
		if($_GET["checkStatus"]=='0'){
			$data["checkStatus"] = '0';
			$this->assign("checkStatus",$data["checkStatus"]);
		}
		if($_GET["isComplaint"]=='1'){
			$data["isComplaint"] = '1';
			$this->assign("isComplaint",$data["isComplaint"]);
		}
		if($_GET["serviceStatus"]=='0'){
			$data["serviceStatus"] = '0';
			$this->assign("serviceStatus",$data["serviceStatus"]);
		}
		$html = send_post(C('URL').'/manager/video/getServceVideoBaseAuditList',$data);
		$count = $html['data']['count'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('pageSize',$data["pageSize"]);
		$this->assign('list',$html['data']['list']);
		$this->display();
	}

	//详情信息
	public function info(){		
		$data['sid'] =  $_GET['sid'];
		$html = send_post(C('URL').'/manager/video/getServiceVideoBaseDetail',$data);
		$this->assign('info',$html['data']);
		$this->display();
	}

	//被举报列表
	public function indexComplaint(){
		$start = $_GET['page']?$_GET['page']:1;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;		
		$data['start'] = ((int)$start-1)*(int)$data["pageSize"];
		if($_GET["isComplaint"]=='1'){
			$data["isComplaint"] = '1';
			$this->assign("isComplaint",$data["isComplaint"]);
		}
		
		$html = send_post(C('URL').'/manager/video/getVideoComplaintList',$data);
		$count = $html['data']['tatolCount'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('pageSize',$data["pageSize"]);
		$this->assign('list',$html['data']['videoComplaintList']);
		$this->display();
	}

	//白名单列表
	public function user(){
		$start = $_GET['page']?$_GET['page']:1;
		$data["size"] = $_GET['pageSize']?$_GET['pageSize']:15;
		$data['pageNum'] = ((int)$start-1)*(int)$data["size"];
		if($_GET["nickName"]){
			$data["nickName"] = trim($_GET["nickName"]);
			$this->assign("nickName",$data["nickName"]);
		}
		if($_GET["telephone"]){
			$data["telephone"] = trim($_GET["telephone"]);
			$this->assign("telephone",$data["telephone"]);
		}
		
		$html = httpRequest(C("URL").'/manager/video/queryWhiteListUser',30,$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['data']['totalRowNumber'],$pageSize,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['dataList']);
		$this->display();
	}

	//添加白名单
	public function addUser(){
		$start = $_GET['page']?$_GET['page']:1;
		$data["size"] = $_GET['pageSize']?$_GET['pageSize']:15;
		$data['pageNum'] = ((int)$start-1)*(int)$data["size"];
		if($_GET["nickName"]){
			$data["nickName"] = trim($_GET["nickName"]);
			$this->assign("nickName",$data["nickName"]);
		}
		if($_GET["telephone"]){
			$data["telephone"] = trim($_GET["telephone"]);
			$this->assign("telephone",$data["telephone"]);
		}
		
		$html = httpRequest(C("URL").'/manager/video/queryUserNotInWhiteList',30,$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['data']['totalRowNumber'],$pageSize,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['dataList']);
		$this->display();
	}

	//内容管理
	public function video() {
		//验证用户权限
		 parent::userauth2(96);
		$video = D('video');
		import('ORG.Util.Page');				// 导入分页类
		$count = $video->where('pid = 2')->count();				//总记录数
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		// $p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$list = $video->where('pid = 2')->order('sortid desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();
		$this->assign('volist',$list);
		$this->assign('page',$show);
		$this->co=$count;
		$this->display();
	}

	public function video2() {
		//验证用户权限
		parent::userauth2(96);
		$video = D('video');
		import('ORG.Util.Page');				// 导入分页类
		$count = $video->where('pid = 3')->count();				//总记录数
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		// $p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$list = $video->where('pid = 3')->order('sortid desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();
		$this->assign('volist',$list);
		$this->assign('page',$show);
		$this->co=$count;
		$this->display();
	}

	//添加内容
	public function add() {
		parent::userauth2(95);
		$this->display();
	}

	//添加处理
	public function add_do() {
		if ($this->isPost()) {
		
			$data = array();
			$data['pid'] = $_POST['pid'];
			$data['title'] = htmlspecialchars($_POST['title']);
			$data['VideoIDS'] = htmlspecialchars($_POST['VideoIDS']);
			$data['sortid'] = $_POST['sortid'];
			$data['atten'] = $_POST['atten'];
			$data['time'] = time();
			$video = D('video');

			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/video/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));
			// $upload->thumb = true;
			// $upload->thumbMaxWidth = '1280';
			// $upload->thumbMaxHeight = '720';
			// $upload->thumbRemoveOrigin = 'true';
			// $upload->thumbPrefix = 'v_';
			if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			}
			//自动创建并验证数据
			$data['img'] = $info[0]['savename'];
			if ($video->create($data)) {
				$video->add();
				parent::operating(__ACTION__,0,'新增成功');
				if($_POST['pid'] == 2){
					$this->success('添加成功',__APP__.'/Video/video',3);
				}else{
					$this->success('添加成功',__APP__.'/Video/video2',3);
				}

			}else {
				parent::operating(__ACTION__,1,'新增失败'.$video->getError());
				$this->error($video->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}

	//更新视频
	public function edit() {
		// parent::win_userauth(50);
		$id = intval(I('get.id','','htmlspecialchars'));

		$video =M('video');
		if ($result=$video->where("id=$id")->find()) {
			//分类数据
			$this->vo=$result;
			$this->display();
		}else {
			parent::operating(__ACTION__,1,'数据不存在');
			$this->error('没有找到相关数据');
		}
	}
	//修改处理
	public function edit_do() {
		// parent::userauth2(50);
		if ($this->isPost()) {
			$data = array();
			$data['id'] = $_POST['id'];
			$data['pid'] = $_POST['pid'];
			$data['title'] = htmlspecialchars($_POST['title']);
			$data['VideoIDS'] = htmlspecialchars($_POST['VideoIDS']);
			$data['sortid'] = $_POST['sortid'];
			$data['atten'] = $_POST['atten'];
			$data['time'] = time();
			$video = D('video');
			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/video/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));
			// $upload->thumb = true;
			// $upload->thumbMaxWidth = '1280';
			// $upload->thumbMaxHeight = '720';
			// $upload->thumbRemoveOrigin = 'true';
			// $upload->thumbPrefix = 'v_';
			if($_FILES['img']['name']){
				if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
				}else{// 上传成功 获取上传文件信息 删除原来的文件
				$img = $video->where('id ='.$data['id'])->field('img')->find();
				$img = $img['img'];
				@unlink("./Public/Upload/video/$img");
				$info =  $upload->getUploadFileInfo();
				}
				//自动创建并验证数据
				$data['img'] = $info[0]['savename'];
			}
	
			//自动创建并验证数据
			if ($video->create($data)) {
				$video->save();
				parent::operating(__ACTION__,0,'更新成功');
				if($_POST['pid'] == 2){
					$this->success('修改成功',__APP__.'/Video/video',3);
				}else{
					$this->success('修改成功',__APP__.'/Video/video2',3);
				}

			}else {
				parent::operating(__ACTION__,1,'更新失败：'.$video->getError());
				$this->error($video->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}

	//删除
	public function videodel() {
		//验证用户权限
		// parent::userauth(51);
		//判断是否是ajax请求
		if ($this->isAjax()) {
			$id = I('post.id','');
			if ($id=='') {
				parent::operating(__ACTION__,1,'参数错误');
				R('Public/errjson',array('参数ID类型错误'));
			}else {
				$id=intval($id);
				$video=M('video');
				//删图片
				$img = $video->where("id=$id")->field('img')->find();
				$img = $img['img'];
				@unlink("./Public/Upload/video/$img");
				if ($video->where("id=$id")->delete()) {
					
					parent::operating(__ACTION__,0,'删除成功');
					R('Public/errjson',array('ok'));
				}else {
					parent::operating(__ACTION__,1,'删除失败');
					R('Public/errjson',array('数据不存在'));
				}
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
}
?>