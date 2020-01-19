<?php
//活动管理类
//活动筛选状态 pid=10
//截止时间   pid=11
class ActivityAction extends CommonAction {
	public function index() {

		// parent::userauth2(109);
		$this->getsearchparam();
		$data = $this->zuhewhere();

		//重组$data
		$data['activeStatus'] = isset($data['activeStatus'])?$data['activeStatus']:'';
		$data['applyTime'] = isset($data['applyTime'])?$data['applyTime']:'';
		$data['keyWord'] = $data['keyWord']?$data['keyWord']:'';

		$keyWord = $data['keyWord'];
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		//请求列表
		$html = send_post(C('URL').'/manager/active/selectActiveList',$data);
		//echo (C('URL').'/manager/active/selectActiveList');
		//dump($html['data']['activeList']);
		$count = $html['data']['count'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('shop',$html['data']['activeList']);
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();
	}
	/**
	 * @author zyl
	 * 活动列表专题模块
	 */
	public function bannerActive(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$html = httpRequest(C("URL").'/manager/activity/queryActivity',30,$data);
		// if(!$html[success]){
		// 	echo "<script>alert('接口请求失败');history.back();</script>";
		// }
		if($html['data']['count'] > 0){
			import('ORG.Util.Page');
			$p = new Page ($html['data']['count'],$pageSize,8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('page',$show)->assign('list',$html['data']['list']);
		}else{
			$empty="<tr><td style='text-align:center' colspan='6'>暂时没有数据</td></tr>";
			$this->assign('empty',$empty);
		}
		$this->display();
	}
	
	/**
	 * @author zyl
	 * 专题模块编辑
	 */
	public function activeEdit(){
		$id = I('get.id','');
		if($id){
			$data['sid'] = $id;
			$info = httpRequest(C("URL").'/manager/activity/getActivity',30,$data);
			if(!$info[success]){
				echo "<script>alert('接口请求失败');history.back();</script>";
			}
			$this->assign('info',$info['data']['activityproduct']);
		}
		$this->display();
	}
	public function getsearchparam(){
		// parent::userauth2(109);
		//获取活动状态
		$this->assign('status', D('status')->field('id, name, value')->order('id asc')->where('pid = 10')->select());
		//活动结束时间
		$this->assign('times', D('status')->field('id, name, value')->order('id asc')->where('pid = 11')->select());

		//线程管理
		$this->assign('thread',D('status')->field('id, name, value')->order('id asc')->where('pid = 16')->select());
		D('status')->field('id, name, value')->order('id asc')->where('pid = 16')->select();
		// echo D('status')->getLastSql();
		// dump($thread);
	}

	//组合请求数据
	public function zuhewhere(){
		// parent::userauth2(109);
		$data = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}

		//状态
		if(isset($_GET['s'])){
			$data['activeStatus'] = intval($_GET['s']);
			if($data['activeStatus'] !== $_SESSION['activeStatus']){
					unset($_REQUEST['page']);
					$data['start'] = 0;	

			}
			$_SESSION['activeStatus'] = intval($_GET['s']);	
		}

		//时间
		if(isset($_GET['t'])){
			$data['applyTime'] =  intval($_GET['t']);
			if($data['applyTime'] !== $_SESSION['applyTime']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['applyTime'] = intval($_GET['t']);
			
		}

	
		if(isset($_GET['k'])){
			//过滤KEY
			$key = trim($_GET['k']);
			if (!get_magic_quotes_gpc()){
				$key = addslashes($key);
			}
			$key = str_replace('_', '', $key);
			$key = str_replace('%', '', $key);
			$key = str_replace('-', '', $key);
			$key = str_replace('*', '', $key);
			$encode = mb_detect_encoding($key, array("ASCII","UTF-8","GB2312","GBK","BIG5"));//mb_detect_encoding检测不准,用这句可以准确检测
			if($encode == "UTF-8"){
				$this->assign('searchkey',$key);
			}else{
				$this->assign('searchkey',iconv('gbk','utf-8',$key));
				$key = iconv('gbk','utf-8',$key);
			}
			if($key != '')
				$data['keyWord'] = $key;
				if($data['keyWord'] !== $_SESSION['keyWord']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
				}
				$_SESSION['keyWord'] = $key;
		}
		return $data;
	}



	//修改信息
	public function edit(){
		parent::userauth2(109);

		$data['activeId'] = $_GET['sid'];
		$html = send_post(C('URL').'/manager/active/detailActive',$data);
		//dump($html);
		$pic = $html['data']['picList'];
		$piclist = array();
		foreach ($pic as $val) {
			if($val['isMainPic'] == 1){
				$this->assign('mainpic',$val);
			}else if($val['isMainPic'] == 0){
				array_push($piclist,$val);
				$this->assign('piclist',$piclist);
			}
		}
		$area = explode('-',$html['data']['active']['activeArea']);
		$this->assign('createTime',$html['data']['active']['createTime']);
		$this->assign('time',date('Y-m-d H:i:s',time()+300));
		$this->assign('shop',$html['data']['active']);
		$this->assign('activeShopRel',$html['data']['activeShopRel']['telephone']);
		$this->assign('activeId',$html['data']['active']['sid']);
		$this->assign('area',$area);
		$this->assign('url',C('URL'));
		
		$this->display();

	}

	public function editout(){
		parent::userauth2(109);
		$data['activeId'] = $_GET['sid'];
		$html = send_post(C('URL').'/manager/active/detailActive',$data);
		$pic = $html['data']['picList'];
		$piclist = array();
		foreach ($pic as $val) {
			if($val['isMainPic'] == 1){
				$this->assign('mainpic',$val);
			}else if($val['isMainPic'] == 0){
				array_push($piclist,$val);
				$this->assign('piclist',$piclist);
			}
		}
		$area = explode('-',$html['data']['active']['activeArea']);
		$this->assign('createTime',$html['data']['active']['createTime']);
		$this->assign('time',date('Y-m-d H:i:s',time()+300));
		$this->assign('shop',$html['data']['active']);

	

		$this->assign('activeShopRel',$html['data']['activeShopRel']['telephone']);
		$this->assign('activeId',$html['data']['active']['sid']);
		$this->assign('area',$area);
		$this->assign('url',C('URL'));
		
		$this->display();

	}

	//发布信息
	public function add(){
		parent::userauth2(109);
		$this->assign('url',C('URL'));
		//echo date('Y-m-d H:i:s',time());
		$this->assign('time',date('Y-m-d H:i:s',time()+300));
		$this->display();
	}

	//查看报名用户
	public function user(){
		parent::userauth2(109);
		$phone = $data['phone'] = trim(I('get.k'));
		$data['activitId'] = $_GET['activeId'];
		$data['pageNum'] = $_GET['page']?$_GET['page']:1;
		$data['pageSize'] = 15;
		//请求列表
		$html = send_post(C('URL').'/manager/active/queryActiveUserInfo',$data);
		//dump($html);
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 15;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();

		//dump($_GET);
		$this->assign('user',$html['data']['dataList']);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->assign('admin',$_SESSION['ThinkUser']['ID']);

		//获取时间
		$data2['activeId'] = $_GET['activeId'];
		$data2 = http_build_query($data2); 
		$opts2 = array( 
				'http' => array(
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data2
		         ) 
		); 
		$context2 = stream_context_create($opts2); 
		$html2 = file_get_contents(C('URL').'/manager/active/detailActive', false, $context2);
		$html2 = json_decode($html2,true);

		$this->assign('kk',$phone);
		$this->assign('activeShopRel',$_GET['activeShopRel']);
		$this->display();
	}

	public function userout(){
	parent::userauth2(109);
		$data['activitId'] = $_GET['activeId'];
		$data['pageNum'] = $_GET['page']?$_GET['page']:1;
		$data['pageSize'] = 15;
		//请求列表
		$html = send_post(C('URL').'/manager/active/queryActiveUserInfo',$data);
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 15;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('user',$html['data']['dataList']);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->assign('admin',$_SESSION['ThinkUser']['ID']);
		$this->display();
	}


	public function sources(){
		parent::userauth2(110);

		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));

		$data = $this->zuhewhere();

		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		$html = send_post(C('URL').'/manager/activeChannel/query',$data);
		$count = $html['data']['allCount'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();

		$this->assign('source',$html['data']['objectList']);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function addchannel(){
		parent::userauth2(110);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function editchannel(){
		parent::userauth2(110);
		$data['id'] = $_GET['sid'];
		$data = http_build_query($data); 
		$opts = array( 
				'http' => array( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/activeChannel/queryById', false, $context);
		$html = json_decode($html,true);
		//dump($html['data']);
		$this->assign('source',$html['data']);
		$this->assign('url',C('URL'));
		
		$this->display();

	}


	public function channel(){
		parent::userauth2(110);
		$data = $this->zuhewhere();
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		$t = time();
		$data['beginTime'] = $beginTime = empty($_POST['beginTime'])?(date("Y",$t).'-'.date("m",$t).'-'.date('d',$t).' 00:00:00'):($_POST['beginTime']);
		$data['endTime'] = $endTime =empty($_POST['endTime'])?(date("Y",$t).'-'.date("m",$t).'-'.date('d',$t).' 23:59:59'):($_POST['endTime']);
		$data['channelCode'] = $_GET['channelCode'];
		$html = send_post(C('URL').'/manager/activeChannelStatistics/query',$data);
		$count = $html['data']['allCount'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('channel',$html['data']['objectList']);
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		$this->display();
	}


	public function thread(){
		parent::userauth2(110);
		$this->getsearchparam();
		$data = '';
		$data = http_build_query($data); 
		$opts = array( 
					'http' => array( 
								'method' => 'POST', 
						        'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $data
			         ) 
			); 
		$context = stream_context_create($opts); 
		if(I('get.s') == 1){
			$html = file_get_contents(C('URL').'/manager/activeOnline/queryActiveBeginMap', false, $context);
		}else if(I('get.s') == 2){
			$html = file_get_contents(C('URL').'/manager/activeOnline/queryActiveEndApplyMap', false, $context);
		}else if(I('get.s') == 3){
			$html = file_get_contents(C('URL').'/manager/activeOnline/queryActiveEndMap', false, $context);
		}else if(I('get.s') == 4){
			$html = file_get_contents(C('URL').'/manager/activeOnline/queryActiveEndLoseTimeMap', false, $context);
		}else{
			$html = file_get_contents(C('URL').'/manager/activeOnline/queryActiveBeginMap', false, $context);
		}
		$html = json_decode($html,true);

		//dump($html);
		$this->assign('shop',$html['data']);
		$this->display();
	}
}
?>

