<?php
//客户管理类
class ClientAction extends CommonAction {

	public function index() {
		parent::userauth2(89);
		//重组$data
		$data = $this->zuhewhere0();
		
		//请求列表
		$html = send_post(C('URL').'/manager/user/getClientInfo',$data);
		$count = $html['data']['count'];
		$user = $html['data']['userList'];
		// dump($user);exit();
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);

		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('user',$user);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->assign('admin',$_SESSION['ThinkUser']);
		$this->display();
	}

	public function zuhewhere0(){
		$data = array();
		$datas = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;

		//用户角色
		if(isset($_GET["userRole"]) && $_GET["userRole"] != -1){
			$data["userRole"] = $_GET["userRole"];
		}
			$datas["userRole"] = $_GET["userRole"];

		//会员状态
		if(isset($_GET["userApproveStatus"]) && $_GET["userApproveStatus"] != -1){
			$data["userApproveStatus"] = $_GET["userApproveStatus"];
		}
			$datas["userApproveStatus"] = $_GET["userApproveStatus"];

		//管家类型
		if(isset($_GET["isHouseManager"]) && $_GET["isHouseManager"] != -1){
			$data["isHouseManager"] = $_GET["isHouseManager"];
		}
			$datas["isHouseManager"] = $_GET["isHouseManager"];
		if(isset($_GET["isHousekeeperNetred"]) && $_GET["isHousekeeperNetred"] != -1){
			$data["isHousekeeperNetred"] = $_GET["isHousekeeperNetred"];
		}
			$datas["isHousekeeperNetred"] = $_GET["isHousekeeperNetred"];	

		//明星逸专员状态
		if(isset($_GET["userIsStar"]) && $_GET["userIsStar"] != -1){
			$data["userIsStar"] = $_GET["userIsStar"];
		}
			$datas["userIsStar"] = $_GET["userIsStar"];

		//视频上传权限
		if(isset($_GET["whiteListStatus"]) && $_GET["whiteListStatus"] != -1){
			$data["whiteListStatus"] = $_GET["whiteListStatus"];
		}
			$datas["whiteListStatus"] = $_GET["whiteListStatus"];

		//代理商状态
		if(isset($_GET["isAgent"]) && $_GET["isAgent"] != -1){
			$data["isAgent"] = $_GET["isAgent"];
		}
			$datas["isAgent"] = $_GET["isAgent"];

		//用户状态
		if(isset($_GET["registerType"]) && $_GET["registerType"] != -1){
			$data["registerType"] = $_GET["registerType"];
		}
			$datas["registerType"] = $_GET["registerType"];

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
			if($key != ''){
				$data['keyWord'] = $key;				
			}
		}
		$data['keyWord'] = $data['keyWord']?$data['keyWord']:'';
		$datas['keyWord'] = $data['keyWord']?$data['keyWord']:'';
		$this->assign('status', D('status')->field('id, name, value')->order('id asc')->where('pid = 1')->select());
		$this->assign('data',$datas);
		return $data;
	}
	
	//公益服务类型
	public function publicservice(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$html = httpRequest(C("URL").'/manager/webFundService/getServiceTypeList',30,$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['recordCount'],$pageSize,15);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['objectList']);	
		$this->display();
	}

	//公益服务 报名人员
	public function userlist(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$keyWord = I('get.keyWord','');
		$auditStatus = I('get.auditStatus','0');
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$data['keyWord'] = $keyWord;
		$data['auditStatus'] = $auditStatus;
		$html = httpRequest(C("URL").'/manager/fundServiceUserInfo/fundServiceUserList',30,$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['data']['count'],$pageSize,15);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['serviceUserlist']);
		$this->assign('keyWord',$keyWord);	
		$this->assign('auditStatus',$auditStatus);	
		$this->display();
	}

	//查看公益服务 报名人员详情
	public function userlistinfo(){
		$sid = I('get.sid','');
		if($sid){
			$data['sid'] = $sid;
			$info = httpRequest(C("URL").'/manager/fundServiceUserInfo/fundServiceUserDetail',30,$data);
			if(!$info['success']){
				echo "<script>alert('接口请求失败');history.back();</script>";
			}
			$this->assign('info',$info['data']);
			$this->display();
		}else{
			echo "<script>alert('非法请求！');history.back();</script>";
		}
	}

	//公益服务 报名人员
	public function getactivity(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$html = httpRequest(C("URL").'/manager/fundActivitie/queryAllFundActivityInfo',30,$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['recordCount'],$pageSize,15);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']);	
		$this->display();
	}

	//公益服务 已报名人员统计
	public function getactivityuser(){
		$activityId = I('get.sid','');
		if(!$activityId){
			echo "<script>alert('非法请求！');history.back();</script>";
		}
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$data['activityId'] = $activityId;
		$html = httpRequest(C("URL").'/manager/fundActivitie/queryAllEnrollInfoByActivityId',30,$data);
		// var_dump($html);exit;
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['data']['listSize'],$pageSize,15);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['objectList']);	
		$this->display();
	}

	//查看公益服务 招募活动详情
	public function getactivityinfo(){
		$sid = I('get.sid','');
		if($sid){
			$data['sid'] = $sid;
			$info = httpRequest(C("URL").'/manager/fundActivitie/queryFundActivityInfoDetail',30,$data);
			if(!$info['success']){
				echo "<script>alert('接口请求失败');history.back();</script>";
			}
			$this->assign('info',$info['data']['FundActivityInfo']);
			$this->assign('seviceTypeList',$info['data']['seviceTypeList']);
			$this->display();
		}else{
			echo "<script>alert('非法请求！');history.back();</script>";
		}
	}

	public function publicmanager(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$keyWord = I('get.keyWord','');
		$auditStatus = I('get.auditStatus','');
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$data['keyWord'] = $keyWord;
		$data['auditStatus'] = $auditStatus;
		$this->assign("keyWord",$keyWord)->assign("auditStatus",$auditStatus);
		$html = send_post(C('URL').'/manager/fundServiceUserInfo/fundServiceUserInfoList',$data);
		if(!$html['success']){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		// var_dump($html['data']['serviceUserlist']);exit;
		$count = $html['data']['count'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],15);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('pageSize',$data["pageSize"]);
		$this->assign('list',$html['data']['serviceUserlist']);
		$this->display();
	}

	/**
	 * @author zyl
	 * 导出
	 */
	
	public function recommendExport(){
		set_time_limit(0);
		$xlsCell  = array(
				array('userNickName','用户昵称'),
				array('userTelephone','用户手机号'),
				array('orderNum','交易数'),
				array('orderMoney','累计交易金额(元)'),
				array('orderFinishTime','交易时间'),
				array('createTime','注册时间'),
		);
		$sessionData = session('houseManagerListParam');
		$countAll = send_post(C('URL').'/manager/seUser/recommend/queryRelStatisticsInfo',$sessionData);
		//var_dump($expression)
		$countSum = intval(session('recommendInfoCount'));
		if($countSum < 1){
			echo json_encode(['code'=>false,'msg'=>'暂无数据可下载！','data'=>'']);
			exit();
		}
		$sessionData["pageSize"] = 10000;
		if($countSum%$sessionData["pageSize"] ==0){
			$pageNum = $countSum/$sessionData["pageSize"];//计算总数能分成几批（每批下载15000条）
		}else{
			$pageNum = ceil($countSum/$sessionData["pageSize"]);//计算总数能分成几批（每批下载15000条）
		}
		// var_dump($pageNum);exit();
		$zip = new ZipArchive;
		if ($zip->open(UPLOAD_FILE.date('Ymd') . '.zip',ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
	
			for($i=0;$i<$pageNum;$i++){
				$sessionData["start"] = $i*$sessionData["pageSize"];
				$html2 = send_post(C('URL').'/manager/seUser/recommend/queryRelStatisticsInfo',$sessionData);
				$xlsData = $html2['data']['list'];

				$fileName = 'houseManagerRecommend'.$i.'('.date('YmdHi').').xls';
				$this->recommendExportExcel($xlsCell,$xlsData,$fileName);
	
				if (file_exists(UPLOAD_FILE.$fileName)){
					$zip->addFile(UPLOAD_FILE.$fileName,$fileName);//添加压缩文件
				}
			}
				
		}
	
		$zip->close(); //关闭
		//远程下载压缩包
		$path = UPLOAD_FILE . date('Ymd') . '.zip';
		echo json_encode(['code'=>true,'msg'=>'','data'=>$path]);
	}
	
	/**
	 * @author
	 * @param unknown $expCellName
	 * @param unknown $expTableData
	 * @param unknown $fileName
	 */
	public function recommendExportExcel($expCellName,$expTableData,$fileName){
		//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		import("ORG.PHPExcel.PHPExcel");
		$objPHPExcel = new PHPExcel();
	
		//得到当前活动的表,注意下文教程中会经常用到$objActSheet
		$objActSheet = $objPHPExcel->getActiveSheet();
		//dump($objPHPExcel);exit;
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		//dump($cellName[$cellNum-1].'2');exit;
		$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '逸管家交易流水信息('.date('Y-m-d H:i:s').')');
		$objActSheet->getColumnDimension("A")->setAutoSize(true);
		$objActSheet->getColumnDimension("B")->setAutoSize(true);
		$objActSheet->getColumnDimension("C")->setAutoSize(true);
		$objActSheet->getColumnDimension("D")->setAutoSize(true);
		$objActSheet->getColumnDimension("G")->setAutoSize(true);
		$objActSheet->getColumnDimension("H")->setAutoSize(true);

		for($i=0;$i<$cellNum;$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
		}
		for($i=0;$i<$dataNum;$i++){
			for($j=0;$j<$cellNum;$j++){
				$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3),' '.$expTableData[$i][$expCellName[$j][0]]);
			}
	
		}
		ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$file_path = UPLOAD_FILE.$fileName;
		$objWriter->save($file_path);
	}
	
	/**@author zyl
	 * 分享接单统计
	 */
	public function sharecount(){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($data["start"]>1){
			$data["start"] = ($data["start"]-1)*$data["pageSize"] ;
		}else{
			$data["start"] = 0 ;
		}
		
		//类型
		$data["serviceType"] = (isset($_GET['serviceType']) && $_GET['serviceType']>0) ? intval($_GET['serviceType']):null;
		$data["keyWords"]= (isset($_POST["keyWords"]) && $_POST["keyWords"]) ? trim($_POST["keyWords"]):null;
		$data["shareServiceId"] = (isset($_GET['shareServiceId']) && $_GET['shareServiceId']) ? $_GET['shareServiceId']:null;
		$data["sellerLevel"] = (isset($_GET['sellerLevel']) &&  intval($_GET['sellerLevel'])>0) ? $_GET['sellerLevel']:null;
		$this->assign('shareServiceId',$_GET['shareServiceId']);
		$this->assign('serviceType',$_GET['serviceType']);
		$this->assign('sellerLevel',$_GET['sellerLevel']);
		$countType = $_GET["countType"]?$_GET["countType"]:1;

		//统计类型  1:接单统计  2：分享统计 3：接单人数统计 4：销售人员统计
		switch (intval($countType)){
			case 2:
				$html = send_post(C('URL').'/manager/shareLink/listAll',$data);
				break;
			case 1:
				$html = send_post(C('URL').'/manager/shareStatistics/getShareStatisticsList',$data);
				break;
			case 3:
				$html = send_post(C('URL').'/manager/shareStatistics/getShareReceiptInfo',$data);
				break;
			case 4:
				$html = send_post(C('URL').'/manager/shareUser/queryShareUserType',$data);
				break;
		}
		if($html == null){
			echo "<script>alert('请求异常');</script>";
		}
		$count = $html['data']['count'];
		//$count = 100000;
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
		$this->assign('keyWords',$data["keyWords"]);
		$this->assign('countType',$countType);
		//var_dump($html['data']['houseManagerList']);exit;
		$this->display();
	}
	/**
	 * @author zyl
	 * 客服管理列表
	 */
	public function servicelist(){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($data["start"]>1){
			$data["start"] = ($data["start"]-1)*$data["pageSize"] ;
		}else{
			$data["start"] = 0 ;
		}
		//手机号
		if($_POST["keywords"]){
			$data["condition"] = trim($_POST["keywords"]);
			
			$this->assign("keywords",$data["condition"]);
		}
		$html = httpRequest(C("URL").'/manager/customer/searchCustomer',30,$data);
		$count = $html['data']['count'];
		//$count = 100000;
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
	
		$this->display();
	}

