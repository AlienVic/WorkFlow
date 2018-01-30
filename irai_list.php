<?php
require_once('base.php');
require_once('DependBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
//require_once('CommBusiness.php');


//获取依赖书状态常量
$DependStatus = ConstUtil::getConstArray("DependStatus");
$depbusi = new DependBusiness();



if (empty($_REQUEST['module']) || $_REQUEST['module']=="list")//页面初始化
{
	
	TplConstTitle::setPageTitle($smarty, 'DepList');
	TplConstTitle::setTplTitle($smarty, 'Dep','List');
	
	$user = $_SESSION['userinfo']['userright'];
	if($user == "0")
	{
		$flag = 0;
		$smarty->assign('flag',$flag);
	}
	else if($user == "1")
	{
		$flag = 1;
		$smarty->assign('flag',$flag);
	}
	else if($user == "2")
	{
		$flag = 2;
		$smarty->assign('flag',$flag);
	}
	else if($user == "3")
	{
		$flag = 3;
		$smarty->assign('flag',$flag);
	}
	
	$cpmtype = $_SESSION['userinfo']['comtype'];
	$smarty->assign('cpmtype',$cpmtype);
	$depbusi->initPage($smarty,$DependStatus);
	
	
	
	
	
	
	
}
else if ($_REQUEST['module'] == "quren1")//确认按钮处理
{
	TplConstTitle::setPageTitle($smarty, 'DepQuren');
	TplConstTitle::setTplTitle($smarty, 'Dep','Quren');
	
	
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	
	$data = $depbusi->search($_REQUEST['ID']);
	$depbusi->setCheckbox($smarty, $data[0][baditemstext]);
	//$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);	
	$smarty->assign('data',$data[0]);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
    $smarty->display('depend/irai_nr_quren.tpl');
    
    
    
    
    
    
    
}
else if ($_REQUEST['module'] == "chren1")//承认按钮处理
{
	TplConstTitle::setPageTitle($smarty, 'DepChren');
	TplConstTitle::setTplTitle($smarty, 'Dep','Chren');
	
	
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	
	$data = $depbusi->search($_REQUEST['ID']);
	$depbusi->setCheckbox($smarty, $data[0][baditemstext]);
	//$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);	
	$smarty->assign('data',$data[0]);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
    $smarty->display('depend/irai_nr_chren.tpl');
    
    
    
    
}
else if ($_REQUEST['module'] == "accept1")//EAT会社受领
{
	
	TplConstTitle::setPageTitle($smarty, 'DepShowOkOk');
	TplConstTitle::setTplTitle($smarty, 'Dep','ShowOkOk');
	
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo($_REQUEST['ID']);
	$smarty->assign('data',$data[0]);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
    $smarty->display('depend/irai_nr_show_ok_ok.tpl');
    
    
    
}
else if($_REQUEST['module'] == "quren2")//ETA会社确认
{
	TplConstTitle::setPageTitle($smarty, 'DepJXQuren');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXQuren');
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];	
	$data = $depbusi->getAcceptInfo($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_quren.tpl');
	
	
	
}
else if($_REQUEST['module'] == "chren2")//ETA会社承认
{
	TplConstTitle::setPageTitle($smarty, 'DepJXChren');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXChren');	
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_chren.tpl');
	
	
	
	
}
else if($_REQUEST['module'] == "accept2")//协力会社担当
{
	TplConstTitle::setPageTitle($smarty, 'DepJXDang');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXDang');	
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo2($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_dang.tpl');
	
	
}
else if($_REQUEST['module'] == "ReQuren")//协力会社确认
{
	TplConstTitle::setPageTitle($smarty, 'DepReQuren');
	TplConstTitle::setTplTitle($smarty, 'Dep','ReQuren');
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo2($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_report_quren.tpl');
	
	
}
else if($_REQUEST['module'] == "ReChren")//协力会社承认
{
	TplConstTitle::setPageTitle($smarty, 'DepReChren');
	TplConstTitle::setTplTitle($smarty, 'Dep','ReChren');
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo2($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_report_chren.tpl');
	
	
}
else if($_REQUEST['module'] == "ShouLing")//最终受领
{
	TplConstTitle::setPageTitle($smarty, 'DepShouLing');
	TplConstTitle::setTplTitle($smarty, 'Dep','ShouLing');
	$datetime = date("Y-m-d H:i:s");
	$username = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$data = $depbusi->getAcceptInfo($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->assign('datetime',$datetime);
	$smarty->assign('username',$username);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_report_shouling.tpl');
	
	
	
}
else if($_REQUEST['module'] == "print1")//协力会社打印
{
	TplConstTitle::setPageTitle($smarty, 'DepPrint');
	$data = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_print.tpl');
	
}
else if($_REQUEST['module'] == "print2")//ETA会社打印
{
	TplConstTitle::setPageTitle($smarty, 'DepPrint');
	$data = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_print2.tpl');

	
}




?>