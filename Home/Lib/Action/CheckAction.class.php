<?php
//客户管理类
class CheckAction extends CommonAction {
	public function index() {
		// parent::userauth2(65);
		//dump($_GET['status']);
		// createsearchurl();
		// $where = '';
		// // $where[] .= $_GET['status1'];
		// // $where[] .= $_GET['status2'];
		// // $where[] .= $_GET['status3'];
		// $key = I('get.keyword','','htmlspecialchars');

		// $key = json_encode($key);
		// //dump($key);
		// // $where .= ' and (name like \'%'.$key.'%\' or phone like \'%'.$key.'%\')';
		// $where .= $this->where();
		// //dump($where);
		// $user = D('username')->select();
		// // dump($user);
		// $this->assign('user',$user);

		// import('ORG.Util.Page');				// 导入分页类
		// $count = 100;			//总记录数
		// $Page = new Page($count,15);				//实例化分页类 传入总记录数和每页显示的记录数
		// $Page->setConfig('header','条记录');
		// $Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
		// $Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
		// $Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
		// $Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
		// $show = $Page->show();
		// $this->assign('page',$show);
		
		// $curl = curl_init();
		// // 设置你需要抓取的URL
		// $url = "";
		// curl_setopt($curl, CURLOPT_URL, $url);
		// // 设置header
		// curl_setopt($curl, CURLOPT_HEADER, 1);
		// // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// // 运行cURL，请求网页
		// $data = curl_exec($curl);
		// //dump(json_encode($data));
		// // 关闭URL请求
		// curl_close($curl);
		// // 显示获得的数据
		// //dump($data);

		// $data = array ('keyWord'=>'','approveStatus'=>'-1','applyDatetime'=>'-1','start'=>'0','pageSize' => '10'); 
		// $data = http_build_query($data); 
		  
		// $opts = array ( 
		// 'http' => array ( 
		// 'method' => 'POST', 
		// 'header'=> "Content-type: application/x-www-form-urlencodedrn". 
		// "Content-Length: " . strlen($data) . "rn", 
		// 'content' => $data
		// ) 
		// ); 
		// dump($opts);exit;  
		// $context = stream_context_create($opts); 
		// $html = file_get_contents('http://192.168.1.27:8080/manager/user/getClientInfo', false, $context); 
		  


		$this->display();

	}
	
	/**
	 * @author zyl
	 * 共享房产
	 */
	public  function houseShare (){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($data["start"]>1){
			$data["start"] = ($data["start"]-1)*$data["pageSize"] ;
		}else{
		
			$data["start"] = 0 ;
		}
		//审核状态
		if(isset($_GET["checkStatus"])){
			$data["checkStatus"] = $_GET["checkStatus"];
		}else{
			$data["checkStatus"] = 0;
		}
		$this->assign("checkStatus",$_GET["checkStatus"]);
	
		$html = send_post(C('URL').'/manager/shareHouse/listAll',$data);
		//$html = send_post('http://192.168.1.28:8080/manager/seUserHouseManager/queryUserHouseManager',$data);
		$count = $html['data']['count'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
		// var_dump($html['data']['list']);exit;
		$this->display();
	} 
	
	/**
	 * @author zyl
	 * 共享房产信息详情
	 */
	public function houseShareInfo(){
		$data["sid"] = $_GET['sid'];
		
		$html = send_post(C('URL').'/manager/shareHouse/detail',$data);
		if($html["success"]){
			$this->assign("houseInfo",$html["data"]["shareHouse"]);
			 $this->assign("picList",$html["data"]["picList"]); 
			$this->assign("proveList",$html["data"]["proveList"]);
			
			$this->display();
		}else{
			echo "<script>alert('请求异常');history.back()</script>";
		}
	}
	//用户详情信息
	public function user(){
		$id = I('get.id','');
		if ($id=='' || !is_numeric($id)) {
			$this->content='参数ID类型错误，请关闭本窗口';
			exit($this->display('Public:err'));
		}
		$id=intval($id);
		//信息内容
		$this->display();
	}

	//时间美化函数
	protected function Beautifytime($dtime) {
		$dtime = strtotime($dtime);
		$betime = time()-$dtime;
		$timename='';
		switch($betime) {
			case ($betime < 60):
				$timename = floor($betime).'秒前';
				break;
			case ($betime < 3600 && $betime > 60):
				$timename = floor(($betime/60)).'分钟前';
				break;
			case ($betime < 86400 && $betime > 3600):
				$timename = floor(($betime/60/60)).'小时前';
				break;
			case ($betime < 2592000 && $betime > 86400):
				$timename = floor(($betime/60/60/30)).'天前';
				break;
			case ($betime < 31536000 && $betime > 2592000):
				$timename = floor(($betime/60/60/30/12)).'个月前';
				break;
			case ($betime < 3153600000 && $betime > 31536000):
				$timename = floor(($betime/60/60/30/12/100)).'年前';
				break;
		}
		return $timename;
	}



	//批量删除
	public function client_c_indel() {
		//验证用户权限
		parent::userauth(76);
		if ($this->isAjax()) {
			if (!$delid=explode(',',I('post.delid',''))) {
				R('Public/errjson',array('请选中后再删除'));
			}
			//将最后一个元素弹出栈
			array_pop($delid);
			$id=join(',',$delid);
			$client=M('client');
			$contact=M('contact');
			$co['Cid'] = array('in',$id);
			if ($client->delete("$id")) {
				$contact->where($co)->delete();
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

	//组合where
	private function where(){
		$where = '';
		//$where .= $_GET['status1'];
		//$where .= $_GET['status2'];
		if($_GET['status1']){
			$where .= ' and renovation = ' .$_GET['status1'];
		}

		if($_GET['status2']){
			$where .= ' and reno = ' .$_GET['status2'];
		}
		return $where;
	}
}
?>

