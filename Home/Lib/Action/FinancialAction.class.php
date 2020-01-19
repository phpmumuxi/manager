<?php
//客户管理类
class FinancialAction extends CommonAction {
	public function index() {
		parent::userauth2(93);
		$this->getsearchparam();
		$data = $this->zuhewhere();
		//重组$data
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		$data['tradeType'] = isset($data['tradeType'])?$data['tradeType']:-1;
		$data['tradeStatus'] = isset($data['tradeStatus'])?$data['tradeStatus']:-1;
		$data['payType'] = isset($data['payType'])?$data['payType']:-1;
		$tel = $data['tel'] = $data['tel']?$data['tel']:'';
		$data['userName'] = $data['userName']?$data['userName']:'';
		$relname = $data['userName'];
		$data['orderNo'] = $data['orderNo']?$data['orderNo']:'';
		$orderNo = $data['orderNo'];
		if($data['tradeComment'] == '-1')
			unset($data['tradeComment']);
		$html = send_post(C('URL').'/manager/wtRecord/getWtRecordList',$data);
		if(!$html){
			echo "<script>alert('接口请求异常');history.back();</script>";
		}
		$count = $html['data']['allCount'];
		$pay = $html['data']['objectList'];
		$this->assign('pay',$pay);
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('url',C('URL'));
		$this->assign('tel',$tel);
		$this->assign('userName',$relname);
		$this->assign('orderNo',$orderNo);
		$this->assign('page',$show);
		$this->assign('userId',$_SESSION['ThinkUser']['ID']);
		$this->assign('tradeTypeData',$data['tradeType']);
		$this->display();
	}

