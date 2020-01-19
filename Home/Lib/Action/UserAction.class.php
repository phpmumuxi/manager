<?php
//用户名类
class UserAction extends CommonAction {

//新增用户
	public function newuser(){
		$data['startDate'] = $_GET['startdate'];//日期s
		$data['endDate']  = $_GET['enddate'];//日期s
		$data['pageNum'] = $_GET['page']?$_GET['page']:1;
		$data['pageSize'] = $_GET['pageSize']?$_GET['pageSize']:20;
		$html =  send_post(C('URL').'/manager/seUser/queryLogUser',$data);
		
		$list = $html['data']['dataList'];
 		$count = count($list);
 		$Pagesize=$data['pageSize'];
 		$count = $count?$count:0;
 		$pagekey = ownpage($count,$Pagesize);//调用分页方法第三个参数为显示页码数,默认为7
		$this->assign('list',$list);
		$this->assign('url',C('URL'));
		$this->assign('date',$data);
		$this->assign('page',$pagekey);
		$this->display();
	}

	//密码重置
	public function passwordSet() {
		$id = I('post.id','');
		//测试环境
		$user = M('user','crm_','mysql://root:luichi2015@@192.168.1.206:3306/crm');
		//线上
		// $user = M('user','crm_','mysql://eachonline_crm:Eachonline_crm@2016@192.168.0.5:33066/eachonline_crm');
		if($id){
			$data['salt'] = substr(md5(time().rand(1000,9999)),0,4);
			$data['password'] = md5(md5('123456'). $data['salt']);
			$res= $user->where("user_id='$id'")->setField($data);
			if($res){
				echo json_encode(['ststus'=>'ok','data'=>'初始化密码成功!']);
			}else{
				echo json_encode(['ststus'=>'err','data'=>'初始化密码失败!']);				
			}
		}else{			
			$keyword = I('get.keyword','','htmlspecialchars,trim');
			if($keyword){
				$where['telephone']=$keyword;
			}else{				
				$where='';
			}
			$count = $user->where($where)->count();			//总记录数	
			$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
			$page_row = 20;//每页显示条数
			import('ORG.Util.Page');						// 导入分页类
			$p = new Page ($count,$page_row,8);		
			$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
			$list = $user->where($where)->field('user_id,name,telephone,company')->limit(($pagenum-1)*$page_row.','.$page_row)->select();					
			$show = $p->show();
			$this->assign('list',$list);
			$this->assign('page',$show);					//输出分页
			$this->assign('keyword',$keyword);
			$this->co = $count;
			$this->display();
		}
	}

