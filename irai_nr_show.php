<?php 
require_once('base.php');
require_once('DependBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
//require_once('CommBusiness.php');



$depbusi = new DependBusiness();

//查数据库
$data = $depbusi->searchIdt_form_head($_REQUEST['idt_form_head']);
$solder=ConstUtil::getConstArray('Solder');
$project=ConstUtil::getConstArray('Project');
$data[0][solder] = $solder[$data[0][solder]];
$data[0][project] = $project[$data[0][project]];
$smarty->assign("data",$data[0]);

	

//页面初始化
if ( empty($_REQUEST['module'] ) || $_REQUEST['module']=="show")
{
	TplConstTitle::setPageTitle($smarty, 'DepShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','Show');
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->display('depend/irai_nr_show.tpl');
	
	
	
	
}
else if ($_REQUEST['module'] == "edit")//编辑
{
	TplConstTitle::setPageTitle($smarty, 'DepEdit');
	TplConstTitle::setTplTitle($smarty, 'Dep','Edit');
	$data2 = $depbusi->search($_REQUEST['idt_form_head']);
	$depbusi->setCheckbox($smarty, $data2[0][baditemstext]);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	
	$smarty->assign('data',$data2[0]);
	$smarty->display('depend/irai_nr_edit.tpl');
	
	
	
	

}
else if ($_REQUEST['module'] == "quren")//承认按钮处理
{
	TplConstTitle::setPageTitle($smarty, 'DepShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','Show');
	$data2 = array("status" => "1");
	$depbusi->update("t_form_head", $data2, $_REQUEST['idt_form_head']);
	$smarty->assign('idt_form_head',$_REQUEST['idt_form_head']);
	$smarty->display('depend/irai_nr_show2.tpl');
	
	
	
}
else if ($_REQUEST['module'] == "list")//一览
{
	
	if ($_REQUEST['msg'] == "1")
	{
		$data = array("contacttime" => date("Y-m-d,H:i:s"),"status" => "2");
		//更新t_form_head
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		//发送消息
		$depbusi->sendMessage("t_depend_form",$_REQUEST['idt_form_head'],"2","0");
		//反写,no=1
		$depbusi->setOverflow("1", $_REQUEST['ID'], $_REQUEST['$id_form_head']);
	}
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
}






?>