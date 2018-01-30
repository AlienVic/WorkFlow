<?php

require_once('base.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('ShukeiBusiness.php');

$massage = new ShukeuBusiness();

if($_REQUEST['module']=="shiji")
{
	if($_REQUEST['type']=="select"){
		
		$sign = 1;
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','shiji');
		
		$reData = array(
						"value"=>$_REQUEST['select'],
						"time1"=>$_REQUEST['text1'],
					    "time2"=>$_REQUEST['text2'],
						"cod"=>$_REQUEST['text3'],
						);

		////插入sql查询语句
		//echo $_REQUEST['select'];
		$data = $massage->select_shiji($_REQUEST['select'],$_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3']);

		$smarty->assign("data",$data);
		$smarty->assign("reData",$reData);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_shiji.tpl");
		
	}else 
	{
		$sign = 0;
		
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','shiji');
		
		$data = $massage->select_shiji();

		$smarty->assign("data",$data);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_shiji.tpl");
	}
}
elseif ($_REQUEST['module']=="irai")
{
	if($_REQUEST['type']=="select")
	{
		$sign = 1;
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','irai');
		
		$reData = array(
						"time1"=>$_REQUEST['text1'],
					    "time2"=>$_REQUEST['text2'],
						"cod"=>$_REQUEST['text3'],
						"cod2"=>$_REQUEST['text4']
						);
						
						
		//插入sql查询语句
		$data = $massage->select_irai($_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3'],$_REQUEST['text4']);
		
		$smarty->assign("data",$data);
		$smarty->assign("reData",$reData);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_irai.tpl");
	}else{
		$sign = 0;
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','irai');
	
		$data = $massage->select_irai();
		
		$smarty->assign("data",$data);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_irai.tpl");
	}
	
	
}elseif($_REQUEST['module']=="csv"){
	
	
	//$massage->import_csv($_REQUEST['type'],$_REQUEST['select'],$_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3'],$_REQUEST['text4']);

	if($_REQUEST['type']==0)
	{
		$massage->import_csv($_REQUEST['type'],null,$_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3']);
		
	}elseif ($_REQUEST['type']==1)
	{
		//echo $_REQUEST['select'];
		if($_REQUEST['select']== null){
			$_REQUEST['select'] = 6;
		}
		$massage->import_csv($_REQUEST['type'],$_REQUEST['select'],$_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3'],null);
	}else
	{
		$massage->import_csv($_REQUEST['type'],null,$_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3'],$_REQUEST['text4']);
	}
}
else{
	if($_REQUEST['type']=="select"){
		
		$sign = 1;
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','renraku');
		$reData = array(
						"time1"=>$_REQUEST['text1'],
					    "time2"=>$_REQUEST['text2'],
						"cod"=>$_REQUEST['text3']
						);
		
		//插入sql查询语句
		
		$data = $massage->select_renraku($_REQUEST['text1'],$_REQUEST['text2'],$_REQUEST['text3']);
		
		$smarty->assign("data",$data);
		$smarty->assign("reData",$reData);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_renraku.tpl");	
	}else{
		$sign = 0;
		TplConstTitle::setPageTitle($smarty, 'Shukei');
		TplConstTitle::setTplTitle($smarty, 'Shukei','renraku');
		
		$data = $massage->select_renraku();

		$smarty->assign("data",$data);
		$smarty->assign("sign",$sign);
		$smarty->display("./shukei/shukei_renraku.tpl");
	}
}


?>