<?php
require_once('base.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TorokuBusiness.php');


TplConstTitle::setPageTitle($smarty, 'Toroku');
TplConstTitle::setTplTitle($smarty, 'Toroku','List');

$massage = new TorokuBusiness();
$link = $_POST['select2'];

if($link==NULL){
	
	
	$type = $massage->select_cType($_SESSION['userinfo']['idm_company']);
	$level_one = $massage->select_toroku(1,$type[0][0]);
	$level_two = $massage->select_toroku(2,$type[0][0]);
	
	$smarty->assign("level_one",$level_one);
	$smarty->assign("level_two",$level_two);
	$smarty->display("toroku/toroku.tpl");
}
else{
	//取url,根据ID
	if($link=="2")
	{
		$link=NULL;
		echo "<script language='javascript'>";
	    echo "location='toroku_syousai.php';";
	    echo "</script>";
	}else if($link=="3")
	{
		$link=NULL;
	    echo "<script language='javascript'>";
	    echo "location='toroku_list.php';";
	    echo "</script>";	
	}
}
?>