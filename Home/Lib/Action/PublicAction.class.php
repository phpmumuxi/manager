<?php
class PublicAction extends Action {
	public function verify() {
		ob_clean();
		import('ORG.Util.Image');
		Image::buildImageVerify('4','1','png','84','38','verify');
	}
	public function err($content) {
		$this->display();
	}
	public function errjson($content) {
		$err=array('s'=>$content);
		exit(json_encode($err));
	}
	public function sha1pow($pow) {
		return sha1(md5($pow));
	}
	public function location($title,$url) {
		header('Content-Type: text/html; charset=utf-8');	//输出头，防止中文乱码
		echo '<script>alert("'.$title.'"); window.top.location="'.$url.'"</script>';
		exit;
	}

	//时间美化函数
	public function Beautifytime($dtime) {
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
}
?>