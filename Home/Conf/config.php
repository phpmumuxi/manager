<?php
return array(
	//'������'=>'����ֵ'
	'URL_PATHINFO_DEPR' => '/',								//�޸ķָ���
	'URL_ROUTER_ON' => true,								//����·�ɹ���
	'URL_CASE_INSENSITIVE' =>true,
	'TMPL_L_DELIM' => '<{',									//�޸����Ҷ����
	'TMPL_R_DELIM' => '}>',
	'DEFAULT_CHARSET'=> 'utf-8',							//��������
	'SESSION_AUTO_START' => true,							//����SESSION
	'URL_HTML_SUFFIX' => 'html|shmtl|xml', 					// ����� | �ָ�α��̬����
	'VAR_PAGE'=>'page',										//��ҳ����
	//�ֶ��Զ�����ģ����ѯ
	'DB_LIKE_FIELDS'=>'Username|Ip|CompanyName|ContactName',
	'LOAD_EXT_CONFIG' => 'setting,core,db,webconfig',							//��������������ļ�
	'TOKEN_NAME' => '__hash__',  							// ������֤�ı������ֶ�����
	'TOKEN_TYPE'=>'md5', 									//���ƹ�ϣ��֤���� Ĭ��ΪMD5
	//����ȫ��URL
	'URL' => 'http://192.168.1.207:8003',
	'URLPUSH' =>'http://180.76.140.84:8001',
	'NEWSURL'=>'http://180.76.170.194:88',
	//'NEWSURL'=>'http://admin.yiguanjiaclub.net'
	
	'TMPL_PARSE_STRING' => array(
		'__APP__' => __ROOT__.'/index.php',					// ����Ĭ�ϵ�__APP__ �滻����
		'__JS__' => __ROOT__.'/Public/js',
		'__CSS__' => __ROOT__.'/Public/style/home',
		'__IMAGE__' => __ROOT__.'/Public/image/home',
		'__UPLOAD__' => __ROOT__.'/Public/Upload',
	),
	// "QINIU_URL" => "http://qimg.app.yiguanjiaclub.org",
	"QINIU_URL" => "http://qimg.app.eachonline.com",
	"YGJ_URL" => "http://192.168.1.111:8020/CheeOnline-PC-New/ygjPC/", 
	// "YGJ_URL" => "http://eachonline.com/",
	"ADMIN_URL" => "http://192.168.1.209:7000/207" ,
	// "ADMIN_URL" => "http://admin.yiguanjiaclub.net" ,
// #009900
);
?>