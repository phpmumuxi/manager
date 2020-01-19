<?php
//api接口
class ApiAction extends Action {
	
	
	Public	function addagreement(){
				header('content-type:application:json;charset=utf8');
				header('Access-Control-Allow-Origin:*');
				header('Access-Control-Allow-Methods:POST');
				header('Access-Control-Allow-Headers:x-requested-with,content-type');

				if(IS_POST && $_POST){ 
				//echo "1";exit;
					$_POST['add_time'] = time();
					$_POST['proxy_rate'] = floatval($_POST['proxy_rate']);
					if(!D('Agreement')->ismobile($_POST['telphone'])){
						exit(json_encode(['code'=>5000,'message'=>'手机号码格式不正确','data'=>null]));
					}
						
					if($id = M('Agreement')->add($_POST)){
						$output = array(
								'code' => 2000,
								'message' => '操作成功',
								'data' => null
						);
						exit(json_encode($output,JSON_UNESCAPED_UNICODE));
					}else{
						$output = array(
								'code' => 5000,
								'message' => '操作失败',
								'data' => null
						);
						exit(json_encode($output,JSON_UNESCAPED_UNICODE));
					}
				}
	}
}
?>