	//订单列表
	public function orderlist(){
		parent::userauth2(107);
		$this->getsearchparam();
		$data['phone'] = isset($_GET['tel'])?$_GET['tel']:'';//手机号
        $data['orderNo'] = isset($_GET['orderno'])?$_GET['orderno']:'';//订单号
        $data['beginTimeStr'] = isset($_GET['sBeginTime'])?$_GET['sBeginTime']:'';
        $data['endTimeStr'] = isset($_GET['sEndTime'])?$_GET['sEndTime']:'';
        $data['pageSize'] = $_GET['pageSize'] ? $_GET['pageSize']:15;
        $data['pageNum'] =  isset($_GET['page'])?$_GET['page']:1;
        $this->assign('pageSize',$data['pageSize']);
        $this->assign('sBeginTime',$data['beginTimeStr']);
        $this->assign('sEndTime', $data['endTimeStr']);
        $this->assign('tel',$data['phone']);
        $this->assign('orderno',$data['orderNo']);

        $html = send_post(C('URL').'/manager/order/queryOrderRecords',$data);
        //echo (C('URL').'/manager/tradeRecord/findTradeRecord');
        //dump($html['data']);
        if($html['code'] == 2000){
			//dump($html['data']['dataList']);
			$this->assign('reco',$html['data']['dataList']);
		}
//var_dump($html['data']['dataList']);exit;
        import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = $data['pageSize'];//每页显示条数
		$p = new Page ($html['data']['totalRowNumber'],$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$this->assign('page',$p->show());
		$this->assign('url',C('URL'));
		$this->display();
	}
	//订单详情
	public function orderinfo(){
		$data["buyerId"] = $_GET["userId"];
		$data["orderNo"] = $_GET["orderNo"];
		if(!$_GET["userId"] || !$_GET["orderNo"]){
			$this->error("缺少参数");
		}
		$html = send_post(C('URL').'/manager/order/getOrderRecords',$data);
		$freight = $html["data"]["orderInfoUser"]["freight"]?$html["data"]["orderInfoUser"]["freight"]:0;//运费
		$html["data"]["orderInfoUser"]["totalPrice"] = $html["data"]["orderInfoUser"]["payPrice"] + $freight;//邮费加商品价格
		//var_dump($html);exit;
		$this->assign("freight",$freight);//总订单信息
		$this->assign("info",$html["data"]["orderInfoUser"]);
		$this->assign("orderInfoAfter",$html["data"]["orderInfoAfter"]);//售后子定单信息
		$this->assign("orderInfoRecords",$html["data"]["orderInfoRecords"]);//子订单信息
		$this->assign("topone",$html["data"]["topone"]);
		$this->assign("toptwo",$html["data"]["toptwo"]);
		$this->display();
	}

	public function getsearchparam(){
		// 获取交易
		$this->assign('tradeType', D('status')->field('id, name, value')->order('id asc')->where('pid = 16')->select());
		// tradeStatus
		$this->assign('tradeStatus', D('status')->field('id, name, value')->order('id asc')->where('pid = 17')->select());
		//payType
		$this->assign('payType', D('status')->field('id, name, value')->order('id asc')->where('pid = 18')->select());
		//申请时间
		$this->assign('times', D('status')->field('id, name, value')->order('id asc')->where('pid = 2')->select());
		//状态
		$this->assign('status',D('status')->field('id,name,value')->order('id asc')->where('pid = 12')->select());

        //添加平台余额明细开始
//        $tt=D('status')->field('id,name,value')->order('id asc')->where('pid = 16')->select();
//        $aa=array();
//        $aa[6]['id']=67;
//        $aa[6]['name']='推荐返利';
//        $aa[6]['value']=6;
//
//        $aa[7]['id']=68;
//        $aa[7]['name']='月返利';
//        $aa[7]['value']=7;
//
//        $aa[8]['id']=69;
//        $aa[8]['name']='店铺等级';
//        $aa[8]['value']=8;
//
//        $plat=array_merge($tt,$aa);
//        $this->assign('platrecjtype',$plat);
        //print_r($plat);exit;
        //添加平台余额明细结束

		//对账交易类型
		$this->assign('recjtype',D('status')->field('id,name,value')->order('id asc')->where('pid = 16')->select());
		//对账交易状态
		$this->assign('recstatus',D('status')->field('id,name,value')->order('id asc')->where('pid = 17')->select());
		//对账支付类型
		$this->assign('recztype',D('status')->field('id,name,value')->order('id asc')->where('pid = 18')->select());
	}

	//组合请求数据
	private function zuhewhere(){
		$data = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}

		//交易类型
		if(isset($_GET['s'])){
			$data['tradeType'] = intval($_GET['s']);
			if($data['tradeType'] !== $_SESSION['tradeType']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['tradeType'] = intval($_GET['s']);
		}

		//交易状态
		if(isset($_GET['t'])){
			$data['tradeStatus'] = intval($_GET['t']);
			if($data['tradeStatus'] !== $_SESSION['tradeStatus']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['tradeStatus'] = intval($_GET['t']);	
		}
	//是否返利
		if(isset($_GET['f'])){
			$data['tradeComment'] = $_GET['f'];
			if($data['tradeComment'] !== $_SESSION['tradeComment']){
				$data['start'] = 0;
				unset($_REQUEST['page']);
			}
			$_SESSION['tradeComment'] = intval($_GET['f']);
		}
		//支付类型
		if(isset($_GET['p'])){
			$data['payType'] = intval($_GET['p']);
			if($data['payType'] !== $_SESSION['payType']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['payType'] = intval($_GET['p']);	
		}

		//tel
		if(isset($_GET['tel']) && !empty($_GET['tel'])){
			//过滤KEY
			$key = trim($_GET['tel']);
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
				$data['tel'] = $key;
				if($data['tel'] !== $_SESSION['tel']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
				}
				$_SESSION['tel'] = $key;
		}
		//userName
		if(isset($_GET['userName']) && !empty($_GET['userName'])){
			//过滤KEY
			$key = trim($_GET['userName']);
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

		//orderNo
		if(isset($_GET['orderNo']) && !empty($_GET['orderNo'])){
			//过滤KEY
			$key = trim($_GET['orderNo']);
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
				$data['orderNo'] = $key;
				if($data['orderNo'] !== $_SESSION['orderNo']){
				$data['start'] = 0;
				unset($_REQUEST['page']);
				}
				$_SESSION['orderNo'] = $key;
		}
		return $data;
	}


	private function zuhewhere2(){
		$data = array();
		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}

		//交易状态
		if(isset($_GET['t'])){
			$data['tradeStatus'] = intval($_GET['t']);
			if($data['tradeStatus'] !== $_SESSION['tradeStatus']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['tradeStatus'] = intval($_GET['t']);	
		}

		//支付类型
		if(isset($_GET['p'])){
			$data['payType'] = intval($_GET['p']);
			if($data['payType'] !== $_SESSION['payType']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['payType'] = intval($_GET['p']);	
		}


		//userName
		if(isset($_GET['userName'])){
			//过滤KEY
			$key = trim($_GET['userName']);
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

	//提现短信通知
	public function businessMessage() {
		$res = send_post(C('URL').'/manager/smsNotifyTemplate/get');		
		if($res['code'] == 2000){
			if($res['data']['sid']){
				$res['data']['smsUsers']=json_decode($res['data']['smsUsers'],true);
				$this->assign('data', $res['data']);
			}
		}else{
			echo "<script>alert(\"接口请求异常\");</script>";
			return;
		}
		$this->display();
	}

	public function business() {
		parent::userauth2(106);
		$this->getsearchparam();
		$data = $this->zuhewhere2();
		//重组$data
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		$data['tradeType'] = 0;
		$data['tradeStatus'] = isset($data['tradeStatus'])?$data['tradeStatus']:-1;
		$data['payType'] = isset($data['payType'])?$data['payType']:-1;
		$data['userName'] = $data['userName']?$data['userName']:'';
		$relname = $data['userName'];
		//var_dump($data);exit;
		// $html = send_post(C('URL').'/manager/wtRecord/getWtRecordList',$data);
		$html = send_post(C('URL').'/manager/wtRecord/getMerchantCashList',$data);//替换接口
		// var_dump($html);
		// var_dump($html);exit;
		if($html == null)
			echo "<script>alert('/manager/wtRecord/getWtRecordList接口访问失败返回值null')</script>";
		//var_dump($html);exit;
		$count = $html['data']['allCount'];
		$pay = $html['data']['objectList'];
		$this->assign('pay',$pay);
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('url',C('URL'));
		$this->assign('userName',$relname);
		$this->assign('page',$show);
		$this->display();
	}


	public function voucher(){
		$data['mainTradeNo'] = $_GET['mainTradeNo'];
		$html = send_post(C('URL').'/manager/wtRecord/checkUpCertificate',$data);
		//dump($html);
		if($html['code'] == 2000){
			//dump($html['data']['certificateUrlList']);
			$this->assign('picture',$html['data']['certificateUrlList']);
		}else{
			echo "<script>alert('异常!')</script>";
			$this->error('',U('Financial/index'),1);
		}

		$this->display();
		
	}

	public function reco(){
		parent::userauth2(107);
		$this->getsearchparam();
        $data['tradeType']   = isset($_GET['s'])?$_GET['s']:''; //交易类型
        $data['tradeStatus']   = isset($_GET['t'])?$_GET['t']:''; //交易状态
        $data['payType']   = isset($_GET['p'])?$_GET['p']:''; //支付类型
		$data['tel'] = isset($_GET['tel'])?$_GET['tel']:'';//手机号
		$data['userCompany'] = isset($_GET['userCompany'])?$_GET['userCompany']:'';//手机号
        $data['userName'] = isset($_GET['username'])?$_GET['username']:'';//用户昵称
        $data['mainTradeNo'] = isset($_GET['payno'])?$_GET['payno']:'';//交易号
        $data['orderNo'] = isset($_GET['orderno'])?$_GET['orderno']:'';//订单号
        $data['tradeTimeStart'] = isset($_GET['sBeginTime'])?$_GET['sBeginTime']:'';
        $data['tradeTimeEnd'] = isset($_GET['sEndTime'])?$_GET['sEndTime']:'';
        $data['tradeFinishTimeStart'] = isset($_GET['EBeginTime'])?$_GET['EBeginTime']:'';
        $data['tradeFinishTimeEnd'] = isset($_GET['eEndTime'])?$_GET['eEndTime']:'';
        $data['pageSize'] = 15;
        $data['pageNum'] =  isset($_GET['page'])?$_GET['page']:1;
        $data=array_map(trim,$data);
        cookie('data',$data);
        $this->assign('tradeTypeNew',$data['tradeType']);
        $this->assign('tradeStatusNew',$data['tradeStatus']);
        $this->assign('payTypeNew',$data['payType']);
        $this->assign('userCompany',$data['userCompany']);
        $this->assign('tel',$data['tel']);
        $this->assign('name',$data['userName']);
        $this->assign('payno',$data['mainTradeNo']);
        $this->assign('orderno',$data['orderNo']);
        $this->assign('tradeTimeStart',$data['tradeTimeStart']);
        $this->assign('tradeTimeEnd',$data['tradeTimeEnd']);
        $this->assign('tradeFinishTimeStart',$data['tradeFinishTimeStart']);
        $this->assign('tradeFinishTimeEnd',$data['tradeFinishTimeEnd']);
        $html = send_post(C('URL').'/manager/tradeRecord/findTradeRecord',$data);
        if($html['code'] == 2000){
			$this->assign('reco',$html['data']['dataList']);
		}

        import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($html['data']['totalRowNumber'],$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$this->assign('page',$p->show());
		$this->assign('url',C('URL'));
		session('exportData',$data);
		$this->display();		
	}

public function export(){
	set_time_limit(0);
	$xlsCell  = array(
			array('mainTradeNo','交易号'),
			array('orderNo','订单号'),
			array('thirdTradeNo','微信/支付宝流水号'),
			array('tradeTime','交易开始时间'),
			array('userNickName','昵称'),
			array('userRealName','真实姓名'),
			array('userCompany','公司名称'),
			array('userTelephone','手机号'),
			array('title','备注'),
			array('money','金额'),
			array('tradeFinishTime','交易结束时间'),
			array('tradeType','用户支出/支入'),
			array('tradePayType','交易类型'),
			array('tradeStatus','交易状态'),
			array('payType','支付类型')
	);
	$sessionData = session('exportData');
	$countAll = send_post(C('URL').'/manager/tradeRecord/exportCount',$sessionData);
	if($countAll["data"]["count"] < 1){
		echo json_encode(['code'=>false,'msg'=>'暂无数据可下载！','data'=>'']);
		exit();
	}
	$sessionData["pageSize"] = 10000;
	if($countAll["data"]["count"]%$sessionData["pageSize"] ==0){
		$pageNum = $countAll["data"]["count"]/$sessionData["pageSize"];//计算总数能分成几批（每批下载15000条）
	}else{
		$pageNum = ceil($countAll["data"]["count"]/$sessionData["pageSize"]);//计算总数能分成几批（每批下载15000条）
	}
	// var_dump($pageNum);exit();
	$zip = new ZipArchive;
	if ($zip->open(UPLOAD_FILE.date('Ymd') . '.zip',ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
		
		for($i=0;$i<$pageNum;$i++){
			$sessionData["start"] = $i*$sessionData["pageSize"];
			$html2 = send_post(C('URL').'/manager/tradeRecord/export',$sessionData);
			$xlsData = $html2['data'];
				
			foreach($xlsData as $k => $v)
			{
				switch ($v['payType']) {
					case '0':
						$xlsData[$k]['payType'] = '支付宝';
						break;
					case '1':
						$xlsData[$k]['payType'] = '微信';
						break;
					case '2':
						$xlsData[$k]['payType'] = '余额支付';
						break;
					case '3':
						$xlsData[$k]['payType'] = '银行卡';
						break;
					case '4':
						$xlsData[$k]['payType'] = '线下付款';
						break;
					default:
						$xlsData[$k]['payType'] = '';
						break;
				}

				switch ($v['tradeStatus']) {
					case '0':
						$xlsData[$k]['tradeStatus'] = '失败';
						break;
					case '1':
						$xlsData[$k]['tradeStatus'] = '待付款';
						break;
					case '2':
						$xlsData[$k]['tradeStatus'] = '成功';
						break;
					case '3':
						$xlsData[$k]['tradeStatus'] = '待收款';
						break;
					default:
						$xlsData[$k]['tradeStatus'] = '';
						break;
				}
				$zhi = zhiru($v['tradeType']);
				$style = zhichu($v['tradeType']);
				$xlsData[$k]['tradeType'] = $zhi;
				if($zhi == '支出'){
					$xlsData[$k]['money'] = '-'.$v['money'];					
				}
				$xlsData[$k]['tradePayType'] = $style;				
			}
			$fileName = 'yiguanjian'.$i.'('.date('YmdHi').').xls';
			$this->exportExcel($xlsCell,$xlsData,$fileName);
				
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
public function downExcel($path){
	$file = fopen($path,"r"); // 打开文件
	// 输入文件标签
	Header("Content-type: application/octet-stream");
	Header("Accept-Ranges: bytes");
	Header("Accept-Length: ".filesize($path));
	Header("Content-Disposition: attachment; filename=" .date('Ymd') . ".zip");
	// 输出文件内容
	echo fread($file,filesize($path));
	fclose($file);	//关闭文件
	unlink($path);	//下载完之后删除压缩包
	$this->delDir(UPLOAD_FILE,false);
}

public function exportExcel($expCellName,$expTableData,$fileName){
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
	// $objActSheet->getColumnDimension("E")->setWidth(15);
	// $objActSheet->getColumnDimension("F")->setWidth(15);
	// $objActSheet->getColumnDimension("G")->setWidth(40);
	// $objActSheet->getColumnDimension("H")->setWidth(20);
	// $objActSheet->getColumnDimension("I")->setWidth(40);
	for($i=0;$i<$cellNum;$i++){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
	}
	for($i=0;$i<$dataNum;$i++){
		for($j=0;$j<$cellNum;$j++){
			$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3),' '.$expTableData[$i][$expCellName[$j][0]]);
			// if($j == 9){
			// 	$zhi = zhiru($expTableData[$i][$expCellName[$j][0]]);
			// 	$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3),$zhi);
			// }else if($j == 12){
			// 	$style = zhichu($expTableData[$i][$expCellName[$j][0]]);
			// 	$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3),$style);
			// }else if($j == 10){
			// 	if($zhi == '支出'){
			// 		$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), '-'.$expTableData[$i][$expCellName[$j][0]]);
			// 	}else{
			// 		$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3),$expTableData[$i][$expCellName[$j][0]]);
			// 	}
			// }

		}

	}
	ob_end_clean();
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$file_path = UPLOAD_FILE.$fileName;
	$objWriter->save($file_path);
}

    public function balance(){
    	$data['pageSize'] = 15;
        $data['start'] =  isset($_GET['page'])?(intval($_GET['page'])-1)*15:0; 
        $data['telephone'] = isset($_GET['telephone'])?$_GET['telephone']:'';
        $data['userNickName'] = isset($_GET['username'])?$_GET['username']:'';
        $data['companyName'] = isset($_GET['company'])?$_GET['company']:'';
        //dump($data);
        $html = send_post(C('URL').'/manager/user/queryAccountBalance',$data);
        //dump($html);
        import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($html['data']['count'],$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$this->assign('page',$p->show());
		$this->assign('reco',$html['data']['list']);
		$this->assign('telephone',$data['telephone']);
		$this->assign('username',$data['userNickName']);
		$this->assign('company',$data['companyName']);
    	$this->display();
    }


    //平台余额明细
    public function platform(){
        parent::userauth2(107);
        $this->getsearchparam();
        $data['tradeType']   = isset($_GET['s'])?$_GET['s']:''; //交易类型
        $data['tradeStatus']   = isset($_GET['t'])?$_GET['t']:''; //交易状态
        $data['payType']   = isset($_GET['p'])?$_GET['p']:''; //支付类型
        $data['tel'] = isset($_GET['tel'])?$_GET['tel']:'';//手机号
        $data['userCompany'] = isset($_GET['userCompany'])?$_GET['userCompany']:'';//手机号
        $data['userName'] = isset($_GET['username'])?$_GET['username']:'';//用户昵称
        //$data['mainTradeNo'] = isset($_GET['payno'])?$_GET['payno']:'';//交易号
        $data['orderNo'] = isset($_GET['orderno'])?$_GET['orderno']:'';//订单号
        $data['tradeNo'] = isset($_GET['payno'])?$_GET['payno']:'';//交易号
        $data['tradeTimeStart'] = isset($_GET['sBeginTime'])?$_GET['sBeginTime']:'';
        $data['tradeTimeEnd'] = isset($_GET['sEndTime'])?$_GET['sEndTime']:'';
        $data['pageSize'] = 15;
        $data['start']=$_GET['page']==0?0:($_GET['page']-1)*$data['pageSize'];
        //$this->redirect('index/export',array('data'=>$data));
        //print_r($data);exit;
        cookie('data',$data);
        $this->assign('userCompany',empty($data['userCompany'])?'':$data['userCompany']);
        $this->assign('tel',empty($data['tel'])?"":$data['tel']);
        $this->assign('name',empty($data['userName'])?"":$data['userName']);
        $this->assign('payno',empty($data['mainTradeNo'])?"":$data['mainTradeNo']);
        $this->assign('orderno',empty($data['orderNo'])?"":$data['orderNo']);
        $this->assign('tradeTimeStart',empty($data['tradeTimeStart'])?"":$data['tradeTimeStart']);
        $this->assign('tradeTimeEnd',empty($data['tradeTimeEnd'])?"":$data['tradeTimeEnd']);
        $this->assign('tradeStatus',empty($data['tradeStatus'])&&$data['tradeStatus']!=0?"":$data['tradeStatus']);
        $this->assign('tradeType',empty($data['tradeType'])&&$data['tradeType']!=0?"":$data['tradeType']);
        $this->assign('payType',empty($data['payType'])&&$data['payType']!=0?"":$data['payType']);
        $this->assign('userName',empty($data['userName'])?"":$data['userName']);
        $this->assign('tradeNo',empty($data['tradeNo'])?"":$data['tradeNo']);
        $html = send_post(C('URL').'/manager/tradeRecord/platformTradeList',$data);
        //dump($data);exit;
        //dump($html['data']);exit;
        if($html['code'] == 2000){
            //dump($html['data']['dataList']);
            $this->assign('reco',$html['data']['list']);
            $this->assign('balance',number_format($html['data']['balance'],2,'.',''));
        }

        import('ORG.Util.Page');				// 导入分页类
        $pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
        $page_row = 15;//每页显示条数
        $p = new Page ($html['data']['count'],$page_row,8);
        $p->isJumpPage = true;
        $p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
        $this->assign('page',$p->show());
        $this->assign('url',C('URL'));
        $this->display();

    }




    /**
     * 商家月返利
     */
    public function monthRebate(){
        $this->assign('status', D('status')->field('id, name, value')->order('id asc')->where('pid = 3')->select());

        //dump($_GET);exit;
        $data['pageSize'] = 15;
        $data['start']=$_GET['page']==0?0:($_GET['page']-1)*$data['pageSize'];
        $data['startDate']=$_GET['month_date'];
        $data['checkStatus'] =$_GET['s'];
        $html = send_post(C('URL').'/manager/monthRebate/queryMonthRebateInfo',$data);
        //dump($data);exit;
        import('ORG.Util.Page');				// 导入分页类
        $pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
        $page_row = 15;//每页显示条数
        $p = new Page ($html['data']['monthRerbateSize'],$page_row,8);
        $p->isJumpPage = true;
        $p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
        $this->assign('page',$p->show());
        //dump($html['data']['monthRebateList']);exit;
        $this->assign('list',$html['data']['monthRebateList']);
        $this->assign('url',C('URL'));
        $this->display();
    }


    /**
     * 查看明细
     */
    public function detail(){
        $data['sid']=$_GET['sid'];
        $html=send_post(C('URL').'/manager/monthRebate/getMonthRebate',$data);
        $this->assign('list',$html['data']);
        $this->display();
    }


    /**
     * 查看订单
     */
    public function order(){
        $data['sid']=$_GET['sid'];
        $data['userId']=$_GET['userId'];
        $data['monthTime']=$_GET['monthTime'];
        $data['pageSize'] = 15;
        $data['start']=$_GET['page']==0?0:($_GET['page']-1)*$data['pageSize'];
        $html=send_post(C('URL').'/manager/monthRebate/getMonthRebateDetail',$data);
        //dump($html['data']['monthRebate']);exit;
        import('ORG.Util.Page');				// 导入分页类
        $pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
        $page_row = 15;//每页显示条数
        $p = new Page ($html['data']['count'],$page_row,8);
        $p->isJumpPage = true;
        $p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
        $this->assign('page',$p->show());
        $this->assign('list',$html['data']['list']);
        $this->assign('monthRebate',$html['data']['monthRebate']);
        $this->display();
    }



}
?>