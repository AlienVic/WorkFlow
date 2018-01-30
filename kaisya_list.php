<?php
require_once('base.php');
require_once('kaisyaBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');

$comtype = ConstUtil::getConstArray("CompanyType");

$smarty = new Smarty();

//TplConstTitle::setPageTitle($smarty, 'Com');
//TplConstTitle::setTplTitle($smarty, 'User','New');


$kai = new kaisyaBusiness();
global  $data;
//初始化页面与点击list时
if (empty($_REQUEST['module']) || $_REQUEST['module']=="list")
{
	TplConstTitle::setPageTitle($smarty, 'Com');
	TplConstTitle::setTplTitle($smarty, 'Com','List');
	$data2 = $kai->Search();
	foreach( $data2 as $key => $value )
	{
		$data2[$key][type] = $comtype[$data2[$key][type]];
	}
	$smarty->assign('data', $data2);
	$smarty->display('kaisya/kaisya_list.tpl');
}
//点击新规时
else if ($_REQUEST['module'] == "new")
{
	TplConstTitle::setPageTitle($smarty, 'Com');
	TplConstTitle::setTplTitle($smarty, 'Com','New');
	$smarty->assign('comtype', $comtype);
	$smarty->display('kaisya/kaisya_new.tpl');

	
}
else if ($_REQUEST['module'] == "edit")
{
	TplConstTitle::setPageTitle($smarty, 'Com');
	TplConstTitle::setTplTitle($smarty, 'Com','Edit');
	$id = $_REQUEST['id'];

	$data3 = $kai->Search($id);
	$smarty->assign('comtype', $comtype);
	$smarty->assign('data', $data3[0]);
	$smarty->display('kaisya/kaisya_edit.tpl');

	
}
else if ($_REQUEST['module'] == "del")
{	
	$kai -> deleteCompanyData($_REQUEST['id']);	
//	echo '123';
 	header('X-JSON: ' . createJonSuccess('deleteSuccess', null));

}

else if($_REQUEST['module'] == "next")
{
	TplConstTitle::setPageTitle($smarty, 'Com');
	TplConstTitle::setTplTitle($smarty, 'Com','Show');
	$data = $_POST['data'];
	$kai->Insert($data);
	$data['ID'] = mysql_insert_id();
//	$data3 = $kai->Search($id);
	//echo "<pre>";
	//echo '123';
	$sequence['longname'] = $data['name'];
	$sequence['name'] = $data['appreviation'];
	//echo "<pre>";
	//print_r($sequence);
	
	$kai->InsertSe($sequence);
	$data[type] = $comtype[$data[type]];
	
	$smarty->assign('data', $data);
	$smarty->display('kaisya/kaisya_show.tpl');
	
	
}

else if($_REQUEST['module'] == "update")
{
	TplConstTitle::setPageTitle($smarty, 'Com');
	TplConstTitle::setTplTitle($smarty, 'Com','Show');

	$data = $_POST['company'];
	//echo "<pre>";
	//print_r($data);
	$kai->Update($data);
//	$data3 = $kai->Search($id);
//		echo "<pre>";
//	print_r($data3[0]);
	$data[type] = $comtype[$data[type]];
	
	$smarty->assign('data', $data);//传到tpl
	$smarty->display('kaisya/kaisya_show.tpl');
}



?>