<?php
//登录日志
class LoginAction extends CommonAction {
	public function index() {

		$data = $this->zuhewhere();	
		$data['userName'] = $data['userName']?$data['userName']:'';
		$keyWord = $data['userName'];
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		//dump($data);
		$html = send_post(C('URL').'/manager/loginLog/queryList',$data);
		$login = $html['data']['objectList'];
		$count = $html['data']['allCount'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('login',$login);
		$this->assign('url',C('URL'));
		$this->display();

	}

	
	public function add(){

		$this->assign('url',C('URL'));
		$this->display();
	}

	public function edit(){

		$this->assign('url',C('URL'));
		$this->display();
	}



	//组合请求数据
	private function zuhewhere(){
		$data = array();

		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*10;
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
				$data['userName'] = $key;
			if($data['userName'] !== $_SESSION['userName']){
				$data['start'] = 0;
				unset($_REQUEST['page']);
			}
			$_SESSION['userName'] = $key;
		}
		return $data;
	}
}
?>

