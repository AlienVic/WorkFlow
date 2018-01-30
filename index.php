<?php
require_once('base.php');
require_once('LoginBusiness.php');
require_once('SessionUtil.php');
require_once('IndexBusiness.php');

/**
 * ログイン機能　コントロール
 *
 * @author zhao
 * @version 2011/08/16
 */
TplConstTitle::setPageTitle($smarty, 'Home');
//TplConstTitle::setTplTitle($smarty, 'User','New');

$index = new IndexBusiness();
$logger = LoggerManager::getLogger('login');
//$data = $index->select_repic($_SESSION['userinfo']['ID']);
//echo "<pre>";
//print_r($data[0]);

if($_REQUEST['module']=="select"){     //////得到告知查询的条件 
	
	$sign = 1;
	
	$select =  $_REQUEST['Categoly_id']; 
	
    //echo $select;
	
	$data0 = $index->select_new($_SESSION['userinfo']['ID']); /////查询topics
	$data1 = $index->select_repic($_SESSION['userinfo']['ID']);/////查询回览
	$data2 = $index->select_notice($select);////查询告知
		
	$length0 = count($data0);
	$length1 = count($data1);
	//echo $length0;
	//echo $length1;
	while ($length0>0){
			
		$data0[$length0-1]['creattime'] = $index->check_date($data0[$length0-1]['creattime']);
			
		$length0--;
	}
		
	while ($length1>0){
			
		$data1[$length1-1]['creattime'] = $index->check_date($data1[$length1-1]['creattime']);
			
		$length1--;
	}

	
		//echo "<pre>";
		//print_r($data0);*/
		//print_r($data2);

	$smarty->assign("right",$_SESSION['userinfo']['userright']);
	$smarty->assign("sign",$sign);
	$smarty->assign("select",$select);
	$smarty->assign("data0",$data0);
	$smarty->assign("data1",$data1);
	$smarty->assign("data2",$data2);
	$smarty->display('index/index.tpl');
}
else {							////页面初始化
	
		$sign = 0;
		$select = 1;
		
		$data0 = $index->select_new($_SESSION['userinfo']['ID']); /////查询topics
		$data1 = $index->select_repic($_SESSION['userinfo']['ID']);/////查询回览
		$data2 = $index->select_notice(1);////查询告知
		
		
		$length0 = count($data0);
		$length1 = count($data1);
		//echo $length0;
		//echo $length1;
		while ($length0>0){
			
			$data0[$length0-1]['creattime'] = $index->check_date($data0[$length0-1]['creattime']);
			
			$length0--;
		}
		
		while ($length1>0){
			
			$data1[$length1-1]['creattime'] = $index->check_date($data1[$length1-1]['creattime']);
			
			$length1--;
		}

	
		//echo "<pre>";
		//print_r($data0);*/
		//print_r($data1);
		$smarty->assign("right",$_SESSION['userinfo']['userright']);
		$smarty->assign("sign",$sign);
		$smarty->assign("select",$select);
		$smarty->assign("data0",$data0);
		$smarty->assign("data1",$data1);
		$smarty->assign("data2",$data2);
		$smarty->display('index/index.tpl');
	
}

		
	
	
		
?>