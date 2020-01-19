<?php
//客户管理
class AgreementModel extends RelationModel {
	//自动验证
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
		
        //-6,手机号不合法！
        array('phone','/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/',-6,self::EXISTS_VALIDATE),
        //-3,邮箱格式不正确
        //-4,账号被占用
        array('username', '', -4, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        //-7,手机号被占用
        array('phone','',-7,self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT),
								//在更新数据时验证ID是否正确
		
	);
	
	//是否是手机号码
	public function ismobile($mobile) {
		if (!is_numeric($mobile)) {
			return false;
		}
		return preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/", $mobile) ? true : false;
	}
	
}
?>