//推荐查询
	public function recommendinfo(){
		//parent::userauth2(109);
		if($_POST){
			$data['seachKey'] = $_POST['name'];
			$html = send_post(C('URL').'/manager/seUser/recommend/getTopRecommendPHPRel',$data);
			//var_dump($html);exit;
			//$html = send_post('http://192.168.1.207:8085/h5server/seUser/recommend/getTopRecommendPHPRel',$data);

			if($html['success']){
				$this->assign('data',$html['data']);
				$this->assign('name',$data['seachKey']);
				$this->display();
			}else{
				$this->error($html['message']);
			}
		}else{
			$this->display();
		}
	}

	// 收费配置
	public function chargeSet(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['start'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$html = httpRequest(C("URL").'/manager/congfig/searchParamConfig',30,$data);
		// var_dump($html['data']['list']['0']);exit;
		if(!$html[success]){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		if($html['data']['count'] > 0){
			import('ORG.Util.Page');
			$p = new Page ($html['data']['count'],$pageSize,8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('page',$show)->assign('list',$html['data']['list']);
		}else{
			$empty="<tr><td style='text-align:center' colspan='7'>暂时没有数据</td></tr>";
			$this->assign('empty',$empty);
		}
		$this->display();
	}
	
	//添加或编辑收费配置
	public function chargeEdit(){
		$id = I('get.id','');
		if($id){
			$data['id'] = $id;
			$info = httpRequest(C("URL").'/manager/congfig/searchParamConfig',30,$data);
			// var_dump($info['data']['list']['0']);exit;
			if(!$info[success]){
				echo "<script>alert('接口请求失败');history.back();</script>";
			}
			$this->assign('info',$info['data']['list']['0']);
		}
		$this->display();
	}

	// 管家经理行业管理
	public function categoryManagement(){
		$start = I('get.page',1);
		$pageSize = I('get.pageSize',15);
		$data['begin'] = ((int)$start-1)*(int)$pageSize;
		$data['pageSize'] = $pageSize;
		$html = httpRequest(C("URL").'/manager/sysGoodsCategory/querySysGoodsCategory',30,$data);
		// var_dump($html);exit;
		if(!$html[success]){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
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

	//添加或编辑管家经理行业
	public function addAndEditCategory(){
		$id = I('get.sid','');
		if($id){
			$data['sid'] = $id;
			$info = httpRequest(C("URL").'/manager/sysGoodsCategory/querySysGoodsCategory',30,$data);
			// var_dump($info['data']);exit;
			if(!$info[success]){
				echo "<script>alert('接口请求失败');history.back();</script>";
			}
			$this->assign('info',$info['data']['result']);
		}
		$this->display();
	}

	// 合同管理
	public function contractmanager(){
		$data["keyWord"] = I('post.keyWord','','trim');
		if($data["keyWord"]){
			$where1['sign_user'] = $data["keyWord"];
			$where1['sign_user_tel'] = $data["keyWord"];
			$where1['_logic'] = 'or';
			$where['_complex'] = $where1;
			$where['status'] = array('lt',3);
			$this->assign("keyWord",$data["keyWord"]);
		}else{
			$where['status'] = array('lt',3);
			$this->assign("keyWord",'');
		}
		$data['start_date'] = I('post.start_date','');
		$data['end_date'] = I('post.end_date','');
		if($data['start_date'] && $data['end_date'] == '' && $data['start_date'] < date('Y-m-d H:i:s',time())){
			$where['create_time'] = array('BETWEEN',array($data['start_date'],date('Y-m-d H:i:s',time())));
			$this->assign("start_date",$data["start_date"])->assign("end_date",date('Y-m-d H:i:s',time()));
		}elseif ($data['end_date'] && $data['start_date'] == '' && $data['end_date'] > date('Y-m-d H:i:s',time())) {
			$where['create_time'] = array('BETWEEN', array(date('Y-m-d H:i:s',time()),$data['end_date']));
			$this->assign("start_date",date('Y-m-d H:i:s',time()))->assign("end_date",$data["end_date"]);
		}elseif ($data['start_date'] && $data['end_date']) {
			$where['create_time'] = array('BETWEEN', array($data["start_date"],$data['end_date']));
			$this->assign("start_date",$data["start_date"])->assign("end_date",$data["end_date"]);
		}else{
			$this->assign("start_date",'')->assign("end_date",'');
		}	
		$start = I('get.page',0);
		$pageSize = I('get.pageSize',15);	
		$count=M('sign_contract')->where($where)->count("id");
		if($count > 0){
			$list=M('sign_contract')->where($where)->page($start,$pageSize)->order('create_time desc,update_time desc')->select();
			import('ORG.Util.Page');
			$p = new Page ($count,$pageSize,8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('page',$show)->assign('list',$list);
		}else{
			$empty="<tr><td style='text-align:center' colspan='10'>暂时没有数据</td></tr>";
			$this->assign('empty',$empty);
		}
		$this->assign('signType',1);
		$this->display();
	}

	// 合同列表
	public function contractList(){
		$start = I('get.page',0);
		$type = I('get.type','');
		$pageSize = I('get.pageSize',15);
		$data['start'] = $start;
		$data['pageSize'] = $pageSize;
		$data['remarks'] = $type;
		$html = httpRequest(C("URL").'/manager/contractManagement/queryContractList',30,$data);
		if(!$html[success]){
			echo "<script>alert('接口请求失败');history.back();</script>";
		}
		import('ORG.Util.Page');
		$p = new Page ($html['data']['count'],$pageSize,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('list',$html['data']['list']);
		$this->assign('type',$type);
		$this->display();
	}

	//查看签署信息详情
	public function signInfo(){
		$id=I('get.id');
		$info=M('sign_contract')->where("id= $id")->find();
		$this->assign('info',$info);		
		$this->display();
	}

	//改变合同状态
	public function changeStatus(){
		$id=I('post.id');
		$status=I('post.status');
		if($status==1){
			$data = array('sign_time'=>date('Y-m-d H:i:s',time()),'status'=>$status);
			$info=M('sign_contract')->where("id= $id")->setField($data);
		}else{
			$info=M('sign_contract')->where("id= $id")->setField('status',$status);		
		}
		if($info){
			$this->ajaxReturn(["success" => true,"message" => "操作成功!"]);
		}else{
			$this->ajaxReturn(["success" => false,"message" => "操作失败!"]);			
		}		
	}

	//批量改变合同状态
	public function changeAllStatus(){
		$ids=I('post.ids');
		$status=I('post.status');
		$where['id']=array('in',$ids);
		$data['status']=$status;
		if($status==1){
			$data['sign_time']=date('Y-m-d H:i:s',time());
			$info=M('sign_contract')->where($where)->save($data);			
		}else{
			$info=M('sign_contract')->where($where)->save($data);		
		}
		if($info){
			$this->ajaxReturn(["success" => true,"message" => "操作成功!"]);
		}else{
			$this->ajaxReturn(["success" => false,"message" => "操作失败!"]);			
		}
	}

	/**
	 * 管家经理推荐统计
	 */
	public function housemanagercount(){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($data["start"]>1){
			$data["start"] = ($data["start"]-1)*$data["pageSize"] ;
		}else{
		
			$data["start"] = 0 ;
		}
		//手机号
		if($_POST["userTelephone"]){
			$data["userTelephone"] = trim($_POST["userTelephone"]);
			$this->assign("userTelephone",$data["userTelephone"]);
		}
		//昵称
		if($_POST["userNickName"]){
			$data["userNickName"] = trim($_POST["userNickName"]);
			$this->assign("userNickName",$data["userNickName"]);
		}
		//管家类型
		if(isset($_GET["managerType"]) && $_GET["managerType"] != -2){
			$data["managerType"] = $_GET["managerType"];
		}
		//var_dump($data);exit;
		$this->assign("managerType",$_GET["managerType"]);
		$html = send_post(C('URL').'/manager/seUser/recommend/queryRelStatistics',$data);
		$count = $html['data']['count'];
		//$count = 100000;
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('pageSize',$data["pageSize"]);
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
		//var_dump($html['data']['houseManagerList']);exit;
		$this->display();
	}

	//商家资质审核管理
	public function checkBusiness(){
		$data["start"] = I('get.page',0);
		$data["pageSize"] = I('get.pageSize',15);
		
		$data['starttime'] = I('post.startTime','');
		$data['endtime'] = I('post.endTime','');		
		$data["keyWord"] = I('post.keyWord','','trim');
		$this->assign("keyWord",$data["keyWord"])->assign("startTime",$data["starttime"])->assign("endTime",$data["endtime"]);

		$data["checkStatus"] = I('get.checkStatus','');
		$data["overdue"] = I('get.overdue',0);
		$this->assign("checkStatus",$data["checkStatus"])->assign("overdue",$data["overdue"]);

		$statusArr=["1"=>"待审核","2"=>"审核通过","3"=>"审核失败"];
		$typeArr=["0"=>"请选择要添加的证照","1"=>"营业执照","2"=>"食品流通许可证","3"=>"食品经营许可证","4"=>"食品生产许可证","5"=>"全国工业生产许可证","6"=>"医疗机械生产许可证","7"=>"医疗器械经营许可证","8"=>"二类医疗机械经营备案凭证","9"=>"出版物经营许可证"];

		$html = send_post(C('URL').'/manager/businessQualification/listAll',$data);
		if(!$html["success"]){
			echo "<script>alert(\"接口请求异常\");</script>";
			return;
		}
		$count = $html['data']['count'];
		if($count > 0){
			$datas=$html['data']['list'];		
			foreach ($datas as $k=> $va) {
				if($va['isForever']=='1'){
				  	$datas[$k]['vldTime']='永久';
				  	$datas[$k]['checkStatus']=$statusArr[$va['checkStatus']];
			  	}else{
			  		$datas[$k]['vldTime']=date('Y-m-d',strtotime($va['vldTime']));					  	
				  	if($datas[$k]['vldTime'] < date('Y-m-d')){
				  		$datas[$k]['checkStatus']='已过期';
					}else{
					  	$datas[$k]['checkStatus']=$statusArr[$va['checkStatus']];
					}
			  	}
			  $datas[$k]['licenceType']=$typeArr[$va['licenceType']];
			}
			import('ORG.Util.Page');
			$p = new Page ($count,$data["pageSize"],8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('page',$show)->assign('pageSize',$data["pageSize"])->assign('list',$datas);
			$this->assign('url',C('URL'));
		}else{
			$empty="<tr><td colspan='9'>暂时没有数据</td></tr>";
			$this->assign('empty',$empty);
		}
		$this->display();
	}

	//查看商家资质详情
	public function lookBusiness(){
		$data['sid']=I('get.sid');
		$statusArr=["1"=>"待审核","2"=>"审核通过","3"=>"审核失败"];
		$typeArr=["0"=>"请选择要添加的证照","1"=>"营业执照","2"=>"食品流通许可证","3"=>"食品经营许可证","4"=>"食品生产许可证","5"=>"全国工业生产许可证","6"=>"医疗机械生产许可证","7"=>"医疗器械经营许可证","8"=>"二类医疗机械经营备案凭证","9"=>"出版物经营许可证"];
		if($data['sid']){
			$res = send_post(C('URL').'/manager/businessQualification/detail',$data);
		}else{
			echo "<script>alert(\"非法请求异常\");</script>";
			return;
		}
		if($res['code'] == 2000){
			$datas=$res['data'];
			$datas['licenceType']=$typeArr[$datas['licenceType']];
			if($datas['isForever']=='1'){
			  	$datas['vldTime']='永久';
			  	$datas['checkStatus']=$statusArr[$datas['checkStatus']];
			}else{
			  	$datas['vldTime']=date('Y-m-d',strtotime($datas['vldTime']));
			  	if($datas['vldTime'] < date('Y-m-d')){
			  		$datas['checkStatus']='已过期';
				}else{
				  	$datas['checkStatus']=$statusArr[$datas['checkStatus']];
				}
			}
			$this->assign('info',$datas);
		}else{
			echo "<script>alert(\"接口请求异常\");</script>";
			return;
		}
		$this->display();
	}
	/**
	 * 管家推荐详情
	 */
	public function housemanagercountinfo(){
		$data["sid"] = $_GET['sid'];
		$data["start"] = $_GET['page']?$_GET['page']:1;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		//var_dump($_POST);exit;
		if($_POST && $_POST['orderNumber']){
			$this->assign('orderNumber',$_POST['orderNumber']);
			$data['orderNumber'] = $_POST['orderNumber'];
		}
		session('houseManagerListParam',$data);
		$html = send_post(C('URL').'/manager/seUser/recommend/queryRelStatisticsInfo',$data);
		//var_dump($html);exit;
		if($html["success"]){
			$count = ($_GET['relCount']?$_GET['relCount']:0);
			import('ORG.Util.Page');		// 导入分页类
			$p = new Page ($count,$data["pageSize"],8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			
			$this->assign('peopleSum',$peopleSum);
			$this->assign('page',$show);
			$this->assign('sid',$data["sid"]);
			$this->assign('pageSize',$data["pageSize"]);
			$this->assign("list",$html["data"]["list"]);
			$this->assign("getparam",$_GET);
			$this->assign("orderNumCount",$html["data"]["orderNumCount"]);
			$this->assign("orderPriceSum",$html["data"]["orderPriceSum"]);
			$this->assign("peopleCount",$html["data"]["peopleCount"]);
			session('recommendInfoCount',$_GET["relCount"]);
			$this->display();
		}else{                    
			$this->error("接口请求失败");
		}
	
	}
	/**
	 * 管家经理审核列表
	 */
	public function housemanager(){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($data["start"]>1){
			$data["start"] = ($data["start"]-1)*$data["pageSize"] ;
		}else{
		
			$data["start"] = 0 ;
		}
		
		//手机号或者用户名
		if($_POST["keyWord"]){
			$data["condition"] = trim($_POST["keyWord"]);
			$this->assign("keyWord",$data["condition"]);
		}
		//管家级别
		if(isset($_GET["managerType"]) && $_GET["managerType"] != -1){
			$data["managerType"] = $_GET["managerType"];
		}
		$this->assign("managerType",$_GET["managerType"]);
		//审核状态
		if(isset($_GET["checkStatus"]) && $_GET["checkStatus"] != -1){
			$data["checkStatus"] = $_GET["checkStatus"];
		}
		$this->assign("checkStatus",$_GET["checkStatus"]);
		//支付状态
		if(isset($_GET["payStatus"]) && $_GET["checkStatus"] != -1){
			$data["payStatus"] = $_GET["payStatus"];
		}
		$this->assign("payStatus",$_GET["payStatus"]);
		//推荐状态
		if(isset($_GET["recommendStatus"]) && $_GET["recommendStatus"] != -1){
			$data["recommendedStatus"] = $_GET["recommendStatus"];
		}
		$this->assign("recommendStatus",$_GET["recommendStatus"]);
		//推荐类别
		if(isset($_GET["recommendType"]) && $_GET["recommendType"] != -1 ){
			$data["recommendedType"] = $_GET["recommendType"];
		}
		$this->assign("recommendType",$_GET["recommendType"]);
		//管家经理来源
		if(isset($_GET["platform"]) && $_GET["platform"] != -1 ){
			$data["platform"] = $_GET["platform"];
		}
		$this->assign("platform",$_GET["platform"]);
		
	
		$html = send_post(C('URL').'/manager/seUserHouseManager/queryUserHouseManager',$data);
		//$html = send_post('http://192.168.1.28:8080/manager/seUserHouseManager/queryUserHouseManager',$data);
		// var_dump($html);exit;
		$count = $html['data']['count'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show)->assign('pageSize',$data["pageSize"]);
		$this->assign('list',$html['data']['houseManagerList']);
		$this->assign('url',C('URL'));
		// var_dump($html['data']['houseManagerList']);exit;
		$this->display();
	}

	// 未报名管家经理报名统计
	public function noStatistic(){
		$start = I('get.page',0);
		$pageSize = I('get.pageSize',15);
		$user_telephone = I('post.user_telephone','');
		if($user_telephone){
			$where['user_telephone']=$user_telephone;			
		}else{
			$where='';
		}
		$count=M('register_manager')->where($where)->count("id");
		if($count > 0){
			$list=M('register_manager')->where($where)->page($start,$pageSize)->order('create_time desc')->select();			
			import('ORG.Util.Page');
			$p = new Page ($count,$pageSize,8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('page',$show)->assign('pageSize',$pageSize);
			$this->assign('list',$list);
		}else{
			$empty="<tr><td style='text-align:center' colspan='6'>暂时没有数据</td></tr>";
			$this->assign('empty',$empty);
		}
		$this->assign('user_telephone',$user_telephone);
		$this->display();
	}

	//管家经理配置
	public function housemanagerreset(){

	if ($_POST){
		
		$data["sid"] = $_POST["sid"];
		isset($_POST["recommendedType"]) && $_POST["recommendedType"] ?($data["recommendedType"] = $_POST["recommendedType"]):($data["recommendedType"] = '0');
		isset($_POST["recommendedStatus"]) && $_POST["recommendedStatus"] ?($data["recommendedStatus"] = $_POST["recommendedStatus"]):($data["recommendedStatus"] = '0');

		isset($_POST["sort"]) && $_POST["sort"] ?($data["sort"] = $_POST["sort"]):($data["sort"] = 0);
		if($_POST["recommendedType"]){
	
			$data["backgroundImg"] = $_POST["smallbackgroundimg"];
			 $data["backgroundBigImg"] = $_POST["bigbackgroundimg"];
		}
//dump($data);exit;
	
		$html = send_post(C('URL').'/manager/seUserHouseManager/updateUserHouseManager',$data);

		if(!$html["success"]){
			$this->error($html["message"]);
			
		}else{
				
				header('location:'.$_POST['referer']);
			}

	}else{
		if($_GET["sid"]){
			//		echo $_GET["sid"];exit;
			$html = send_post(C('URL').'/manager/seUserHouseManager/queryUserHouseManagerBySid',['sid' => $_GET["sid"]]);
			$this->assign("info",$html["data"]["houseManagerList"][0]);
			$this->assign("checkinfo",$html["data"]["checkLogList"][0]);
			//var_dump($html["data"]["houseManagerList"][0]);exit;
			$this->assign('sid',$_GET["sid"]);
			$this->assign("url",C("url"));
			$this->assign("qiniuUrl",C("QINIU_URL"));
			$this->assign("referer",$_SERVER['HTTP_REFERER']);
			$this->display();
		}
		
	}
	

	
}
	//管家经理申请审核是否通过
	public function housemanagerpass(){
		if($_POST){
			$checkUser = $_SESSION['ThinkUser'];
			//var_dump($checkUser);exit;
			$data["checkUserName"] = $checkUser["Username"];
			$data["checkUserId"] = $checkUser["ID"];
			$data['checkStatus'] = $_POST['checkStatus'];
			$data['managerType'] = $_POST['type'];
			$data['checkInfo'] = $_POST['remark'];
			$data["sid"] = $_POST["sid"];
			$data["recommendedUserTel"] = $_POST["recommendedUserTel"];
			//$html = send_post('http://192.168.1.28:8080/manager/seUserHouseManager/updateSeUserHouseManagerCheck',$data);
			$html = send_post(C('URL').'/manager/seUserHouseManager/updateSeUserHouseManagerCheck',$data);


			if($html["success"]){
				$this->ajaxReturn(["success" => true,"message" => "操作成功","refererurl" => $_SERVER['HTTP_REFERER']]);
			}else{
				if($html == null){
					$this->ajaxReturn(["success" => false,"message" => "manager/seUserHouseManager/updateSeUserHouseManagerCheck接口访问失败"]);
				}
			}
	
		}
	}
	
	/**
	 * 查看管家经理资料
	 */
	public function housemanagerinfo(){
		$manager_type = $_GET['manager_type'];
		if($manager_type == 1){
			$data["userId"] = $_GET['sid'];
			$html = send_post(C('URL').'/manager/userHouseNetRed/queryNetredInfo',$data);
		}else{
			$data["sid"] = $_GET['sid'];
			$html = send_post(C('URL').'/manager/seUserHouseManager/queryUserHouseManagerBySid',$data);
		}
	
		if($html["success"] && $manager_type == 0){
			$this->assign("info",$html["data"]["houseManagerList"][0]);
			$this->assign("checkinfo",$html["data"]["checkLogList"][0]);	
			$this->display('housemanagerinfo');
		}elseif($html["success"] && $manager_type == 1){
			$this->assign("info",$html["data"]);
			$this->display('netredhousemanagerinfo');
		}else{
			$this->error("接口请求失败");
		}
	}


		/**
	 * 删除管家经理资料
	 */
	public function housemanagerdel(){
	
		$data["sid"] = $_GET['sid'];
		$html = send_post(C('URL').'/manager/seUserHouseManager/deleteManager',$data);
		//$html = send_post('http://192.168.1.28:8080/manager/seUserHouseManager/queryUserHouseManagerBySid',$data);
		if($html["success"]){
			echo "<script>alert('删除成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
		}else{
			echo"<script>alert('请求失败');history.go(-1);</script>";
		}

	}
//申请成为管家经理
	public function apply(){
		$data["start"] = $_GET['page']?$_GET['page']:0;
		$data["pageSize"] = $_GET['pageSize']?$_GET['pageSize']:15;
		if($_POST["keyWord"]){
			$data["keyWord"] = trim($_POST["keyWord"]);
			$this->assign("keyWord",$data["keyWord"]);
		}
		$data['startTime'] = isset($_POST['sBeginTime'])?$_POST['sBeginTime']:'';
		$data['endTime'] = isset($_POST['sEndTime'])?$_POST['sEndTime']:'';
		$this->assign("sBeginTime",$data["startTime"]);
		$this->assign("sEndTime",$data["endTime"]);
	
	
		if(isset($_GET["checkStatus"])){
			$data["checkStatus"] = $_GET["checkStatus"];
		}
		$this->assign("checkStatus",$data["checkStatus"]);
		//var_dump($data);exit;
		$html = send_post(C('URL').'/manager/userHouseManager/queryHouseManagerList',$data);
		//var_dump($html);exit;
		$count = $html['data']['count'];
		import('ORG.Util.Page');		// 导入分页类
		$p = new Page ($count,$data["pageSize"],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
		//var_dump($html['data']['list']);exit;
		$this->display();
	}
	//申请点击是否通过
	public function applypass(){
		if($_POST && $_POST["userId"]){
			$checkUser = $_SESSION['ThinkUser'];
			$data['checkUserId'] = $checkUser["ID"];
			$data['checkUserName'] = $checkUser["Username"];
			$data['checkStatus'] = $_POST['checkStatus'];
			$data['userId'] = $_POST["userId"];
			$data["remark"] = $_POST["remark"];
			$data["houseManagerId"] = $_POST["sid"];
	
			$html = send_post(C('URL').'/manager/houseManagerCheck/saveHouseManagerCheckLog',$data);
			if($html["success"]){
				if($_POST['checkStatus'] == 1){
					$userdata['is_house_manager'] = 1;
					$userdata["userId"] = $_POST["userId"];
					$result = send_post("http://crm.yiguanjiaclub.net/index.php?m=appserver&a=userTomanager",$userdata);
					if($result["success"]){
						$this->ajaxReturn(["success" => true,"message" => "操作成功","refererurl" => $_SERVER['HTTP_REFERER']]);
					}else{
						$this->ajaxReturn(["success" => false,"message" => "操作失败"]);
					}
				}
				if($_POST['checkStatus'] == 2){
					$this->ajaxReturn(["success" => true,"message" => "操作成功","refererurl" => $_SERVER['HTTP_REFERER']]);
				}
	
			}
		}
	}
	
	/**
	 * 查看管家经理资料
	 */
	public function applyinfo(){
	
		$data["sid"] = $_GET['sid'];
	
		$html = send_post(C('URL').'/manager/userHouseManager/detailHouseManager',$data);
		if($html["success"]){
			$this->assign("info",$html["data"]["houseManager"]);
	
			$this->display();
		}else{
			$this->error($html["message"]);
		}
	
	
	}
	

	//查询商户及代理商
	public function agent() {

		//清空区域
		if($_GET['clear'] == 1){
			cookie('state','');
			cookie('city','');
			cookie('area','');
		}
		$this->getsearchparam();
		$data = $this->zuhewhere2();

		// if($data['position_id'] && !$data['position_id'] == '-1'){
		// 	$where['position_id'] = $data['position_id'];
		// }
		// if($data['time'] == 1){
		// 	$order['create_time'] = 'DESC';
		// }else{
		// 	$order['create_time'] = 'ASC';
		// }

		// $address = $_COOKIE['state'].$_COOKIE['city'].$_COOKIE['area'];
		// //dump($address);
		// $where['company_address'] = array('like',"%$address%");
		// $where['_string'] = "(company_name like '%$data[keyWord]%')  OR ( name like '%$data[keyWord]%')";
		// $agent = M('agent');
		// import('ORG.Util.Page');		// 导入分页类
		// $pagenum = isset( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		// $page_row = 15;//每页显示条数
		// $count = $agent->where($where)->count();
		// $p = new Page ($count,$page_row,8);
		// $p->isJumpPage = true;
		// $p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		// $show = $p->show();
		// $agents = $agent->where($where)->order($order)->limit(($pagenum-1)*$page_row.','.$page_row)->having()->select();
	
		//重组$data
		
		$keyWord = $data['keyWord'];
		$data['companyName'] = $data['keyWord']?$data['keyWord']:'';
		$data['companyAddress'] = $_COOKIE['state'].$_COOKIE['city'].$_COOKIE['area'];
		$data['userRole'] = isset($data['userRole'])?$data['userRole']:'0';
		$data['isUse'] = isset($data['isUse'])?$data['isUse']:'-1';
		$data['sort'] = isset($data['sort'])?$data['sort']:'';
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		//dump($data);exit;
		//请求列表
		$html = send_post(C('URL').'/manager/community/queryCommunityUser',$data);
		//dump($html);

		$count = $html['data']['count'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		//dump($count);
		$p = new Page ($count,$page_row,8);

		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();

		//dump($html['data']);
		$this->assign('agents',$html['data']['communityList']);
		$this->assign('page',$show);
		$this->assign('state',$_COOKIE['state']);
		$this->assign('city',$_COOKIE['city']);
		$this->assign('area',$_COOKIE['area']);
		$this->assign('key',$keyWord);
		$this->display();
	}

	//用户详情信息
	public function user(){
		parent::userauth2(89);
		$data['sid'] =  $_GET['sid'];
		//请求列表
		$html = send_post(C('URL').'/manager/user/userDetail',$data);
		$user = $html['data']['user'];
		$this->assign('user',$user);
		$this->assign('url',C('URL'));
		$this->display();
	}

	public function nopa(){
		$nopa = $_GET['_URL_'];
		$this->assign('url',C('URL'));
		$this->assign('nopa',$nopa);
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

	public function getsearchparam(){
		//获取会员状态
		$this->assign('status', D('status')->field('id, name, value')->order('id asc')->where('pid = 1')->select());
		//申请时间
		$this->assign('times', D('status')->field('id, name, value')->order('id asc')->where('pid = 2')->select());
		//排序方式
		$this->assign('sort', D('status')->field('id, name, value')->order('id asc')->where('pid = 7')->select());
		//专员分类
		// $this->assign('user', D('status')->field('id, name, value')->order('id asc')->where('pid = 8')->select());
		// dump(D('status')->field('id, name, value')->order('id asc')->where('pid = 8')->select());

		//专员头像
		$this->assign('userimg', D('status')->field('id, name, value')->order('id asc')->where('pid = 9')->select());
		// dump(D('status')->field('id, name, value')->order('id asc')->where('pid = 9')->select());

		//商户分类
		$this->assign('agent',D('status')->field('id,name,value')->order('id asc')->where('pid = 13')->select());

		//启用停用
		$this->assign('enable',D('status')->field('id,name,value')->order('id asc')->where('pid = 14')->select());

		//升序降序
		$this->assign('asc',D('status')->field('id,name,value')->order('id asc')->where('pid = 15')->select());

		//合同状态
		$this->assign('contract',D('status')->field('id,name,value')->order('id asc')->where('pid = 19')->select());

		//日期
		$this->assign('datetime', D('status')->field('id, name, value')->order('id asc')->where('pid = 20')->select());

		//升序降序
		$this->assign('desc',D('status')->field('id,name,value')->order('id asc')->where('pid = 21')->select());
	}

	//组合请求数据逸专员
	public function zuhewhere(){
		$data = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}
		//会员状态
		if(isset($_GET['s'])){
			$data['approveStatus'] = intval($_GET['s']);
			if($data['approveStatus'] !== $_SESSION['approveStatus']){
					unset($_REQUEST['page']);
					$data['start'] = 0;	

			}
			$_SESSION['approveStatus'] = intval($_GET['s']);	
		}

		//申请时间
		if(isset($_GET['t'])){
			$data['applyDatetime'] =  intval($_GET['t']);
			if($data['applyDatetime'] !== $_SESSION['applyDatetime']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['applyDatetime'] = intval($_GET['t']);
			
		}

		//排序方式
		if(isset($_GET['p'])){
			$data['sort'] =  intval($_GET['p']);
			if($data['sort'] !== $_SESSION['sort']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['sort'] = intval($_GET['p']);
			
		}

		//是否有头像
		if(isset($_GET['g'])){
			$data['headFlag'] =  intval($_GET['g']);
			if($data['headFlag'] !== $_SESSION['headFlag']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['headFlag'] = intval($_GET['g']);
			
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
			if($key != ''){
				$data['keyWord'] = $key;
				if($data['keyWord'] !== $_SESSION['keyWord']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
				}
				$_SESSION['keyWord'] = $key;
			}
		}
		return $data;
	}


	//组合条件 商户及代理商
	public function zuhewhere2(){
		cookie('state',$_GET['state']);
		cookie('city',$_GET['city']);
		cookie('area',$_GET['area']);

		$data = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}

		//用户类型
		if(isset($_GET['n'])){
			$data['userRole'] = intval($_GET['n']);
		}

		//状态
		if(isset($_GET['e'])){
			$data['isUse'] =  intval($_GET['e']);
		}

		//排序方式
		if(isset($_GET['c'])){
			$data['sort'] =  intval($_GET['c']);	
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
		}
		return $data;
	}

	//获取店铺统计信息
	public function shopStatistics(){
		//请求列表
		$data['date'] = isset($_GET['date'])?$_GET['date']:date("Y-m-d",time());//日期s
		$data['endDate'] = isset($_GET['endDate'])?$_GET['endDate']:'';//日期s
		$data['start'] = $_GET['page']?$_GET['page']:1;
		$data['pageSize'] = $_GET['pageSize']?$_GET['pageSize']:20;
		$num =  send_post(C('URL').'/manager/CompanyCount/queryCount');
		$html = send_post(C('URL').'/manager/CompanyCount/findCompanyInfo',$data);
		 // var_dump($data);var_dump($html);exit;
		$list = $html['data']['pageList'];
 		$count = $html['data']['sumCount'];
 		$Pagesize=$data['pageSize'];
 		$count = $count?$count:0;
 		$pagekey = ownpage($count,$Pagesize);//调用分页方法第三个参数为显示页码数,默认为7

 		//前五天选项的url连接
 		$dayUrl =  [
 				'today' => U('/Client/shopStatistics',['date' => date('Y-m-d',time()) , 'pageSize'=>$data['pageSize'] ]), 
 				'beforeOne' => U('/Client/shopStatistics',['date' => date('Y-m-d',strtotime('-1 day')) , 'pageSize'=>$data['pageSize'] ]),
 				'beforeTwo' => U('/Client/shopStatistics',['date' => date('Y-m-d',strtotime('-2 day')) , 'pageSize'=>$data['pageSize'] ]),
 				'beforeThree' => U('/Client/shopStatistics',['date' => date('Y-m-d',strtotime('-3 day')) , 'pageSize'=>$data['pageSize'] ]),
 				'beforeFore' => U('/Client/shopStatistics',['date' => date('Y-m-d',strtotime('-4 day')) , 'pageSize'=>$data['pageSize'] ]),
 		]; 
 		//var_dump($list);exit;
 		$this->assign('daynum',$num['data']);
 		$this->assign('dayUrl',$dayUrl);
		$this->assign('list',$list);
		$this->assign('url',C('URL'));
		// $this->assign('date',$html['data']['date']);
		$this->assign('startDate',$data['date']);
		$this->assign('endDate',$data['endDate']);
		$this->assign('page',$pagekey);
		$this->display();
	}



	public function agreementlist(){
		//请求列表
		$Pagesize=$_GET['pageSize']?$_GET['pageSize']:20;
		if($_GET['page']){
			$limitcount = ($_GET['page']-1)*$Pagesize;
		}else{
			$limitcount = 0;
		}
		
		$list = M('agreement')->limit($limitcount,$Pagesize)->select();
		$count = M('agreement')->count(1);
		$count = $count?$count:0;
		$pagekey = ownpage($count,$Pagesize);//调用分页方法第三个参数为显示页码数,默认为7
		foreach ($list as $k=>$v){
			$list[$k]['add_time'] = date('Y-m-d H:i', $v['add_time']);
		}
		$this->assign('list',$list);
		$this->assign('page',$pagekey);
		$this->display();
		
	}
	//登录日志
	public function login(){

		$data['userName'] =  $_GET['userNickName'];
		//请求列表
		$html = send_post(C('URL').'/manager/loginLog/queryList',$data);
		$login = $html['data']['objectList'];
		$count = $html['data']['allCount'];
		//dump($user);
		import('ORG.Util.Page');				// 导入分页类
		$Page = new Page($count,2);				//实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('header','条记录');
		$Page->setConfig('prev','<img src="__IMAGE__/prev.gif" border="0" title="上一页" />');
		$Page->setConfig('next','<img src="__IMAGE__/next.gif" border="0" title="下一页" />');
		$Page->setConfig('first','<img src="__IMAGE__/first.gif" border="0" title="第一页" />');
		$Page->setConfig('last','<img src="__IMAGE__/last.gif" border="0" title="最后一页" />');
		$show = $Page->show();
		$this->assign('page',$show);
		$this->assign('login',$login);
		$this->assign('url',C('URL'));
		$this->display();
	}

	//修改用户信息
	public function edit(){

		$this->assign('url',C('URL'));
		$this->display();

	}


	//修改商户信息
	public function agentedit(){
		$sid = I('get.sid');
		$mysql = "mysql://eachonline_crm:Eachonline_crm@2016@192.168.0.5:33066/eachonline_crm";

		//var_dump(I('get.userRole'));exit;
		if(I('get.userRole') == 1){
			$sql = "SELECT count(1) FROM crm_user WHERE isagent = 3 AND general_agent = '$sid'";
			$area_agent = M("User","crm_",$mysql)->query($sql);
			$sql2 = "SELECT count(1) FROM crm_user as u, crm_role as r WHERE u.user_id = r.user_id AND r.position_id = 19 AND u.isagent = 0 AND u.general_agent = '$sid'";
			$company = M("User","crm_",$mysql)->query($sql2);
		}else if(I('get.userRole') == 4){
			$area_agent['0']['count(1)'] = 0;
			$sql = "SELECT count(1) FROM crm_user as u,crm_role as r WHERE u.user_id = r.user_id AND r.position_id = 19 AND u.isagent = 0 AND area_agent = '$sid'";
			$company = M("User","crm_",$mysql)->query($sql);
			//var_dump($company);exit;
		}else{
			$area_agent['0']['count(1)'] = 0;
			$company['0']['count(1)'] = 0;
		}
		$data['userId'] = $sid;
		$html = send_post(C('URL').'/manager/community/queryCommunityUserDetail',$data);

		$this->assign('type',I('get.type'));
		$this->assign('userid',$sid);
		$this->assign('cc',$html['data']);
		$this->assign('agent',$area_agent['0']['count(1)']);
		$this->assign('company',$company['0']['count(1)']);
		$this->assign('url',C('URL'));
		$this->display();
	}

	//查看用户详情
	public function agentview(){
		$agent = M('agent');
		$user = $agent->where("sid = '%s'",get('sid'))->find();
		$this->assign('user',$user);
		$this->assign('webimg',C('WEBIMG'));
		$this->display();
	}

	// //申请成为代理商
	// public function apply(){
	// 	$apply = M('apply');
	// 	import('ORG.Util.Page');		// 导入分页类
	// 	$pagenum = isset( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
	// 	$page_row = 15;//每页显示条数
	// 	$count = $apply->where("name like \"%$_POST[name]%\"")->count();
	// 	$p = new Page ($count,$page_row,8);
	// 	$p->isJumpPage = true;
	// 	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
	// 	$show = $p->show();
	// 	$user = $apply->where("name like \"%$_POST[name]%\"")->limit(($pagenum-1)*$page_row.','.$page_row)->order('time asc')->select();
	// 	$this->assign('page',$show);
	// 	$this->assign('user',$user);
	// 	$this->display();
	// }

	//区域代理合同审核
	public function local(){
		$this->getsearchparam();
		$data['checkStatus'] = isset($_GET['s']) ? $_GET['s'] : '';
		$data['datetime'] = isset($_GET['t']) ? $_GET['t']: '';
		$data['sort'] = isset($_GET['d']) ? $_GET['d']: '0';
		$data['keyWord'] = isset($_GET['k']) ? $_GET['k']:'';
		$data['pageSize'] = 15;
		$data['pageNum'] = isset($_GET['page'])?$_GET['page']:'1';
		$html = send_post(C('URL').'/manager/contract/findAgentContract',$data);
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		//dump($count);
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('local',$html['data']['dataList']);
		$this->assign('key',$data['key']);
		$this->assign('url',C('URL'));
		$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
		$this->display();
	}


	//区域代理商管理
	public function useragent(){
		$this->getsearchparam();
		$data['day'] = isset($_GET['t']) ? $_GET['t']: '';
		$data['sort'] = isset($_GET['d']) ? $_GET['d']: '0';
		$data['keyWord'] = isset($_GET['k']) ? $_GET['k']:'';
		$data['pageSize'] = 15;
		$data['pageNum'] = isset($_GET['page'])?$_GET['page']:'1';
		$html = send_post(C('URL').'/manager/newContract/queryAgentContract',$data);
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		//dump($count);
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('local',$html['data']['dataList']);
		$this->assign('key',$data['key']);
		$this->assign('url',C('URL'));
		$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
		$this->display();
	}
      

    //合同管理
    public function contract(){
    	$data['userSid'] = $_GET['userId'];
    	$html = send_post(C('URL').'/manager/newContract/getUserContractRecord',$data);
    	$this->assign('contract',$html['data']);
    	$this->assign('url',C('URL'));
		$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
    	$this->display();
    }
    
    
    //合同管理
    public function contractEdit(){
		if(isset($_GET['sid']) && $_GET['sid']){
			$data['sid'] = $_GET['sid'];
			$info = httpRequest(C("URL").'/manager/contractManagement/queryContractInfo',30,$data);
			if($info['success']){
				$this->assign('info',$info['data']['info']);
				// var_dump($info['data']['info']);exit;
				$this->assign('sid',$data['sid']);
			}
		}else{			
			$info['isEncryptView']=0;
			$info['isCharge']=1;
			$info['chargeType']=1;
			$info['remarks']=A;
			$info['isUse']=1;
			$this->assign('info',$info);
		}
    	$this->display();
    }
    

    //添加合同
    public function addcontract(){
		$data=send_post(C('URL').'/manager/newContract/getAllContract','');
		//print_r($data['data']);exit;
    	$this->assign('url',C('URL'));
		$this->assign('data',$data['data']);
    	$this->assign('userId',$_GET['sid']);
    	$this->display();
    }

    public function contractOne(){
    	$data['sid'] = $_GET['sid'];
    	//$data['contractNo'] = $_GET['contractNo'];
		//echo $data['sid'];exit;
    	$html = send_post(C('URL').'/manager/newContract/getContractRecord',$data);
		//var_dump($html['data']);exit;
    	$startDate = explode("-",date("Y-m-d",strtotime($html['data']['startDate'])));
    	$endDate = explode("-",date("Y-m-d",strtotime($html['data']['endDate'])));
    	$this->assign('endDate',$endDate);
		$this->assign('picture',$html['data']['pictureList']);
    	$this->assign('startDate',$startDate);
    	$this->assign('contract',$html['data']['contractRecord']);
    	$this->display();
    }

//    public function contractTwo(){
//    	$data['userId'] = $_GET['userId'];
//    	$data['contractNo'] = $_GET['contractNo'];
//    	$html = send_post(C('URL').'/manager/contract/getAgentContract',$data);
//    	$startDate = explode("-",date("Y-m-d",strtotime($html['data']['startDate'])));
//    	$endDate = explode("-",date("Y-m-d",strtotime($html['data']['endDate'])));
//    	$this->assign('endDate',$endDate);
//    	$this->assign('startDate',$startDate);
//    	$this->assign('contract',$html['data']);
//    	$this->display();
//    }
//
//    public function contractThree(){
//    	$data['userId'] = $_GET['userId'];
//    	$data['contractNo'] = $_GET['contractNo'];
//    	$html = send_post(C('URL').'/manager/contract/getAgentContract',$data);
//    	$startDate = explode("-",date("Y-m-d",strtotime($html['data']['startDate'])));
//    	$endDate = explode("-",date("Y-m-d",strtotime($html['data']['endDate'])));
//    	$this->assign('endDate',$endDate);
//    	$this->assign('startDate',$startDate);
//    	$this->assign('contract',$html['data']);
//    	$this->display();
//    }
//
//    public function contractFour(){
//    	$data['userId'] = $_GET['userId'];
//    	$data['contractNo'] = $_GET['contractNo'];
//    	$html = send_post(C('URL').'/manager/contract/getAgentContract',$data);
//    	$startDate = explode("-",date("Y-m-d",strtotime($html['data']['startDate'])));
//    	$endDate = explode("-",date("Y-m-d",strtotime($html['data']['endDate'])));
//    	$this->assign('endDate',$endDate);
//    	$this->assign('startDate',$startDate);
//    	$this->assign('contract',$html['data']);
//    	$this->display();
//    }

 /**
     * 审核社区实体店
     */
    public function checkchildshop(){
    	//		$this->getsearchparam();
    	//		$status=D('status')->field('id,name,value')->order('id asc')->where('pid = 19')->select();
    	//		foreach($status as $k=>$v){
    	//			if($v['value']==3){
    	//				unset($status[$k]);
    	//			}
    	//		}
    	//		$this->assign('status',$status);
    	//		$data['checkStatus'] = isset($_GET['s']) ? $_GET['s'] : '';
    	$data['pageSize'] = 15;
    	$data['pageNum'] = isset($_GET['page'])?$_GET['page']:'1';
    	$html = send_post(C('URL').'/manager/communityUser/findCommunityUserInfo',$data);
    	$count = $html['data']['totalRowNumber'];
    	import('ORG.Util.Page');				// 导入分页类
    	$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
    	$page_row = 15;//每页显示条数
    	//dump($count);
    	$p = new Page ($count,$page_row,8);
    	$p->isJumpPage = true;
    	$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
    	$show = $p->show();
    	$this->assign('page',$show);
    	$this->assign('local',$html['data']['dataList']);
    	$this->assign('key',$data['key']);
    	$this->assign('url',C('URL'));
    	$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
    	$this->assign('userId',$_SESSION['ThinkUser']['ID']);
    	$this->display();
    }
    /**
     * 社区实体店查看详情
     */
    
    public function childshopinfo(){
    	$data['userSid']=$_GET['userId'];
    	$html = send_post(C('URL').'/manager/communityUser/getCommunityUserInfo',$data);
    	//print_r($html['data']);exit;
    	$list=json_decode(json_encode($html['data'],true),true);
    	//print_r($list);exit;
    	$this->assign('data',$list);
    	$this->display();
    }
    
    /**
     * 查看社区实体店审核记录
     */
    public function checkshoprecord(){
    	$data['communityUserSid']=$_GET['sid'];
    	$html = send_post(C('URL').'/manager/communityUser/getCommunityUserCheck',$data);
    	//print_r($html);exit;
    	$this->assign('data',$html['data']);
    	$this->display();
    }
    
    /**
     * 实体店审核通过
     * checkRocord  0：不通过；1：待审核；2:审核通过
     */
    public function checkshoppass(){
    	$data['communityUserSid']=$_GET['sid'];
    	$data['checkUserId']=$_GET['userId'];
    	$data['checkName']=$_GET['name'];
    	//$data['details']=$_POST['content'];
    	$data['checkRocord']=$_GET['status'];
    	//print_r($data);exit;
    	$status=send_post(C('URL').'/manager/communityUser/addCheck',$data);
    	//var_dump($status);exit;
    	if ($status['success']) {

			return 	$this->success('提交成功');
			}else {

				return $this->error('提交失败');
			}
    }

    /**
	 * 审核入驻资料
	 */
    public function check(){
//		$this->getsearchparam();
//		$status=D('status')->field('id,name,value')->order('id asc')->where('pid = 19')->select();
//		foreach($status as $k=>$v){
//			if($v['value']==3){
//				unset($status[$k]);
//			}
//		}
//		$this->assign('status',$status);
//		$data['checkStatus'] = isset($_GET['s']) ? $_GET['s'] : '';
		$data['pageSize'] = 15;
		$data['pageNum'] = isset($_GET['page'])?$_GET['page']:'1';
		$html = send_post(C('URL').'/manager/company/queryCompanyBasicInfo',$data);
		//print_r($html);exit;
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		//dump($count);
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('local',$html['data']['dataList']);
		$this->assign('key',$data['key']);
		$this->assign('url',C('URL'));
		$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
		$this->assign('userId',$_SESSION['ThinkUser']['ID']);
		$this->display();
	}



	/**
	 * 查看入驻信息资料
	 */
	public function checklist(){
		$data['sid']=$_GET['sid'];
		$html = send_post(C('URL').'/manager/company/getCompanyBasicInfo',$data);
		//print_r($html['data']);exit;
		$list=json_decode(json_encode($html['data'],true),true);
		//print_r($list);exit;
		$this->assign('data',$list);
		$this->display();
	}


	/**
	 * 查看审核记录
	 */
	public function checkrecord(){
		$data['companyInfoSid']=$_GET['sid'];
		$html = send_post(C('URL').'/manager/company/getCheckCompanyInfoRecord',$data);
		//print_r($html);exit;
		$this->assign('data',$html['data']);
		$this->display();
	}


	/**
	 * 审核通过
	 */
	public function checkpass(){
		$data['sid']=$_POST['sid'];
		$data['userId']=$_POST['userId'];
		$data['name']=$_POST['name'];
		//$data['details']=$_POST['content'];
		$data['status']=2;
		//print_r($data);exit;
		$status=send_post(C('URL').'/manager/company/checkCompanyBasicInfo',$data);
		if ($status) {
			return $this->ajaxReturn();
		}
	}

	/**
	 * 审核不通过
	 */
	public function checknopass(){
		$this->assign('sid',$_GET['sid']);
		$this->assign('userId',$_GET['userId']);
		$this->assign('name',$_GET['name']);
		$this->display();
	}

	/**
	 * 审核不通过
	 */
	public function check_nopass(){
		if ($this->isPost()) {
			$data['sid']=$_POST['sid'];
			$data['userId']=$_POST['userId'];
			$data['name']=$_POST['name'];
			$data['details']=$_POST['content'];
			$data['status']=0;
			$status=send_post(C('URL').'/manager/company/checkCompanyBasicInfo',$data);
			if ($status) {
				parent::operating(__ACTION__,0,'提交成功');
				$this->success('提交成功','check',2);
			}else {
				parent::operating(__ACTION__,1,'提交失败');
				$this->error('提交失败');
			}
		}
	}



	/**
	 * 会员股票
	 */
	public function memberstock(){
		$data['pageSize'] = 15;
		$data['start']=$_GET['page']==0?0:($_GET['page']-1)*$data['pageSize'];
		$data['telephone']=$_GET['tel'];
		$html = send_post(C('URL').'/manager/seUserStockLog/selectUserStockLogList',$data);
		//dump($data);exit;
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($html['data']['count'],$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$this->assign('page',$p->show());
		//dump($html['data']['monthRebateList']);exit;
		$this->assign('list',$html['data']['list']);
		$this->assign('url',C('URL'));
		$this->display();
	}





	/**
	 * 合资子公司
	 */
	public function smallcompany(){
		//$this->getsearchparam();
//		$status=D('status')->field('id,name,value')->order('id asc')->where('pid = 19')->select();
//		foreach($status as $k=>$v){
//			if($v['value']==3){
//				unset($status[$k]);
//			}
//		}
		$status=Array ( '0' => Array ( 'id' => 80, 'name' => '审核失败', 'value' => '0') ,'1' => Array ( 'id' => 78, 'name' => '待审核' ,'value' => 1 ), '2' => Array ( 'id' => 79, 'name' => '审核通过', 'value' => 2 ));
		$this->assign('status',$status);
		$data['status'] = isset($_GET['s']) ? $_GET['s'] : '';
		$data['pageSize'] = 15;
		$data['pageNum'] = isset($_GET['page'])?$_GET['page']:'1';
		$html = send_post(C('URL').'/manager/jointVentureSubcompay/queryJoinVentureSubcompany',$data);
		//print_r($html);exit;
		$count = $html['data']['totalRowNumber'];
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		//dump($count);
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('local',$html['data']['dataList']);
		$this->assign('key',$data['key']);
		$this->assign('url',C('URL'));
		$this->assign('checkName',$_SESSION['ThinkUser']['Username']);
		$this->assign('userId',$_SESSION['ThinkUser']['ID']);
		$this->display();
	}



	/**
	 * 查看合资子公司资料
	 */
	public function smallcompanylist(){
		$data['sid']=$_GET['sid'];
		$html = send_post(C('URL').'/manager/jointVentureSubcompay/getJointVentureSubcompany',$data);
		//print_r($html['data']);exit;
		$list=json_decode(json_encode($html['data'],true),true);
		//print_r($list);exit;
		$this->assign('data',$list);
		$this->display();
	}


	/**
	 * 查看合资子公司审核记录
	 */
	public function smallcompanyrecord(){
		$data['companyInfoSid']=$_GET['sid'];
		$html = send_post(C('URL').'/manager/jointVentureSubcompay/getCheckJointVentureSubcompany',$data);
		//print_r($html);exit;
		$this->assign('data',$html['data']);
		$this->display();
	}


	/**
	 * 合资子公司审核通过
	 */
	public function smallcompanypass(){
		$data['sid']=$_POST['sid'];
		$data['userId']=$_POST['userId'];
		$data['name']=$_POST['name'];
		//$data['details']=$_POST['content'];
		$data['status']=2;
		//print_r($data);exit;
		$status=send_post(C('URL').'/manager/jointVentureSubcompay/checkJointVentureSubcompany',$data);
		if ($status) {
			return $this->ajaxReturn();
		}
	}

	/**
	 * 合资子公司审核不通过
	 */
	public function smallcompanynopass(){
		$this->assign('sid',$_GET['sid']);
		$this->assign('userId',$_GET['userId']);
		$this->assign('name',$_GET['name']);
		$this->display();
	}

	/**
	 * 合资子公司审核不通过
	 */
	public function smallcompany_nopass(){
		if ($this->isPost()) {
			$data['sid']=$_POST['sid'];
			$data['userId']=$_POST['userId'];
			$data['name']=$_POST['name'];
			$data['details']=$_POST['content'];
			$data['status']=0;
			$status=send_post(C('URL').'/manager/jointVentureSubcompay/checkJointVentureSubcompany',$data);
			if ($status) {
				parent::operating(__ACTION__,0,'提交成功');
				$this->success('提交成功','smallcompany',2);
			}else {
				parent::operating(__ACTION__,1,'提交失败');
				$this->error('提交失败');
			}
		}
	}
}
?>

