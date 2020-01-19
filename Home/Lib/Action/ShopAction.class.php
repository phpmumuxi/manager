<?php
Vendor('Qiniu.Auth','','.class.php');
class ShopAction extends CommonAction {
	public function index() {
		
		parent::userauth2(91);
		$this->getsearchparam();
		$data = $this->zuhewhere();
		//重组$data
		//重组$data
		//重组$data
		if($_GET['is_hot_recommend'] == 1){
			//是否推荐
			$data['hotRecommend'] = $_GET['is_hot_recommend'];
			$this->assign('is_hot_recommend',$_GET['is_hot_recommend']);
		}
		if(isset($_GET['is_hot_recommend']) && $_GET['is_hot_recommend'] == 0){
			//是否推荐
			$data['hotRecommend'] = $_GET['is_hot_recommend'];
			$this->assign('is_hot_recommend',2);
		}
		
		if($_GET['is_hot_recommend'] <0){
			//是否推荐
			$this->assign('is_hot_recommend',-1);
		}
		$data['keyWord'] = $data['keyWord']?$data['keyWord']:'';
		$keyWord = $data['keyWord'];
		$data['start'] = $data['start']?$data['start']:0;
		$data['pageSize'] = 15;
		$data[' '] = isset($data['checkStatus'])?$data['checkStatus']:-1;
	//$html = send_post('http://192.168.1.28:8080/manager/serviceGoods/goodsBaseInfo',$data);
	$html = send_post(C('URL').'/manager/serviceGoods/goodsBaseInfo',$data);
$count = $html['data']['count'];   //改成固定数
//$count = 80000;
		$shop = $html['data']['data'];
		$this->assign('shop',$shop);
 //dump($html);exit;
		import('ORG.Util.Page');				// 导入分页类
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('key',$keyWord);
		$this->assign('page',$show);
		$this->assign('url',C('URL'));
		$this->display();

	}
	
