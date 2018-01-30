<?php
    require_once('base.php');
	require_once('SessionUtil.php');
	require_once('ConstUtil.php');
	require_once('TorokuBusiness.php');
	
	
	
	
	
	TplConstTitle::setPageTitle($smarty, 'TorokuList');
    TplConstTitle::setTplTitle($smarty, 'Toroku','Jinbu');
	
	$massage = new TorokuBusiness();
	
	$data = $massage->select_torokuMessage();
    $length = count($data);
	
	while ($length){
			$data[$length-1]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
            $status[$length-1]=$massage->check_status($data[$length-1]['STATUS']);
    	$length--;
	}
	$right = $_SESSION['userinfo']['userright'];
	$c_type = $massage->select_cType($_SESSION['userinfo']['idm_company']);
	/*echo "<pre>";
	print_r($data);	
	print_r($status);*/
	$smarty->assign("type",$c_type[0][0]);
	$smarty->assign("right",$right);
	$smarty->assign("status",$status);
    $smarty->assign("data",$data);
	$smarty->display("toroku/toroku_list.tpl");
?>