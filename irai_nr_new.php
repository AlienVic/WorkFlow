<?php
require_once('base.php');
require_once('DependBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('LoginBusiness.php');


TplConstTitle::setPageTitle($smarty, 'DepNew');
TplConstTitle::setTplTitle($smarty, 'Dep','New');
$depbusi = new DependBusiness();
$lgb = new LoginBusiness();
	$companyname = null;	//协力工厂名
	$issuedate = null;	//当前日期
	$manageno = null;	//管理NO
	$m_no = null;		//制翻
	$goods_cod = null;	//型式
	$m_o_q = null;		//元数
	$solder = null;	//使用はんだ
	$serialno = null;	//ｼﾘｱﾙNo
	$dependnum = null;		//依赖数
	$project = null;	//工程
	$baditems = null;  //不具合内容
	$baditemstext = null;	//不具合内容文本
	$baddetails = null;		//详细
	$swaprecord = null;		//制品交换记录
	
	
	$companyname = $_SESSION['userinfo']['companyname'];	//$_SESSION['userinfo']['company']	
	$issuedate = date("Y-m-d");		
	
	if (empty($_SESSION['depend_mgno']))
	{
		$tail = $depbusi->getManageNoTail($_SESSION['userinfo']['appreviation']);
		$head = $depbusi->getManageNoHead();
		$manageno = $head."-".$tail;
		$_SESSION['depend_mgno'] = $manageno;
	}
	//echo "<pre>";
	//print_r($_SESSION['userinfo']);
	
	
//页面初始化
if (empty($_REQUEST['module']) || $_REQUEST['module']=="ini")
{

	//初始化，自动入力
	$smarty->assign('companyname',$companyname);
	$smarty->assign('issuedate',$issuedate);
	$smarty->assign('manageno',$_SESSION['depend_mgno']);
   	$smarty->display('depend/irai_nr_new.tpl');
	
}
else if ($_REQUEST['module'] == "quren")//确认按钮处理
{
	

	//获取表单数据
	$baditems = $_POST['baditems'];   
	for($i=0;$i< count($baditems);$i++)
	{
		$baditemstext .= $baditems[$i]." ";
	}
	
	$data = array(
	"creattime" => $issuedate,
	"creater" => $_SESSION['userinfo']['ID'],
	"editor" => $_SESSION['userinfo']['ID'],
	"edittime" => $issuedate,
	"status" => "0"	
	);
	
	//向表：t_form_head插入数据
	$depbusi->insert("t_form_head",$data);
	$idt_form_head = mysql_insert_id();
	
	//echo $idt_form_head;
	
	$data2 = array(	"companyname" => $companyname,
					"idt_form_head" => $idt_form_head,
					"issuedate" => $issuedate,
					"manageno" => $_SESSION['depend_mgno'],
					"m_no" => $_POST['m_no'],
					"goods_cod" => $_POST['goods_cod'],
					"m_o_q" => $_POST['m_o_q'],
					"solder" => $_POST['solder'],
					"serialno" => $_POST['serialno'],
					"dependnum" => $_POST['dependnum'],
					"project" => $_POST['project'],
					"baditems" => $baditemstext,
					"baditemstext" => $baditemstext,
					"baddetails" => $_POST['baddetails'],
					"swaprecord" => $_POST['swaprecord'],
					"delflag" => "0",
					"idm_company" => $_SESSION['userinfo']['idm_company']
	);
					

	
	
	
	//向表t_dpend_form插入数据
	$depbusi->insert("t_depend_form", $data2);
    echo "<script language='javascript'>";
	echo "location='irai_nr_show.php?module=show&idt_form_head=".$idt_form_head."'";
	echo "</script>";

}
else if($_REQUEST['module'] == "requren")
{
	//编辑后再确认
	$baditems = $_POST['baditems'];   
	for($i=0;$i< count($baditems);$i++)
	{
		$baditemstext .= $baditems[$i]." ";
	}
	
	$data = array(	"companyname" => $_REQUEST['companyname'],
				"idt_form_head" => $_REQUEST['idt_form_head'],
				"issuedate" => $_REQUEST['issuedate'],
				"manageno" => $_REQUEST['manageno'],
				"m_no" => $_POST['m_no'],
				"goods_cod" => $_POST['goods_cod'],
				"m_o_q" => $_POST['m_o_q'],
				"solder" => $_POST['solder'],
				"serialno" => $_POST['serialno'],
				"dependnum" => $_POST['dependnum'],
				"project" => $_POST['project'],
				"baditems" => $baditemstext,
				"baditemstext" => $baditemstext,
				"baddetails" => $_POST['baddetails'],
				"swaprecord" => $_POST['swaprecord'],
				"delflag" => "0"
	);
	
	
	$data2 = array("status" => "1");
	$depbusi->update("t_form_head", $data2, $_REQUEST['idt_form_head']);
	$depbusi->update("t_depend_form",$data,$_REQUEST['idt_form_head']);
	
	
	
    echo "<script language='javascript'>";
	echo "location='irai_nr_show.php?module=show&idt_form_head=".$_REQUEST['idt_form_head']."'";
	echo "</script>";
}
else if ($_REQUEST['module'] == "list")//一览
{
	echo "<script language='javascript'>";
	echo "location='irai_nr_list.php'";
	echo "</script>";
}
else if ($_REQUEST['module'] == "depart")//ajax
{	
	$id = $_REQUEST['m_no'];
	$data = $depbusi->searchProduct($id);
	foreach ($data as $key => $v)
	{
		 $response = $v[goods_cod];
	}	
	foreach ($data as $key => $v)
	{
		 $response .= ":".$v[m_o_q];
	}
	foreach ($data as $key => $v)
	{
		 $response .= ":".$v[issue_d];
	}
	echo $response;
}





?>