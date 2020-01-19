<?php
//合作伙伴
class TacticAction extends CommonAction {

	//内容管理
	public function photo() {
		//验证用户权限
		parent::userauth2(99);
		$photo = D('photo');
		import('ORG.Util.Page');				// 导入分页类
		$count = $photo->where('iscoop = 3')->count();				//总记录数
		$Page = new Page($count,6);				//实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('header','条记录');
		$Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
		$Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
		$Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
		$Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
		$show = $Page->show();					//分页显示输出
		$list = $photo->where('iscoop = 3')->order('sortid desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('volist',$list);
		$this->assign('page',$show);
		$this->co=$count;
		$this->display();
	}

	//添加内容
	public function add() {
		parent::userauth2(98);
		$this->display();
	}

	//添加处理
	public function add_do() {
		if ($this->isPost()) {
			$data = array();
			$data['desc'] = htmlspecialchars($_POST['desc']);
			$data['sortid'] = $_POST['sortid'];
			$data['isshow'] = $_POST['show'];
			$data['time'] = time();
			$data['iscoop'] = 3;
			$photo = D('photo');

			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','gif','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/photo/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));
			
			if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			}
			//自动创建并验证数据

			$data['img'] = $info[0]['savename'];
			if ($photo->create($data)) {
				$photo->add();
				parent::operating(__ACTION__,0,'新增成功');
				$this->success('添加成功',__APP__.'/Tactic/photo',3);
			}else {
				parent::operating(__ACTION__,1,'新增失败'.$photo->getError());
				$this->error($photo->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}


	public function edit() {
		$id = intval(I('get.id','','htmlspecialchars'));

		$photo =M('photo');
		if ($result=$photo->where("id=$id")->find()) {
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
		if ($this->isPost()) {
			$data = array();
			$data['id'] = $_POST['id'];
			$data['desc'] = htmlspecialchars($_POST['desc']);
			$data['sortid'] = $_POST['sortid'];
			$data['isshow'] = $_POST['show'];
			$data['time'] = time();
			$data['iscoop'] = 3;
			$photo = D('photo');
			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/photo/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));
			

			if($_FILES['img']['name']){
				if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
				}else{// 上传成功 获取上传文件信息 删除原来的文件
				$img = $photo->where('id ='.$data['id'])->field('img')->find();
				$img = $img['img'];
				@unlink("./Public/Upload/photo/$img");
				$info =  $upload->getUploadFileInfo();
				}
				//自动创建并验证数据
//				var_dump($info[0]['savename']);exit;
				$data['img'] = $info[0]['savename'];
			}
			//自动创建并验证数据
			if ($photo->create($data)) {
				$photo->save();
				parent::operating(__ACTION__,0,'更新成功');
				$this->success('修改成功',__APP__.'/Tactic/photo',3);
			}else {
				parent::operating(__ACTION__,1,'更新失败：'.$photo->getError());
				$this->error($photo->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}

	//删除
	public function photodel() {
		//验证用户权限
		//判断是否是ajax请求
		if ($this->isAjax()) {
			$id = I('post.id','');
			if ($id=='') {
				parent::operating(__ACTION__,1,'参数错误');
				R('Public/errjson',array('参数ID类型错误'));
			}else {
				$id=intval($id);
				$photo=M('photo');
				//删图片
				$img = $photo->where("id=$id")->field('img')->find();
				$img = $img['img'];
				@unlink("./Public/Upload/photo/$img");
				if ($photo->where("id=$id")->delete()) {
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