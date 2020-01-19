<?php
//终端管理
class EndAction extends CommonAction {
	public function index() {
		parent::userauth2(104);
		$data = $this->zuhewhere();
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 8;
		//请求列表
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/appClient/queryList', false, $context); 
		$html = json_decode($html,true);
		$end = $html['data']['objectList'];
		$count = $html['data']['allCount'];
		//dump($html);
		$this->assign('end',$end);
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();

	}

	//app类别列表
public function appcatelist(){
		//过滤平台navPlat
		if(isset($_GET['navPlat'])){
		
			$data['navPlat'] = $_GET['navPlat'];
			$this->assign('navPlat',$_GET['navPlat']);
		}
		if($_GET['navPlat'] <0){
			//是否推荐
			unset($data['navPlat']);
			//$this->assign('navPlat','-1');
		}
		//过滤归属bannerCategory
		if($_GET['bannerCategory']){
			//平台
			$data['bannerCategory'] = $_GET['bannerCategory'];
			$this->assign('bannerCategory',$_GET['bannerCategory']);
		}
		if($_GET['bannerCategory'] <0){
			//是否推荐
			unset($data['bannerCategory']);
			//$this->assign('bannerCategory','-1');
		}

		parent::userauth2(101);
		$data['start'] = $_GET['page']?$_GET['page']:0;
		$data['pageSize'] = 15;
		//var_dump($data);exit;
		$html = send_post(C('URL').'/manager/sysNavHome/findAllSysNavHome',$data);
		//$html = send_post('http://192.168.1.28:8080/manager/sysNavHome/findSysNavHome');
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$count = $html['data']["count"];
		$page_row = $data['pageSize']?$data['pageSize']:15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('catelist',$html['data']["list"]);
	//var_dump($html);exit;
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();
	}
	
	//app类别添加
	public function appcateedit(){
		if(isset($_GET['sid']) && $_GET['sid']){
			$data['sid'] = $_GET['sid'];
			$html = send_post(C('URL').'/manager/sysNavHome/getSysNavHome',$data);
			
		}
		
		$this->assign('cateinfo',$html['data']);
		$this->assign('url',C('URL'));
		$this->display();
		
	}
	
	//app类别删除
	public function appcatedel(){
		if(isset($_GET['sid']) && $_GET['sid']){
			$data['sid'] = $_GET['sid'];
			$html = send_post(C('URL').'/manager/sysNavHome/deleteSysNavHome',$data);
				
		}
		if($html['success']){
			$this->success('操作成功','appcatelist');
		}else{
			$this->error('操作失败'.$html['message']);
		}
	
	}
	//app类别保存
public function appcatesave(){
//var_dump($_POST);exit;
		$data['navName'] = $_POST['navName'];
		$data['imageKey'] = $_POST['imageKey'];
		$data['linkUrl'] = $_POST['linkUrl'];
		$data['navType'] = $_POST['navType'];
		$data['navNo'] = $_POST['navNo'];
		$data['bannerCategory'] = $_POST['bannerCategory'];
		$data['navPlat'] = (string)$_POST['navPlat'];
		
		//var_dump($data);exit;
		if($_POST['sid']){
			$data['sid'] = $_POST['sid'];
			//$html =  send_post('http://192.168.1.28:8080/manager/sysNavHome/updateSysNavHome',$data);
			$html = send_post(C('URL').'/manager/sysNavHome/updateSysNavHome',$data);
		}else{
			//$html =  send_post('http://192.168.1.28:8080/manager/sysNavHome/saveSysNavHome',$data);
			$html = send_post(C('URL').'/manager/sysNavHome/saveSysNavHome',$data);
		}
		
// 		var_dump($data);
// 		var_dump($html);exit;
		if($html['success']){
			$this->success('操作成功','appcatelist');
		}else{
			$this->error('操作失败'.$html['message']);
		}
		
	
	}
	
	
	public function add(){

		parent::userauth2(104);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function edit(){
		parent::userauth2(104);
		$this->assign('url',C('URL'));
		$this->display();
	}



	//组合请求数据
	private function zuhewhere(){
		$data = array();

		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*8;
		}
		return $data;
	}



//banner 管理
	public function banner() {
		parent::userauth2(105);
		$data = $this->zuhewhere();
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 8;

		//dump($data);
		//请求列表
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/banner/queryList', false, $context); 
		$html = json_decode($html,true);
		//dump($html);
		$end = $html['data']['objectList'];
		$count = $html['data']['allCount'];
	//dump($end);
		$this->assign('end',$end);
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign("qiniuUrl",C("QINIU_URL"));
		$this->assign('url',C('URL'));
		$this->display();

	}

	
	public function banneradd(){
		parent::userauth2(105);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function banneredit(){
		parent::userauth2(105);
		$data['sid'] = $_GET['sid'];
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/banner/query', false, $context); 
		$html = json_decode($html,true);
		$this->assign('banner',$html['data']);
		$this->assign('url',C('URL'));
		$this->assign('type',I('get.type'));
		$this->display();
	}

	//短信管理
	public function sms(){

		$this->assign('news',C('NEWSURL'));
		$this->assign('url',C('URL'));
		$this->display();
	}


	//app开机广告管理
	public function adv(){
		//状态值
		$this->assign('advstatus',D('status')->field('id, name, value')->order('id asc')->where('pid = 14')->select());
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 8;

		//dump($data);
		//请求列表
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/guide/queryGuideAdv', false, $context); 
		$html = json_decode($html,true);
		//dump($html);
		$count = $html['data']['allCount'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('adv',$html['data']['advData']);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function advadd(){
		$this->assign('url',C('URL'));
		$this->assign('time',date('Y-m-d H:i:s',time()));
		$this->display();
	}

	public function advedit(){
		$data['sid'] = I('get.sid');
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/guide/queryGuideAdvDetail', false, $context); 
		$html = json_decode($html,true);
		//dump($html);
		$this->assign('url',C('URL'));
		$this->assign('adv',$html['data']['advDetail']);
		$this->assign('type',I('get.type'));
		$this->display();
	}


	//PC-banner 管理
	public function pcbanner() {
		$data = $this->zuhewhere();
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 8;

		//请求列表
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/banner/queryPcList', false, $context); 
		$html = json_decode($html,true);
		
		$end = $html['data']['objectList'];
		$count = $html['data']['allCount'];
		$this->assign('end',$end);
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 8;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();

	}


	public function pcbanneradd(){
		$this->assign('url',C('URL'));
		$this->display();
	}


	public function pcbanneredit(){
		$data['sid'] = $_GET['sid'];
		$data = http_build_query($data); 
		$opts = array ( 
				'http' => array ( 
							'method' => 'POST', 
					        'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $data
		         ) 
		); 
		 
		$context = stream_context_create($opts); 
		$html = file_get_contents(C('URL').'/manager/banner/query', false, $context); 
		$html = json_decode($html,true);
		$this->assign('banner',$html['data']);
		$this->assign('url',C('URL'));
		$this->assign('type',I('get.type'));
		$this->display();
	}

}
?>