	//店铺列表
    public function shops(){
    	if(isset($_GET['userShopType']) && $_GET['userShopType'] != 0){
			$data['userShopType'] = $_GET['userShopType'];
			$this->assign('userShopType',$data['userShopType']);
		}else{
			$this->assign('userShopType',0);
		}
    	if(isset($_GET['dataSource']) && ($_GET['dataSource'] == 0 || $_GET['dataSource'] == 1)){
			$data['dataSource'] = $_GET['dataSource'];
			$this->assign('dataSource',$data['dataSource']);
		}else{
			$this->assign('dataSource',-1);
		}
        $data['telephone']=isset($_POST['telephone'])?$_POST['telephone']:$_GET['telephone'];
        $data['userNickName']=isset($_POST['userNickName'])?$_POST['userNickName']:$_GET['userNickName'];
        $data['pageSize']=15;
        $data['start']=!isset($_GET['page'])?0:($_GET['page']-1)*$data['pageSize'];
        $list=send_post(C('URL').'/manager/seUserExpand/shopManagementAdmin',$data);
        
        import('ORG.Util.Page');
		$p = new Page ($list['data']['count'],$data['pageSize'],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->assign('list',$list['data']['list']);
        $this->display();
    }

    public function goods() {
		$data['userId'] =  $_GET['companyId'];	
		if(!$data['userId']){
			$this->error('缺少参数！');
		}
		$data['pageSize'] = 15;
		$data['pageNum']=!isset($_GET['page'])?0:($_GET['page']-1)*$data['pageSize'];
		$html = send_post(C('URL').'/manager/service/myShop',$data);
		import('ORG.Util.Page');
		$p = new Page ($html['data']['totalCount'],$data['pageSize'],8);
		$p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$this->assign('page',$show);
		$this->assign('companyName',$html['data']['companyName']);
		$this->assign('shop',$html['data']['dataList']);
		$this->display();

	}

	//商家统计--运营操作数据统计
	public function business(){
		$this->getsearchparam();
		$data = $this->zuhewhere();
		$data['userId'] = session('ThinkUser')["ID"];
		if(session('ThinkUser')["Username"] == "admin"){
			$userlist = M("user")->where(["Roleid" => 2])->select();
			$this->assign("userlist",$userlist);
			$this->assign('url',C('URL'));
			$this->display("adminbusiness");
		}else{
			$data['keyWord'] = $data['keyWord']?$data['keyWord']:'';
			$keyWord = $data['keyWord'];
			if(isset($_GET['serviceStatus'])){
				$data['serviceStatus'] = $status = $_GET['serviceStatus'] ;
			}else{
				//全部状态产品
				$status = -1;
			}
				
			// 		var_dump(session('ThinkUser'));
			// 		echo $data['userId'];exit;
			$data['start'] = $_GET['page']?$_GET['page']:0;
			$data['pageSize'] = 15;
			$data['checkStatus'] = isset($data['checkStatus'])?$data['checkStatus']:-1;
			//$html = send_post('http://192.168.1.28:8080/manager/serviceGoods/goodsBaseInfo',$data);
				
			$html = send_post(C('URL').'/manager/operateUserInfo/queryUserInfoByUserId',$data);
			$count = $html['data']['sumCount'];
			$shop = $html['data']['infoList'];
			$this->assign('shop',$shop);
			// var_dump($shop);exit;
			import('ORG.Util.Page');				// 导入分页类
			$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
			$page_row = 15;//每页显示条数
			$p = new Page ($count,$page_row,8);
			$p->isJumpPage = true;
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$show = $p->show();
			$this->assign('serviceStatus',$status);
			$this->assign('key',$keyWord);
			$this->assign('page',$show);
			$this->assign('url',C('URL'));
				
			$this->display();
		}
	}

	/*
	 * 商品删除
	 * @param string $sid 商品id
	 */
	public function delgoods(){
		parent::userauth2(91);
		$data['sid'] =  $_POST['sid'];
		//$data['sid'] =  '00c24487f5194fbab62f601577c9a450';
		$html = send_post(C('URL').'/manager/serviceGoods/delGoodsBase',$data);
		//$html = send_post('http://192.168.1.27:8080/manager/serviceGoods/delGoodsBase',$data);
		if($html['success']){
			$this->ajaxReturn(['success'=>1]);
		}else{
			$this->ajaxReturn(['success'=>0]);
		}
		
	}
	
	/*
	 * 商品推荐
	 * @param string sid 商品id
	 * @param string is_hot_recommend 是否推荐
	 */
	public function goodsrecommend(){
		$data['goodsId'] =  $_GET['sid'];
		$data['HotRecommend'] = $_GET['is_hot_recommend'];

	//$html = send_post('http://192.168.1.28:8080/manager/fbSellerGoodsRel/updateFbSellerGoodsRel',$data);
	$html = send_post(C('URL').'/manager/fbSellerGoodsRel/updateFbSellerGoodsRel',$data);

		if($html['success']){
			$url = $_SERVER['HTTP_REFERER'];
			Header("HTTP/1.1 303 See Other");
			Header("Location: $url");
		}else{
			$this->error('操作失败');
		}
	}
	
	/**
	 * @author zyl 
	 * 服务上下架编辑
	 */

	public function serviceedit(){

		if(isset($_POST) && $_POST){
		
			$data['isUse'] = $_POST['isUser'];
			$data['serviceStatus'] = $_POST['serviceStatus'];
			$data['sid'] = $_POST['sid'];
			//var_dump($data)	;exit;
			//$html = send_post('http://192.168.1.27:8080/manager/serviceGoods/updateGoodsBase',$data);
	
			$html = send_post(C('URL').'/manager/serviceGoods/updateGoodsServiceStatus',$data);
			if($html['success']){
				$this->ajaxReturn(['success'=>1,'refereurl' => $_SERVER['HTTP_REFERER']]);
			}else{
				$this->ajaxReturn(['success'=>0]);
			}
		}
	
	}
	

	//商品编辑
	public function shopedit(){
		if(isset($_POST) && $_POST){
			//var_dump($_POST);exit;
			foreach ($_POST['formdata'] as $k=>$v){
				$data[$v['name']] = $v['value']; 
			}
			$data['tradeClassHeadArrary'] = $_POST['tradeClassHeadArrary'];
			$data['servicePictureArray'] = json_encode($_POST['imagelist']);
			$data['classValueOne'] = $_POST['classValueOne'];
			$data['classValueTwo'] = $_POST['classValueTwo'];
			$data['classValueThree'] = $_POST['classValueThree'];
			$data['tradeSpecParameterArrary'] = $_POST['tradeSpecParameterArrary'];
			$data['tradePriceRangeArrary'] = $_POST['tradePriceRangeArrary'];
			$data['sumStockNum'] = $_POST['sumStockNum'];
			
	       $html = send_post(C('URL').'/manager/serviceGoods/updateGoodsBase',$data);
			if($html['success']){
				$this->ajaxReturn(['success'=>1,'refereurl' => $_SERVER['HTTP_REFERER']]);
			}else{
				$this->ajaxReturn(['success'=>0]);
			}
		}
		$data['sid'] =  $_GET['sid'];
		$data['module'] =  $_GET['module'];
		$html = send_post(C('URL').'/manager/serviceGoods/getGoodsBase',$data);
		//dump($html);
		if($_GET['module'] == 'he_house_estate'){
			$goodsData = $html['data']['resultMap'][0]['goodsData'];
			//dump($goodsData);
			$goodsLogs = $html['data']['resultMap'][0]['goodsLogs'];
			$picture = $html['data']['picList'];
			//dump($picture);
			$userData = $html['data']['resultMap'][0]['userData'];
			$checkStatus = $goodsData['checkStatus'];
		}else{
			$goodsData = $html['data']['resultMap'];
			//dump($goodsData);
			$picture = $html['data']['picList'];
			//dump($picture);
			$checkStatus = $goodsData['checkStatus'];
		}

		$this->assign('httpreferer',$_SERVER['HTTP_REFERER']);
		//var_dump($_SERVER['HTTP_REFERER']);
		$this->assign('html',$html['data']['resultMap']);
		$this->assign('admin',$_SESSION['ThinkUser']);
		$this->assign('checkStatus',$checkStatus);
		$this->assign('goodsData',$goodsData);
		$this->assign('specList',$goodsData['specList']['0']);
		$this->assign('goodsLogs',$goodsLogs);
		$this->assign('picture',$picture);
		//var_dump($goodsData);exit;
		$this->assign('userData',$userData);
		$this->assign('module',$_GET['module']);
		$this->assign('serviceType',$_GET['serviceType']);
		$this->assign('url',C('URL'));
		$this->assign('sid',$_GET['sid']);
		if(intval($_GET["serviceType"]) == 1){
			$this->display('serviceedit');//0是商品；1是服务
		}else{
			$this->display();
		}
		
	}
	
	
	public function ceshi(){
		$this->display();
	}

	//详情信息
	public function shop(){
		parent::userauth2(91);
		$data['sid'] =  $_GET['sid'];
		
		$data['module'] =  $_GET['module'];
		$html = send_post(C('URL').'/manager/serviceGoods/getGoodsBase',$data);
		if($_GET['module'] == 'he_house_estate'){
			$goodsData = $html['data']['resultMap'][0]['goodsData'];
			//dump($goodsData);
			$goodsLogs = $html['data']['resultMap'][0]['goodsLogs'];
			$picture = $html['data']['picList'];
			//dump($picture);
			$userData = $html['data']['resultMap'][0]['userData'];
			$checkStatus = $goodsData['checkStatus'];
		}else{
			$goodsData = $html['data']['resultMap'];
			//dump($goodsData);
			$picture = $html['data']['picList'];
			//dump($picture);
			$checkStatus = $goodsData['checkStatus'];
		}

		$this->assign('html',$html['data']['resultMap']);
		$this->assign('admin',$_SESSION['ThinkUser']);
		$this->assign('checkStatus',$checkStatus);
		$this->assign('goodsData',$goodsData);
		$this->assign('specList',$goodsData['specList']['0']);
		$this->assign('goodsLogs',$goodsLogs);
		$this->assign('picture',$picture);
		$this->assign('userData',$userData);
		$this->assign('module',$_GET['module']);
		$this->assign('url',C('URL'));
		$this->assign('sid',$_GET['sid']);
		$this->assign('httpreferer',$_SERVER['HTTP_REFERER']);
		if($_GET['module'] == 'he_house_estate'){
			$this->display('Shop/house');
		}else{
			if($_GET['module'] == 'community_goods_extend'){
				$res = send_post(C('URL').'/manager/community/getCommunityType');
				$this->assign('list',$res['data']);
			}
			$this->display('Shop/shop');
		}
	}
	//获取七牛云token
	function getQiNiuToken(){
		$auth = new \Qiniu\Auth(c('QINIUYUN')['accessKey'],c('QINIUYUN')['secrectKey']);
		$upToken = $auth->uploadToken(c('QINIUYUN')['bucket'],$_POST['imageName']);
		return  ajaxReturn(0,'',['token'=>$upToken,'domain' => c('QINIUYUN.domain'),'imgStyle' => c('QINIUYUN.imgStyle')]);
	}

	public function pass(){
		$pass = $_GET['_URL_'];
		$this->assign('url',C('URL'));
		$this->assign('pass',$pass);
		$this->display();
	}

	public function nopass(){
		$pass = $_GET['_URL_'];
		$this->assign('url',C('URL'));
		$this->assign('pass',$pass);
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
		//获取商品状态
		$this->assign('status', D('status')->field('id, name, value')->order('id asc')->where('pid = 3')->select());
		
	}

	//组合请求数据
	private function zuhewhere(){
		$data = array();

		//分页
		if(isset($_GET['page']) && intval($_GET['page']) >= 0){
			$data['start'] =  (intval($_GET['page'])-1)*15;
		}

		//状态
		if(isset($_GET['s'])){
			$data['checkStatus'] = intval($_GET['s']);
			if($data['checkStatus'] !== $_SESSION['checkStatus']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
			}
			$_SESSION['checkStatus'] = intval($_GET['s']);	
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
				if($data['keyWord'] !== $_SESSION['shop']){
					$data['start'] = 0;
					unset($_REQUEST['page']);
				}
				$_SESSION['shop'] = $key;
		}
		return $data;
	}
}
?>

