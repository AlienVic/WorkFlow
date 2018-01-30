<?php
require_once('base.php');
require_once('DependBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('LoginBusiness.php');


$depbusi = new DependBusiness();
$const = new ConstUtil();


//处理结果0：正常, 1拒绝,；  2：NG , 3却下
if($_REQUEST['module'] == "quren1")//list项目6(确认)----------------------------------------------------------------
{
	
	if($_REQUEST['result'] == "0")//处理结果正常
	{

		//更新t_form_head
		$data = array(
				"affirmanttime" => $_REQUEST['datetime'],
				"affirmantperson" => $_SESSION['userinfo']['ID'],
				"affirmantresult" => $_REQUEST['result']
				);
				
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		
		//发送消息$sendStatus=3	
		//$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "3", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("4", $_REQUEST['ID'],$_REQUEST['idt_form_head']);
		
	}
	else if($_REQUEST['result'] == "2")//处理结果为NG
	{
		
		//反写
		$data = array("status" => "0");
		$depbusi->update("t_form_head", $data,$_REQUEST['idt_form_head']);
		$depbusi->setFailedOverflow("1",$_REQUEST['ID']);
		
		//发送消息$sendStatus=3
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "3", $_REQUEST['result']);
	}
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
	
}
else if($_REQUEST['module'] == "chren1")//list项目7(承认)----------------------------------------------------------------
{
	if($_REQUEST['result'] == "0")//处理结果正常
	{
		//更新t_form_head
		$data = array(
				"approvetime" => $_REQUEST['datetime'],
				"approveperson" => $_SESSION['userinfo']['ID'],
				"approveresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		
		//发送消息$sendStatus=4
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "4", $_REQUEST['result']);
		
		//反写overflow
		$depbusi->setOverflow("5", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
		
		
		
	}
	else if($_REQUEST['result'] == "3")//处理结果为却下
	{
		
		//反写
		$data = array("status" => "0");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("1",$_REQUEST['ID']);
		//发送消息$sendStatus=4
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "4", $_REQUEST['result']);
	}

		
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
	
}
else if($_REQUEST['module'] == "accept1")//list项目9----------------------------------------------------------------
{
	
	if($_REQUEST['result'] == "0")//处理结果为正常
	{
		
		//更新t_form_head
		$data = array(
				"baccepttime" => $_REQUEST['datetime'],
				"bacceptperson" => $_SESSION['userinfo']['ID'],
				"bacceptresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		
		
		
		//发送消息$sendStatus=5
		//$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "5", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("6", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
		
			//处理完后跳转到受领新规
		TplConstTitle::setPageTitle($smarty, 'DepJXNew');
		TplConstTitle::setTplTitle($smarty, 'Dep','JXNew');
		$data = $depbusi->getAcceptShow($_REQUEST['ID']);
		
		$smarty->assign('data',$data[0]);
		$smarty->display('depend/irai_jx_new.tpl');
		
		
		
	}
	else if($_REQUEST['result'] == "1")//处理结果为拒绝
	{
		//反写
		$data = array("status" => "0");
		$depbusi->update("t_form_head", $data,$_REQUEST['idt_form_head']);
		$depbusi->setFailedOverflow("1",$_REQUEST['ID']);
		//发送消息$sendStatus=5
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "5", $_REQUEST['result']);
		
		
		
		//处理完后跳转到一览页面
		echo "<script language='javascript'>";
		echo "location='irai_list.php?module=list'";
		echo "</script>";
		
	}
	
	

	

	
	
	
		
}
else if($_REQUEST['module'] == "accept_1_Show")//accept新规的处理-------------------------------------
{
	
	//获取表单，更新t_depend_form
	$arr1 = $const->getPartcheck();
	$arr2 = $const->getSoldercheck();
	$arr3 = $const->getInstallcheck();
	$data = array(
			"partcheck" => $_REQUEST['partcheck'],
			"partchecktext" => $arr1[$_REQUEST['partcheck']],
			"soldercheck" => $_REQUEST['soldercheck'],
			"solderchecktext" => $arr2[$_REQUEST['soldercheck']],
			"installcheck" => $_REQUEST['installcheck'],
			"installchecktext" => $arr3[$_REQUEST['installcheck']],
			"repairhistory" => $_REQUEST['repairhistory'],
			"analyseresult" => $_REQUEST['analyseresult'],
			"comment1" => $_REQUEST['comment1']
	
	
	);
	$depbusi->update("t_depend_form", $data, $_REQUEST['ID']);
	
	
	//处理完后跳转到show页面
	TplConstTitle::setPageTitle($smarty, 'DepJXShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXShow');
	$data2 = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('data',$data2[0]);
	$smarty->display('depend/irai_jx_show.tpl');
	
	
	
}
else if($_REQUEST['module'] == "accept_1_show2")//show2页面-----------------------------------------
{
	
	TplConstTitle::setPageTitle($smarty, 'DepJXShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXShow');
	$data2 = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('data',$data2[0]);
	$smarty->display('depend/irai_jx_show2.tpl');
	
	
}
else if($_REQUEST['module'] == "accept_end")//show2页面完了处理
{
	
	//更新t_form_head中status
	$data = array("status" => "5");
	$depbusi->update("t_form_head", $data, $_REQUEST['idt_form_head']);
	
	
	//发送消息$sendStatus=5
	$depbusi->sendMessage("t_depend_form",$_REQUEST['ID'], "5", "0");
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
	
	
}
else if($_REQUEST['module'] == "quren2")//list项目10,EAT确认--------------------------------------------
{
	if($_REQUEST['result'] == "0")//正常
	{
		//更新t_form_head
		$data = array(
				"bconfirmtime" => $_REQUEST['datetime'],
				"bconfirmperson" => $_SESSION['userinfo']['ID'],
				"bconfirmresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		//发送消息$sendStatus=6
		//$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "6", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("7", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
		
	}
	else if($_REQUEST['result'] == "2")//NG
	{
		//反写
		$data = array("status" => "5");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("2",$_REQUEST['ID']);
		
		
		//发送消息$sendStatus=6
		$depbusi->sendMessage("t_form_head", $_REQUEST['ID'], "6", $_REQUEST['result']);
	}
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
	
	
}
else if($_REQUEST['module'] == "chren2")//list项目11。ETA承认-------------------------------------------
{
	if($_REQUEST['result'] == "0")//正常
	{
		//更新t_form_head
		$data = array(
				"badmittime" => $_REQUEST['datetime'],
				"badmitperson" => $_SESSION['userinfo']['ID'],
				"badmitresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		//发送消息$sendStatus=7
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "7", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("8", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
	}
	else if($_REQUEST['result'] == "2")//却下
	{
		//反写
		$data = array("status" => "5");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("2",$_REQUEST['ID']);
		//发送消息$sendStatus=7
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "7", $_REQUEST['result']);
	}
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
	
}
else if($_REQUEST['module'] == "edit")//页面edit处理--------------------------------------
{
	TplConstTitle::setPageTitle($smarty, 'DepJXEdit');
	TplConstTitle::setTplTitle($smarty, 'Dep','JXEdit');
	$data = $depbusi->getAcceptShow($_REQUEST['ID']);
		
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_jx_new.tpl');
		
}
else if($_REQUEST['module'] == "accept2")//协力会社受领-------------------------------------
{
	
	if($_REQUEST['result'] == "0")//处理结果为正常
	{		
		//更新t_form_head
		$data = array(
				"aaccepttime" => $_REQUEST['datetime'],
				"aacceptperson" => $_SESSION['userinfo']['ID'],
				"aacceptresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);		
		//反写overflow
		$depbusi->setOverflow("9", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
		
		
		//处理完后跳转到受领新规
		TplConstTitle::setPageTitle($smarty, 'DepReNew');
		TplConstTitle::setTplTitle($smarty, 'Dep','ReNew');
		$data = $depbusi->getAcceptShow($_REQUEST['ID']);
		$smarty->assign('data',$data[0]);
		$smarty->display('depend/irai_report_new.tpl');
		
		
		
	}
	else if($_REQUEST['result'] == "1")//处理结果为拒绝
	{
		//反写
		$data = array("status" => "5");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("2",$_REQUEST['ID']);
		//发送消息$sendStatus=5
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "5", $_REQUEST['result']);
		
		//处理完后跳转到一览页面
		echo "<script language='javascript'>";
		echo "location='irai_list.php?module=list'";
		echo "</script>";
	}
	
	

	
	
	
}
else if($_REQUEST['module'] == "accept_2_Show")//协力报告show页面------------------------------------------
{

	//更新t_depend_form
	$data = array(
			"swapresult" => $_REQUEST['result'],
			"comment2" => $_REQUEST['comment2'],
			);
	$depbusi->update("t_depend_form",$data,$_REQUEST['ID']);
	
	
	TplConstTitle::setPageTitle($smarty, 'DepReShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','ReShow');
	$data2 = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('data',$data2[0]);
	$smarty->display('depend/irai_report_show.tpl');

}
else if($_REQUEST['module'] == "accept_2_Show2")//---------------------------------------
{
	
	
	TplConstTitle::setPageTitle($smarty, 'DepReShow');
	TplConstTitle::setTplTitle($smarty, 'Dep','ReShow');
		
	$item = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('ID',$item);
	
	$smarty->assign('item',$item[0]);
	$smarty->display('depend/irai_report_show2.tpl');
	
	
}
else if($_REQUEST['module'] == "accept_2_end")//---------------------------------------
{
	
	//发送消息$sendStatus=8,番号7
	$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "8", "0");
	
		
		
		
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
}
else if($_REQUEST['module'] == "ReQuren")//协力会社再确认
{
	if($_REQUEST['result'] == "0")//正常
	{
		//更新t_form_head
		$data = array(
				"aconfirmtime" => $_REQUEST['datetime'],
				"aconfirmperson" => $_SESSION['userinfo']['ID'],
				"aconfirmresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		//发送消息$sendStatus=6
		//$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "6", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("10", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
	}
	else if($_REQUEST['result'] == "2")//NG
	{
		//反写
		$data = array("status" => "8");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("3",$_REQUEST['ID']);
		
		
		//发送消息$sendStatus=6
		$depbusi->sendMessage("t_form_head", $_REQUEST['ID'], "9", $_REQUEST['result']);
	}
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
}
else if($_REQUEST['module'] == "ReChren")//协力会社再承认
{
	if($_REQUEST['result'] == "0")//正常
	{
		//更新t_form_head
		$data = array(
				"aadmittime" => $_REQUEST['datetime'],
				"aadmitperson" => $_SESSION['userinfo']['ID'],
				"aadmitresult" => $_REQUEST['result']
				);
		$depbusi->update("t_form_head",$data,$_REQUEST['idt_form_head']);
		//发送消息$sendStatus=10
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "10", $_REQUEST['result']);
		//反写overflow
		$depbusi->setOverflow("11", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
		
		
	}
	else if($_REQUEST['result'] == "2")//却下
	{
		//反写
		$data = array("status" => "8");
		$depbusi->update("t_form_head", $data,$_REQUEST['ID']);
		$depbusi->setFailedOverflow("3",$_REQUEST['ID']);
		//发送消息$sendStatus=10
		$depbusi->sendMessage("t_depend_form", $_REQUEST['ID'], "10", $_REQUEST['result']);
	}
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
	
	
	
}
else if($_REQUEST['module'] == "edit2")//结果页面edit------------------------------------------
{
	TplConstTitle::setPageTitle($smarty, 'DepReEdit');
	TplConstTitle::setTplTitle($smarty, 'Dep','ReEdit');
	$data = $depbusi->getAcceptShow($_REQUEST['ID']);
	$smarty->assign('ID',$_REQUEST['ID']);
	$smarty->assign('data',$data[0]);
	$smarty->display('depend/irai_report_edit.tpl');
	
	
}
else if($_REQUEST['module'] == "shouling")//最终受领
{
	
	//更新t_depend_form
	$data = array(
			"endtime" => $_REQUEST['datetime'],
			"endperson" => $_REQUEST['userinfo']['ID'],
			"endresult" => $_REQUEST['result']
			);
	$depbusi->update("t_form_head",$data,$_REQUEST['ID']);
	

	//反写overflow
	$depbusi->setOverflow("12", $_REQUEST['ID'], $_REQUEST['idt_form_head']);
	
	//处理完后跳转到一览页面
	echo "<script language='javascript'>";
	echo "location='irai_list.php?module=list'";
	echo "</script>";
}








?>