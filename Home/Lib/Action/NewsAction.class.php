<?php
use QL\QueryList;
class NewsAction extends CommonAction {
	public function classindex() {
		parent::userauth2(45);
		$newsclass = D('Newsclass');
		import('ORG.Util.Page');		// 导入分页类
		$count = $newsclass->count();			//总记录数
		$Page = new Page($count,15);		//实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('header','条记录');
		$Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
		$Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
		$Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
		$Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
		$show = $Page->show();			//分页显示输出
		$volist=$newsclass->relation(true)->order('Dtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		$this->assign('volist',$volist);
		$this->assign('page',$show);
		$this->co=$count;
		$this->display();
	}

	//添加分类
	public function classadd() {
		parent::win_userauth(46);
		$this->display();
	}


	//添加分类处理
	public function classadd_do() {
		parent::userauth(46);
		if ($this->isAjax()) {
			$data = array();
			$data['ClassName'] = I('post.classname','');
			// $data['Aliases'] = I('post.aliases','');
			$data['Description'] = I('post.description');
			$newsclass = D('Newsclass');
			//自动创建并验证数据
			if ($newsclass->create($data)) {
				$newsclass->add();
				parent::operating(__ACTION__,0,'新增成功');
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'新增失败:'.$newsclass->getError());
				R('Public/errjson',array($newsclass->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}

	
	//修改分类
	public function classedit() {
		parent::win_userauth(47);
		$id = I('get.id','');
		if ($id=='' || !is_numeric($id)) {
			parent::operating(__ACTION__,1,'参数错误');
			$this->content='参数ID类型错误，请关闭本窗口';
			exit($this->display('Public:err'));
		}
		$id=intval($id);
		$newsclass=M('newsclass');
		if ($result=$newsclass->where("ID=$id")->find()) {
			$this->result=$result;
			$this->display();
		}else {
			parent::operating(__ACTION__,1,'数据不存在');
			$this->content='没有找到相关数据，请关闭本窗口';
			$this->display('Public:err');
		}
	}
	//修改分类处理
	public function classedit_do() {
		parent::userauth(47);
		if ($this->isAjax()) {
			$data = array();
			$data['ID'] = I('post.id','');
			$data['ClassName'] = I('post.classname','');
			$data['Aliases'] = I('post.aliases','');
			$data['Description'] = I('post.description');
			$newsclass = D('Newsclass');
			//自动创建并验证数据
			if ($newsclass->create($data)) {
				$newsclass->save();
				parent::operating(__ACTION__,0,'更新成功');
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'更新失败'.$newsclass->getError());
				R('Public/errjson',array($newsclass->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
	//删除分类
	public function classdel() {
		//验证用户权限
		parent::userauth(48);
		//判断是否是ajax请求
		if ($this->isAjax()) {
			$id = I('post.id','');
			if ($id=='' || !is_numeric($id)) {
				parent::operating(__ACTION__,1,'参数错误');
				R('Public/errjson',array('参数ID类型错误'));
			}else {
				$id=intval($id);
				$newsclass=M('newsclass');
				if ($newsclass->where("ID=$id")->delete()) {
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
	
	//内容管理
	public function news() {
		//验证用户权限
		// parent::userauth2(84);
		$sid = I('get.sid','','htmlspecialchars');
		if ($sid!='') {
			$where['Sid'] = intval($sid);
		}
		// dump($_POST['k']);
		$where['Title'] = array('like','%'.$_POST['k'].'%');
		$news = D('News');
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$count = $news->where($where)->count();
		//dump($count);
		$p = new Page ($count,$page_row,8);

		$p->isJumpPage = true;
		//$p->function_name = 'yourself';
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		//dump($show);
		$volist=$news->relation(true)->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();
		//dump($volist);
		//echo $news->getLastSql();
		$return_data['data'] = $volist;
		$return_data['page'] = $p->show();
 
//var_dump($volist);exit;
		//分类数据
		$newsclass = M('newsclass');
		$clist = $newsclass->order('ID asc')->select();
		$this->assign('key',$_POST['k']);
		$this->assign('url',C('URL'));
		$this->assign('maxpage',$maxpage);
		$this->assign('urlnews','http://admin.yiguanjiaclub.net');
		$this->assign('urlpush',C('URLPUSH'));
		$this->assign('volist',$volist);
		$this->assign('clist',$clist);
		$this->assign('page',$show);
		$this->assign('sid',$sid);
		$this->co=$count;
		$this->display();
	}
	//添加内容
	public function add() {
		parent::win_userauth(49);
		$newsclass = M('newsclass');
		$clist = $newsclass->order('Dtime asc')->select();
		$this->assign('clist',$clist);
		//排序ID
		$news = M('News');
		$Sortid = $news->count()+1;
		$this->assign('Sortid',$Sortid);
		$this->display();
	}
	//添加处理
	public function add_do() {
		parent::userauth2(49);
		if ($this->isPost()) {
			$data = array();
			$data['Uid'] = $_SESSION['ThinkUser']['ID'];
			$data['Sid'] = $_POST['Sid'];
			$data['Title'] = ($_POST['Title']);
			$data['Sortid'] = $_POST['Sortid'];
			$data['Description'] = ($_POST['Description']);
			$data['Content'] = str_replace('Array', '', $_POST['Content']);
			$data['source'] = $_POST['source'];
			$data['Dtime'] = date('Y-m-d H:i:s', time()); 
			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/news/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));

			if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			}
			//自动创建并验证数据
			$data['img'] = $info[0]['savename']; 
			$news = D('News');

			$map['Title'] = $_POST['Title'];
			$title = $news->where($map)->find();
		
			if($title){
				echo '此标题已经添加,请不要重复添加';
				$this->success('请勿重复',__APP__.'/News/add',3);
				exit;
			}
			//自动创建并验证数据
			if ($news->create($data)) {
				$news->add();
				parent::operating(__ACTION__,0,'新增成功');
				$this->success('添加成功',__APP__.'/News/news',0);
			}else {
				parent::operating(__ACTION__,1,'新增失败'.$news->getError());
				$this->error($news->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}
	//更新文章
	public function edit() {
		parent::win_userauth(50);
		$id = I('get.id','','htmlspecialchars');
		$Sid = I('get.Sid','','htmlspecialchars');
		if ($id=='' || !is_numeric($id)) {
			parent::operating(__ACTION__,1,'参数错误');
			$this->error('参数ID类型错误');
		}
		$id=intval($id);
		$news=M('news');
		if ($result=$news->where("ID=$id")->find()) {
			//分类数据
			$newsclass = M('newsclass');
			$clist = $newsclass->order('ID asc')->select();
			$this->result=$result;
			$this->clist=$clist;
			$this->display();
		}else {
			parent::operating(__ACTION__,1,'数据不存在');
			$this->error('没有找到相关数据');
		}
	}
	//修改处理
	public function edit_do() {
		parent::userauth2(50);
		if ($this->isPost()) {
			$data = array();
			$data['ID'] = $_POST['ID'];
			$data['Sid'] = $_POST['Sid'];
			$data['Title'] = ($_POST['Title']);
			$data['Sortid'] = $_POST['Sortid'];
			$data['Description'] = ($_POST['Description']);
			$data['Content'] = $_POST['Content'];
			$data['Dtime'] = date('Y-m-d H:i:s', time()); 
			$data['source'] = $_POST['source'];
			$news = D('News');
			import('ORG.Net.UploadFile');// 实例化上传类
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
			$upload->savePath = './Public/Upload/news/';// 设置附件上传目录
			$upload->saveRule = uniqid(time().rand(0,10000));
		
			if($_FILES['img']['name']){
				if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
				}else{// 上传成功 获取上传文件信息 删除原来的文件
				$img = $news->where('id ='.$data['id'])->field('img')->find();
				$img = $img['img'];
				@unlink("./Public/Upload/news/$img");
				$info =  $upload->getUploadFileInfo();
				}
				//自动创建并验证数据
				$data['img'] = $info[0]['savename']; 
			}
			//自动创建并验证数据
			if ($news->create($data)) {
				$news->where('ID ='.$data['ID'])->save();
				parent::operating(__ACTION__,0,'更新成功');
				$this->success('修改成功',__APP__.'/News/news',0);
			}else {
				parent::operating(__ACTION__,1,'更新失败：'.$news->getError());
				$this->error($news->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}

	//推送
	public function push(){
		//判断是否是ajax请求
		if ($this->isAjax()) {
			$id = I('post.id','');
			if ($id=='' || !is_numeric($id)) {
				parent::operating(__ACTION__,1,'参数错误');
				R('Public/errjson',array('参数ID类型错误'));
			}else {
				$data['ID']=intval($id);
				$data['push'] = 1;
				$news=M('news');
				//自动创建并验证数据
				if ($news->create($data)) {
					$news->save();
					$this->ajaxReturn('',"修改成功",1);
				}else {
					$this->ajaxReturn('',"修改失败",0);
				}
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}

	//文章详情
	public function article() {
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

	//删除
	public function newsdel() {
		//验证用户权限
		parent::userauth(51);
		//判断是否是ajax请求
		if ($this->isAjax()) {
			$id = I('post.id','');
			if ($id=='' || !is_numeric($id)) {
				parent::operating(__ACTION__,1,'参数错误');
				R('Public/errjson',array('参数ID类型错误'));
			}else {
				$id=intval($id);
				$news=M('news');
				if ($news->where("ID=$id")->delete()) {
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
	//批量删除
	public function newsindel() {
		//验证用户权限
		parent::userauth(51);
		if ($this->isAjax()) {
			if (!$delid=explode(',',I('post.delid',''))) {
				R('Public/errjson',array('请选中后再删除'));
			}
			//将最后一个元素弹出栈
			array_pop($delid);
			$id=join(',',$delid);
			$news=M('news');
			if ($news->delete("$id")) {
				parent::operating(__ACTION__,0,'删除成功');
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'删除失败');
				R('Public/errjson',array('删除失败'));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}

	//api获取第三方新闻
	
	//头条
	// public function head(){
	// 	$ch = curl_init();
	// 	$url = 'http://apis.baidu.com/showapi_open_bus/channel_news/search_news?channelId=5572a109b3cdc86cf39001db&channelName=%E5%9B%BD%E5%86%85%E6%9C%80%E6%96%B0&title=&page=1&needContent=1&needHtml=1';
	// 	$header = array(
	// 		'apikey: bb44bf5d5787448710c2103e6d9afc9a',
	// 		);
 //    // 添加apikey到header
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //    // 执行HTTP请求
	// 	curl_setopt($ch , CURLOPT_URL , $url);
	// 	$res = json_decode(curl_exec($ch),true);

	// 	$res = ($res['showapi_res_body']['pagebean']['contentlist']);

	// 	foreach ($res as $key => $val) {
			
	// 		//dump($val);
	// 		$data['Sid'] = 2;
	// 		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
	// 		$data['Title'] = $val['title'];
	// 		$data['Description'] = $val['desc'];
	// 		$img1= $val['imageurls']['0']['url'];
	// 		$img2= $val['imageurls']['1']['url'];
	// 		$img3= $val['imageurls']['2']['url'];
	// 		$img4= $val['imageurls']['3']['url'];
	// 		$img1 = "<img src = $img1>";
	// 		$img2 = "<img src = $img2>";
	// 		$img3 = "<img src = $img3>";
	// 		$img4 = "<img src = $img4>";
			
	// 		$data['Content'] = ($val['html']).$img1.$img2.$img3.$img4;
	// 		$data['Dtime'] = $val['pubDate'];
	// 		$data['source'] = $val['source'];

	// 		$newscopy = D('Newscopy');
	// 		//自动创建并验证数据
	// 		if ($newscopy->create($data)) {
	// 			$newscopy->add($data);
	// 			$this->success('采集中...',__APP__.'/News/head2',3);
	// 		}else {
	// 			$this->error('采集失败',3);
	// 		}
		
	// 	}
	// }

	//编辑头条
	// public function head2() {
	// 	$news = D('Newscopy');
	// 	import('ORG.Util.Page');				// 导入分页类
	// 	$where['Sid'] = 2;
	// 	$count = $news->where($where)->count();				//总记录数
	// 	// $Page = new Page($count,20);				//实例化分页类 传入总记录数和每页显示的记录数
	// 	// $Page->setConfig('header','条记录');
	// 	// $Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
	// 	// $Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
	// 	// $Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
	// 	// $Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
	// 	// $show = $Page->show();					//分页显示输出
	// 	$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
	// 	$page_row = 20;//每页显示条数
	// 	$p = new Page ($count,$page_row,8);
	// 	// $p->isJumpPage = true;
	// 	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
	// 	$show = $p->show();
	// 	$volist=$news->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();

	// 	//dump($volist);exit;
	// 	$this->assign('volist',$volist);
	// 	$this->assign('page',$show);

	// 	$this->co=$count;
	// 	$this->display();
	// }


	// public function house(){
	// 	$ch = curl_init();
	// 	$url = 'http://apis.baidu.com/showapi_open_bus/channel_news/search_news?channelId=5572a108b3cdc86cf39001d2&channelName=&title=&page=1&needContent=1&needHtml=1';
	// 	$header = array(
	// 		'apikey: bb44bf5d5787448710c2103e6d9afc9a',
	// 		);
 //    // 添加apikey到header
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //    // 执行HTTP请求
	// 	curl_setopt($ch , CURLOPT_URL , $url);
	// 	$res = json_decode(curl_exec($ch),true);

	// 	$res = ($res['showapi_res_body']['pagebean']['contentlist']);
	// 	//dump($res);exit;

	// 	foreach ($res as $key => $val) {
			
	// 		//dump($val);
	// 		$data['Sid'] = 3;
	// 		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
	// 		$data['Title'] = $val['title'];
	// 		$data['Description'] = $val['desc'];
	// 		$img1= $val['imageurls']['0']['url'];
	// 		$img2= $val['imageurls']['1']['url'];
	// 		$img3= $val['imageurls']['2']['url'];
	// 		$img4= $val['imageurls']['3']['url'];
	// 		$img1 = "<img src = $img1>";
	// 		$img2 = "<img src = $img2>";
	// 		$img3 = "<img src = $img3>";
	// 		$img4 = "<img src = $img4>";
			
	// 		$data['Content'] = ($val['html']).$img1.$img2.$img3.$img4;
	// 		$data['Dtime'] = $val['pubDate'];
	// 		$data['source'] = $val['source'];

	// 		$newscopy = D('Newscopy');
	// 		//自动创建并验证数据
	// 		if ($newscopy->create($data)) {
	// 			$newscopy->add($data);
	// 			$this->success('采集中...',__APP__.'/News/house2',3);
	// 		}else {
	// 			$this->error('采集失败',3);
	// 		}
		
			
	// 	}
		
	// }

	//编辑房子
	// public function house2() {
	// 	$news = D('Newscopy');
	// 	import('ORG.Util.Page');				// 导入分页类
	// 	$where['Sid'] = 3;
	// 	$count = $news->where($where)->count();				//总记录数
	// 	// $Page = new Page($count,20);				//实例化分页类 传入总记录数和每页显示的记录数
	// 	// $Page->setConfig('header','条记录');
	// 	// $Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
	// 	// $Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
	// 	// $Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
	// 	// $Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
	// 	// $show = $Page->show();					//分页显示输出
	// 	$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
	// 	$page_row = 20;//每页显示条数
	// 	$p = new Page ($count,$page_row,8);
	// 	// $p->isJumpPage = true;
	// 	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
	// 	$show = $p->show();
	// 	$volist=$news->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();

	// 	//dump($volist);exit;
	// 	$this->assign('volist',$volist);
	// 	$this->assign('page',$show);

	// 	$this->co=$count;
	// 	$this->display();
	// }

	//添加新闻
	// public function editcopy() {
	// 	$id = I('get.id','','htmlspecialchars');
	// 	$Sid = I('get.Sid','','htmlspecialchars');

	// 	if ($id=='' || !is_numeric($id)) {
	// 		parent::operating(__ACTION__,1,'参数错误');
	// 		$this->error('参数ID类型错误');
	// 	}
	// 	$id=intval($id);

	// 	$newscopy=M('newscopy');
	// 	if ($result=$newscopy->where("ID=$id")->find()) {
	// 		//分类数据
	// 		$newsclass = M('newsclass');
	// 		$clist = $newsclass->order('ID asc')->select();
	// 		$this->result=$result;
	// 		$this->clist=$clist;
	// 		$this->display();
	// 	}else {
	// 		parent::operating(__ACTION__,1,'数据不存在');
	// 		$this->error('没有找到相关数据');
	// 	}
	// }

	//确认添加
	// public function edit_docopy() {
	// 	if ($this->isPost()) {
	// 		$data = array();
	// 		$data['Sid'] = $_POST['Sid'];
	// 		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
	// 		$data['Title'] = ($_POST['Title']);
	// 		$data['Description'] = ($_POST['Description']);
	// 		$data['Content'] = $_POST['Content'];
	// 		$data['Sortid'] = $_POST['Sortid'];
	// 		$data['Dtime'] = date('Y-m-d H:i:s', time()); 
	// 		$data['source'] = ($_POST['source']);
			
	// 		//dump($data);exit;
	// 		$news = D('News');
	// 		$map['Title'] = $_POST['Title'];
	// 		$title = $news->where($map)->find();
		
	// 		if($title){
	// 			echo '此标题已经添加,请不要重复添加';
	// 			$this->success('请勿重复',__APP__.'/News/add',3);
	// 			exit;
	// 		}
	// 		import('ORG.Net.UploadFile');// 实例化上传类
	// 		$upload = new UploadFile();// 实例化上传类
	// 		$upload->maxSize = 3145728 ;// 设置附件上传大小
	// 		$upload->allowExts  = array('jpg','png', 'jpeg');// 设置附件上传类型
	// 		$upload->savePath = './Public/Upload/news/';// 设置附件上传目录
	// 		$upload->saveRule = uniqid(time().rand(0,10000));
		
	// 		if(!$upload->upload()) {// 上传错误提示错误信息
	// 		$this->error($upload->getErrorMsg());
	// 		}else{// 上传成功 获取上传文件信息
	// 		$info =  $upload->getUploadFileInfo();
	// 		}
	// 		//自动创建并验证数据
	// 		$data['img'] = $info[0]['savename']; 
	// 		$img = D('newscopy')->where('id ='.$_POST['ID'])->field('img')->find();
	// 			if($data['img'] == ''){
	// 				$data['img'] = $img['img'];
	// 			}

	// 		//dump($data);exit;
	// 		//自动创建并验证数据
	// 		if ($news->create($data)) {
	// 			$news->add($data);
	// 			$this->success('添加成功',__APP__.'/News/news',0);
	// 		}else {
	// 			$this->error($news->getError());
	// 		}
	// 	}else {
	// 		parent::operating(__ACTION__,1,'非法请求');
	// 		$this->error('非法请求');
	// 	}
	// }


	// public function finance(){
	// 	$ch = curl_init();
	// 	$url = 'http://apis.baidu.com/showapi_open_bus/channel_news/search_news?channelId=5572a109b3cdc86cf39001e1&channelName=&title=&page=1&needContent=1&needHtml=1';
	// 	$header = array(
	// 		'apikey: bb44bf5d5787448710c2103e6d9afc9a',
	// 		);
 //    // 添加apikey到header
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //    // 执行HTTP请求
	// 	curl_setopt($ch , CURLOPT_URL , $url);
	// 	$res = json_decode(curl_exec($ch),true);

	// 	$res = ($res['showapi_res_body']['pagebean']['contentlist']);

	// 	foreach ($res as $key => $val) {

	// 		$data['Sid'] = 4;
	// 		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
	// 		$data['Title'] = $val['title'];
	// 		$data['Description'] = $val['desc'];
	// 		$img1= $val['imageurls']['0']['url'];
	// 		$img2= $val['imageurls']['1']['url'];
	// 		$img3= $val['imageurls']['2']['url'];
	// 		$img4= $val['imageurls']['3']['url'];
	// 		$img1 = "<img src = $img1>";
	// 		$img2 = "<img src = $img2>";
	// 		$img3 = "<img src = $img3>";
	// 		$img4 = "<img src = $img4>";
			
	// 		$data['Content'] = ($val['html']).$img1.$img2.$img3.$img4;
	// 		$data['Dtime'] = $val['pubDate'];
	// 		$data['source'] = $val['source'];


	// 		$newscopy = D('Newscopy');
	// 		//自动创建并验证数据
	// 		if ($newscopy->create($data)) {
	// 			$newscopy->add($data);
	// 			$this->success('采集中...',__APP__.'/News/finance2',3);
	// 		}else {
	// 			$this->error('采集失败',3);
	// 		}
	// 	}
	// }

	//编辑财经
	// public function finance2() {
	// 	$news = D('Newscopy');
	// 	import('ORG.Util.Page');				// 导入分页类
	// 	$where['Sid'] = 4;
	// 	$count = $news->where($where)->count();				//总记录数
	// 	// $Page = new Page($count,20);				//实例化分页类 传入总记录数和每页显示的记录数
	// 	// $Page->setConfig('header','条记录');
	// 	// $Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
	// 	// $Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
	// 	// $Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
	// 	// $Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
	// 	// $show = $Page->show();					//分页显示输出
	// 	$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
	// 	$page_row = 20;//每页显示条数
	// 	$p = new Page ($count,$page_row,8);
	// 	// $p->isJumpPage = true;
	// 	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
	// 	$show = $p->show();
	// 	$volist=$news->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();

	// 	$this->assign('volist',$volist);
	// 	$this->assign('page',$show);

	// 	$this->co=$count;
	// 	$this->display();
	// }

	// public function life(){
	// 	$ch = curl_init();
	// 	$url = 'http://apis.baidu.com/showapi_open_bus/channel_news/search_news?channelId=5572a10bb3cdc86cf39001f8&channelName=&title=&page=1&needContent=1&needHtml=1';
	// 	$header = array(
	// 		'apikey: bb44bf5d5787448710c2103e6d9afc9a',
	// 		);
 //    // 添加apikey到header
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //    // 执行HTTP请求
	// 	curl_setopt($ch , CURLOPT_URL , $url);
	// 	$res = json_decode(curl_exec($ch),true);

	// 	$res = ($res['showapi_res_body']['pagebean']['contentlist']);

	// 	foreach ($res as $key => $val) {

	// 		$data['Sid'] = 5;
	// 		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
	// 		$data['Title'] = $val['title'];
	// 		$data['Description'] = $val['desc'];
	// 		$img1= $val['imageurls']['0']['url'];
	// 		$img2= $val['imageurls']['1']['url'];
	// 		$img3= $val['imageurls']['2']['url'];
	// 		$img4= $val['imageurls']['3']['url'];
	// 		$img1 = "<img src = $img1>";
	// 		$img2 = "<img src = $img2>";
	// 		$img3 = "<img src = $img3>";
	// 		$img4 = "<img src = $img4>";
			
	// 		$data['Content'] = ($val['html']).$img1.$img2.$img3.$img4;
	// 		$data['Dtime'] = $val['pubDate'];
	// 		$data['source'] = $val['source'];


	// 		$newscopy = D('Newscopy');
	// 		//自动创建并验证数据
	// 		if ($newscopy->create($data)) {
	// 			$newscopy->add($data);
	// 			$this->success('采集中...',__APP__.'/News/life2',3);
	// 		}else {
	// 			$this->error('采集失败',3);
	// 		}
	// 	}
	// }

	// public function life2() {
	// 	$news = D('Newscopy');
	// 	import('ORG.Util.Page');				// 导入分页类
	// 	$where['Sid'] = 5;
	// 	$count = $news->where($where)->count();				//总记录数
	// 	// $Page = new Page($count,20);				//实例化分页类 传入总记录数和每页显示的记录数
	// 	// $Page->setConfig('header','条记录');
	// 	// $Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
	// 	// $Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
	// 	// $Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
	// 	// $Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
	// 	// $show = $Page->show();					//分页显示输出
	// 	$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
	// 	$page_row = 20;//每页显示条数
	// 	$p = new Page ($count,$page_row,8);
	// 	// $p->isJumpPage = true;
	// 	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
	// 	$show = $p->show();
	// 	$volist=$news->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();

	// 	$this->assign('volist',$volist);
	// 	$this->assign('page',$show);

	// 	$this->co=$count;
	// 	$this->display();
	// }

	//新api新闻(没有正文内容无法使用)

	// Public function internew(){
	// 	$ch = curl_init();
	//     $url = 'http://apis.baidu.com/txapi/world/world?num=10&page=1';
	//     $header = array(
	//         'apikey: bb44bf5d5787448710c2103e6d9afc9a',
	//     );
	//     // 添加apikey到header
	//     curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//     // 执行HTTP请求
	//     curl_setopt($ch , CURLOPT_URL , $url);
	//     $res = curl_exec($ch);

	//     dump(json_decode($res,true));
	// }

	//文章详情
	public function newarticle() {
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


	//今日头条
	public function collect(){
		$newsclass = M('newsclass');
		import('ORG.QL.QueryList');				// 导入采集类
		$page = $_POST['page'];
		if (!$page) {
			echo '请填写URL地址';
			$this->success('采集失败',__APP__.'/News/add',0);
			exit();
		}
		$rules = array(
		    'title' => array('h1','text'),
		   	'image' => array('.article-content img','src'),
		   	'desc' => array('.article-content','text'),
		   	'time' => array('.time','text'),
		   	'source'=> array('.src','text'),
		   	'content'=> array('.article-content','html'),
		);
		$newsdet = QueryList::Query($page,$rules)->data;
		$imgurl = $newsdet['0']['image'];
		$data['Sid'] = 2;
		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
		$data['Title'] = $newsdet['0']['title'];
		$data['Description'] = trim(mbsubstr($newsdet['0']['desc'],0,150));
		$data['Content'] = $newsdet['0']['content'];
		$data['Dtime'] = $newsdet['0']['time'];
		$data['source'] = empty($newsdet['0']['source'])?'今日头条':$newsdet['0']['source'];
		$data['Sortid'] = '36';
		$data['img'] = $newsdet['0']['image'];
		//getImage($imgurl,'./Public/nesimg/','',1);
		if(!$imgurl){
			echo '缺少图片,此URL不可用!';
			$this->success('采集失败',__APP__.'/News/add',0);
			exit();
		}
		$clist = $newsclass->order('Dtime asc')->select();
		$this->assign('clist',$clist);
		$this->assign('result',$data);
		$this->display();
	}

	//房产采集
	public function addhouse(){
		$newsclass = M('newsclass');
		import('ORG.QL.QueryList');
		//phpQuery::$defaultCharset="utf-8";
		$page = $_POST['page'];
		// if(! preg_match('/<meta[^>]+charset=/i', $page)) {
		//     $charset = mb_check_encoding($page, 'utf-8') ? 'utf-8' : 'gbk';
		//     $page = sprintf('<meta http-equiv="Content-Type" content="text/html; charset=%s">%s', $charset, $page);
		// }
		$page = iconv('GBK','UTF-8',file_get_contents($page));
		//dump($page);exit;
		if(!$page){
			echo '请填写URL地址';
			$this->success('采集失败',__APP__.'/News/add',3);
			exit();
		}
		//dump($page);
		$rules = array(
		    'title' => array('h1','text'),
		   	'image' => array('#Cnt-Main-Article-QQ img','src'),
		   	'desc' => array('#Cnt-Main-Article-QQ','text'),
		   	'time' => array('.article-time','text'),
		   	'source'=> array('.color-a-1','text'),
		   	'content'=> array('#Cnt-Main-Article-QQ','html'),
		);
		//$data = QueryList::Query($html,$rule,'','UTF-8','GB2312')->data;
		$newsdet = QueryList::Query($page,$rules)->data;
		$imgurl = $newsdet['0']['image'];
		$data['Sid'] = 3;
		$data['Uid'] = $_SESSION['ThinkUser']['ID'];
		$data['Title'] = $newsdet['0']['title'];
		$data['Description'] = trim(mbsubstr($newsdet['0']['desc'],0,150));
		$data['Content'] = $newsdet['0']['content'];
		$data['Dtime'] = $newsdet['0']['time'];
		$data['source'] = empty($newsdet['0']['source'])?'今日头条':$newsdet['0']['source'];
		$data['Sortid'] = '36';
		$data['img'] = $newsdet['0']['image'];
		if(!$imgurl){
			echo '缺少图片,此URL不可用!';
			$this->success('采集失败',__APP__.'/News/add',3);
			exit();
		}
		$clist = $newsclass->order('Dtime asc')->select();
		$this->assign('clist',$clist);
		$this->assign('result',$data);
		$this->display('collect');
	}
	
	public function collect_do() {
		if ($this->isPost()) {
			$data = array();
			$data['Sid'] = $_POST['Sid'];
			$data['Title'] = ($_POST['Title']);
			$data['Sortid'] = $_POST['Sortid'];
			$data['Description'] = ($_POST['Description']);
			$data['Content'] = $_POST['Content'];
			$data['Dtime'] =  date('Y-m-d H:i:s', time());
			$data['img'] = $_POST['img'];
			$data['source'] = $_POST['source'];
			$news = D('News');
			$map['Title'] = $_POST['Title'];
			$title = $news->where($map)->find();
			if($title){
				echo '此标题已经添加,请不要重复添加';
				$this->success('请勿重复',__APP__.'/News/add',1);
				exit;
			}
			//自动创建并验证数据
			if ($news->create($data)) {
				$news->add();
				parent::operating(__ACTION__,0,'添加成功');
				$this->success('添加成功',__APP__.'/News/add',0);
			}else {
				parent::operating(__ACTION__,1,'添加失败：'.$news->getError());
				$this->error($news->getError());
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			$this->error('非法请求');
		}
	}
}
?>