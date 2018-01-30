<?php
require_once('base.php');
require_once('departBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');



$smarty = new Smarty();
TplConstTitle::setPageTitle($smarty, 'Depa');

$dep = new departBusiness();
if (empty($_REQUEST['module']) || $_REQUEST['module']=="list")
{
	TplConstTitle::setTplTitle($smarty, 'Depa','List');
	$data2 = $dep->Search();
	$smarty->assign('data', $data2);
	$smarty->display('depart/depart_list.tpl');
}
else if ($_REQUEST['module'] == "new")//插入数据用
{
	TplConstTitle::setTplTitle($smarty, 'Depa','New');
	$data2 = $dep->Searchname();
	$smarty->assign('data',$data2);
	$smarty->display('depart/depart_new.tpl');

	
}
else if ($_REQUEST['module'] == "edit")//插入数据用
{
	TplConstTitle::setTplTitle($smarty, 'Depa','Edit');
	$id = $_REQUEST['id'];
	$data2 = $dep->Searchname();
	$smarty->assign('data1',$data2);

	$data = $dep->Search($id);
	$smarty->assign('data', $data[0]);
	$smarty->display('depart/depart_edit.tpl');

	
}
else if ($_REQUEST['module'] == "del")
{	
	$dep -> DeleteLogical($_REQUEST['id']);	

	header('X-JSON: ' . createJonSuccess('deleteSuccess', null));
}

else if($_REQUEST['module'] == "next")
{
	TplConstTitle::setTplTitle($smarty, 'Depa','Show');
	//$name = $dep->Searchname();
	//$smarty->assign('data', $data2[0]);
	$data = $_POST['data'];
	$dep->Insert($data);
	$id = mysql_insert_id();
	$data3 = $dep->Search($id);
	$smarty->assign('data', $data3[0]);//传到tpl
	$smarty->display('depart/depart_show.tpl');
}
else if($_REQUEST['module'] == "update")
{
	TplConstTitle::setTplTitle($smarty, 'Depa','Show');
	$id = $_POST['id'];
	$data = $_POST['depart'];
	$data['id'] = $id;
	$dep->Update($data);
	$data3 = $dep->Search($id);
	//echo "<pre>";
	//print_r($data3);
	$smarty->assign('data', $data3[0]);//传到tpl
	$smarty->display('depart/depart_show.tpl');
}



?>