	public function index() {
		parent::userauth2(2);
		$keyword = I('get.keyword','','htmlspecialchars');
		$user = D('User');
		import('ORG.Util.Page');						// 导入分页类
		$where['Username']=$keyword;
		$count = $user->where($where)->count();			//总记录数						
		$pagenum = isset ( $_REQUEST ['page'] ) ? $_REQUEST ['page'] : 1;
		$page_row = 15;//每页显示条数
		$p = new Page ($count,$page_row,8);
		// $p->isJumpPage = true;
		$p->setConfig('theme','%totalRow% %header% %nowPage%/%totalPage% 页%first% %upPage% %linkPage% %downPage% %end% %jump%');
		$show = $p->show();
		$volist = $user->relation(true)->where($where)->order('Dtime desc')->limit(($pagenum-1)*$page_row.','.$page_row)->select();
		$this->assign('volist',$volist);
		$this->assign('page',$show);					//输出分页
		$this->assign('keyword',$keyword);
		$this->co = $count;
		$this->display();
	}
	public function useradd() {
		parent::win_userauth(3);
		$role=M('role');
		$volist=$role->where('Status=0')->getField('ID,Rolename,Status');
		$this->assign('volist',$volist);
		$this->display('add');
	}
	//添加用户
	public function useradd_do() {
		//验证用户权限
		parent::userauth(3);
		$data=array();
		if ($this->isAjax()) {
			$data['Roleid'] = I('post.roleid','','htmlspecialchars');
			$data['Username'] = I('post.username','','htmlspecialchars');
			$data['Password'] = I('post.password','');
			$data['Email'] = I('post.email','');
			$data['Description'] = I('post.description','','htmlspecialchars');
			$data['Status'] = I('post.status','');
			//自动完成验证与新增
			$user=D('User');
			if ($user->create($data)) {
				$uid=$user->add();
				$role=M('role');
				$r=$role->where('ID='.$data['Roleid'])->getField('Competence');
				//给新用户添加默认权限
				$user->where("ID=$uid")->setField(array('Competence' => $r));
				parent::operating(__ACTION__,0,'新增用户：'.$data['Username']);
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'新增失败：'.$data['Username']);
				R('Public/errjson',array($user->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
	//修改界面
	public function useredit() {
		parent::win_userauth(4);
		$id = I('get.id','');
		if ($id=='' || !is_numeric($id)) {
			parent::operating(__ACTION__,1,'参数错误');
			$this->content='参数ID类型错误，请关闭本窗口';
			exit($this->display('Public:err'));
		}
		$id=intval($id);
		$role=M('role');
		$user=M('user');
		//获取分类信息
		$volist=$role->where('Status=0')->order('Dtime')->getField('ID,Rolename,Status');
		$data=array('ID' => $id);
		if ($result=$user->where($data)->find()) {
			$this->result=$result;
			$this->volist=$volist;
			//获取权限数据
			$co=M('competence');
			$aulist=$co->where('Sid=0 AND Status=0')->order('Dtime asc')->getField('ID,Cname,Status');
			$slist=$co->where('Sid<>0 AND Status=0')->order('Dtime asc')->getField('ID,Sid,Cname,Status');
			$this->aulist=$aulist;
			$this->slist=$slist;
			$this->display('edit');
		}else {
			parent::operating(__ACTION__,1,'数据不存在');
			$this->content='没有找到相关数据，请关闭本窗口';
			$this->display('Public:err');
		}
	}
	//用户修改处理
	public function useredit_do() {
		//验证用户权限
		parent::userauth(4);
		$data=array();
		if ($this->isAjax()) {
			$data['ID'] = I('post.id','');
			$data['Roleid'] = I('post.roleid','');
			$data['Username'] = I('post.username','','htmlspecialchars');
			$data['Password'] = I('post.password','');
			$data['Email'] = I('post.email','');
			$data['Description'] = I('post.description','','htmlspecialchars');
			$data['Competence'] = I('post.comp','');
			$data['Status'] = I('post.status');
			$user=D('User');
			if ($user->create($data)) {
				$user->save();
				parent::operating(__ACTION__,0,'更改用户资料：'.$data['Username']);
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'更新失败：'.$data['Username']);
				R('Public/errjson',array($user->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求：');
			R('Public/errjson',array('非法请求'));
		}
	}
	//个别信息修改
	public function uedit() {
		parent::win_userauth(18);
		$id = $_SESSION['ThinkUser']['ID'];
		$user=M('user');
		$data=array('ID' => $id);
		if ($result=$user->where($data)->find()) {
			$this->result=$result;
			//获取权限数据
			$this->display('uedit');
		}else {
			parent::operating(__ACTION__,1,'没有找到相关数据：'.$id);
			$this->content='没有找到相关数据，请关闭本窗口';
			$this->display('Public:err');
		}
	}
	//个别信息修改处理
	public function uedit_do() {
		//验证用户权限
		parent::userauth(18);
		$data=array();
		if ($this->isAjax()) {
			$data['ID'] = $_SESSION['ThinkUser']['ID'];
			$password = I('post.password','');
			$data['Email'] = I('post.email','');
			if (strlen($password)<6 || strlen($password)>18) {
				R('Public/errjson',array('请输入6～18位数的安全密码'));
			}
			if (!preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/i',$data['Email'])) {
				R('Public/errjson',array('请输入正确的邮箱地址'));
			}
			$data['Password']=R('Public/sha1pow',array($password));
			$user=M('user');
			$where=array('ID' => $data['ID']);
			if ($user->where($where)->setField($data)) {
				parent::operating(__ACTION__,0,'修改密码成功');
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'更新失败：'.$user->getError());
				R('Public/errjson',array($user->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
	//删除数据
	public function userdel() {
		//验证用户权限
		parent::userauth(5);
		//判断是否是ajax请求
		if ($this->isAjax()) {
			if (I('post.post','')=='ok') {
				$id=I('post.id','');
				if ($id=='' || !is_numeric($id)) {
					parent::operating(__ACTION__,1,'参数错误');
					R('Public/errjson',array('参数ID类型错误'));
				}else {
					$id=intval($id);
					if ($id==1) {
						parent::operating(__ACTION__,1,'不能删除系统默认用户');
						R('Public/errjson',array('不能删除系统默认用户'));
					}
					$user=M('user');
					$where=array('ID'=>$id);
					if ($user->where($where)->getField('ID')) {
						$user->where($where)->delete();
						parent::operating(__ACTION__,0,'删除用户ID为：'.$id.'的数据');
						R('Public/errjson',array('ok'));
					}else {
						parent::operating(__ACTION__,1,'删除用户失败：'.$user->getError());
						R('Public/errjson',array($user->getError()));
					}
				}
			}else {
				parent::operating(__ACTION__,1,'非法请求');
				R('Public/errjson',array('非法请求'));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
	//批量删除
	public function in_user_del() {
		//验证用户权限
		parent::userauth(5);
		if ($this->isAjax()) {
			if (!$delid=explode(',',I('post.delid',''))) {
				R('Public/errjson',array('请选中后再删除'));
			}
			//将最后一个元素弹出栈
			array_pop($delid);
			if (in_array(1,$delid)) {
				R('Public/errjson',array('不能删除ID号为1的数据'));
			}
			$id=join(',',$delid);
			$user=M('user');
			if ($user->delete("$id")) {
				parent::operating(__ACTION__,0,'批量删除ID为：'.$id.'的数据');
				R('Public/errjson',array('ok'));
			}else {
				parent::operating(__ACTION__,1,'批量删除用户失败：'.$user->getError());
				R('Public/errjson',array($user->getError()));
			}
		}else {
			parent::operating(__ACTION__,1,'非法请求');
			R('Public/errjson',array('非法请求'));
		}
	}
}
?>