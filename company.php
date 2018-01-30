<?php
require_once('base.php');

require_once('CompanyBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
//require_once('CommBusiness.php');


$comtype = ConstUtil::getConstArray("CompanyType");


//TplConstTitle::setPageTitle($smarty, 'Home');
//TplConstTitle::setTplTitle($smarty, 'User','New');

$combusi = new CompanyBusiness();



if (empty($_REQUEST['module']) || $_REQUEST['module']=="List")
{
	$data = $combusi->search();
	$smarty->assign('data', $data);
    $smarty->display('company/list.tpl');

}
else if ($_REQUEST['module'] == "new")//插入数据用
{
	//追加完毕，回到List
	$data = $_POST['company'];
	$combusi->insert($data);
	echo "<script language='javascript'>";
	echo "location='company.php?module=List';";
	echo "</script>";
}
else if ($_REQUEST['module'] == "edit")
{
	$id = $_REQUEST['id'];
	$data = $combusi->search($id );
	$smarty->assign('data', $data[0]);
    $smarty->display('company/list.tpl');
}
else if ($_REQUEST['module'] == "del")
{
	//删除完毕后返回到List画面
}
else if ($_REQUEST['module'] == "ini")
{
	$comtype = ConstUtil::getConstArray("CompanyType");
	$smarty->assign('comtype', $comtype);
    $smarty->display('company/new.tpl');

}






?>