<?php

require_once('base.php');
require_once('ToldBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');
require_once('FilesOperateBusiness.php');

$smarty = new Smarty();
$told = new ToldBusiness();
$filesoperatebusiness = new FilesOperateBusiness();

$pdfpart=$told->uploadpath;

//$comtype = ConstUtil::getConstArray("CompanyType");
$category=ConstUtil::getConstArray('Category');
$referrange=ConstUtil::getConstArray('Referrange');

TplConstTitle::setPageTitle($smarty, 'told');

$Year = date('Y',time());
$Month = date('m',time());
$Day = date('d',time());
$smarty->assign("Year",$Year);
$smarty->assign("Month",$Month);
$smarty->assign("Day",$Day);

$smarty->assign('category', $category);
$smarty->assign('referrange', $referrange);




/**
 * 告知的List画面
 */
if(empty($_REQUEST['module']) || $_REQUEST['module']=="list")
{
	TplConstTitle::setTplTitle($smarty, 'told','List');
	$data = $told -> search();
    $length = count($data);
	
	while ($length){
			$data[$length-1]['creattime'] = substr($data[0]['creattime'], 0,4)."年"
    						.substr($data[0]['creattime'],5,2)."月"
    						.substr($data[0]['creattime'],8,2)."日";
    	$length--;
	}
	//echo "<pre>";
	//print_r($data);
 
	$smarty->assign("toldList",$data);
	$smarty->display('told/told_list.tpl');

}
/**
 * 新规初始化页面
 */
if ($_REQUEST['module'] == "ini")
{
	TplConstTitle::setTplTitle($smarty, 'told','New');
	$data['creattime'] = $Year."年".$Month."月".$Day."日";
	$person = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$departname = $_SESSION['userinfo']['departname'];
	$data['lander'] = $departname.":".$person;
	$data['referrange'] = $told->getcompanyname();
	//echo "<pre>";
	//print_r($data);
	$smarty->assign('data',$data);
	$smarty->display('told/told_new.tpl');
	
}

/**
 * 对list进行逻辑删除
 */

if ($_REQUEST['module'] == "del")
{	
	$told -> DeleteLogical($_REQUEST['id']);	
	header('X-JSON: ' . createJonSuccess('deleteSuccess', null));

}
/**
 * 对list进行编辑修改
 */
if($_REQUEST['module'] == "edit")
{
	TplConstTitle::setTplTitle($smarty, 'told','Edit');

	$id = $_REQUEST['id'];
	
	
	$data = $told->search($id);
	$name=$told->search_user($data[0]['creater']);
	$file = $told->search_file($id);
	$data[0]['creattime'] = substr($data[0]['creattime'], 0,4)."年"
    						.substr($data[0]['creattime'],5,2)."月"
    						.substr($data[0]['creattime'],8,2)."日";

	$data[0]['lander'] = $name[0]['departname'].":".$name[0]['username1'].$name[0]['username2'];
	$data[0]['edittime'] = $Year."年".$Month."月".$Day."日";
	$data[0]['period_from'] = substr($data[0]['period_from'], 0,10);
    $data[0]['period_to'] = substr($data[0]['period_to'], 0,10);
    $smarty->assign('file', $file[0]);
	$smarty->assign('data', $data[0]);
	$smarty->display('told/told_new.tpl');
}


if ($_REQUEST['module'] == "data")//插入数据用
{
	//追加完毕，回到List
	$id = $_POST['id'];
	$data = $_POST['told'];
		
	if (empty($id))
	{
		$upfile= './uploadfiles/'.date('YmdGis');
		$do = move_uploaded_file($_FILES['myFile0']['tmp_name'],$upfile);
		if (!$do)  echo 'failed!';

		//向表t_file插入数据
		$idt_files = $told->Insert_file($upfile,$_FILES['myFile0']['tmp_name']);
		
		$data['creattime'] = $Year."-".$Month."-".$Day;
		$data['creater'] = $_SESSION['userinfo']['username1']+$_SESSION['userinfo']['username2'];
		$data['idt_files'] = $idt_files;
		$data['delflag']=0;
		
		//echo "<pre>";
		//print_r($data);
		$told->Noticeinsert($data);
		$id = mysql_insert_id();
						
		echo "<script language='javascript'>";
		echo "location='told_list.php?module=show&id=$id';";
		echo "</script>";
			
	}
	else
	{
		$upfile= './uploadfiles/'.date('YmdGis');
		$do = move_uploaded_file($_FILES['myFile0']['tmp_name'],$upfile);
		if (!$do)  echo 'failed!';
		$fileName = date('YmdGis').'told';
		$idt_files = $told->Insert_file($_FILES['myFile0']['tmp_name'],$fileName);
		$data['id'] = $_POST['id'];
		$data['editor'] = $_SESSION['userinfo']['username1']+$_SESSION['userinfo']['username2'];
		$data['edittime'] = $Year."-".$Month."-".$Day;
		$data['idt_files'] = $idt_files;
		
		$told->Update($data);
		echo "<script language='javascript'>";
		echo "location='told_list.php';";
		echo "</script>";
	}
		
	
}

if ($_REQUEST['module'] == "show")
{
	TplConstTitle::setTplTitle($smarty, 'told','Show');
	$id = $_REQUEST['id'];
	$data = $told->search($id);
	$name=$told->search_user($data[0]['creater']);
	$file = $told->search_file($id);
	$data[0]['lander'] = $name[0]['departname'].":".$name[0]['username1'].$name[0]['username2'];
	$data[0]['creattime'] = substr($data[0]['creattime'], 0,4)."年"
    						.substr($data[0]['creattime'],5,2)."月"
    						.substr($data[0]['creattime'],8,2)."日";
    $data[0]['period_from'] = substr($data[0]['period_from'], 0,4)."年"
    						.substr($data[0]['period_from'],5,2)."月"
    						.substr($data[0]['period_from'],8,2)."日";
    $data[0]['period_to'] = substr($data[0]['period_to'], 0,4)."年"
    						.substr($data[0]['period_to'],5,2)."月"
    						.substr($data[0]['period_to'],8,2)."日";
	//$step = $_REQUEST['s'];
	//echo "<pre>";
	//print_r($data[0]);
	$smarty->assign('file', $file[0]);
	$smarty->assign('data', $data[0]);
	//$smarty->assign('step', $step);
    $smarty->display('told/told_show.tpl');
}
if ($_REQUEST['module'] == 'download')
{
	if(isset($_GET["id"]) && $_GET["id"] > 0)
	{
		$info = $filesoperatebusiness->getFileInfo($_GET["id"]);
		FilesOperateBusiness::downloadFile(UPLOAD_FILES_PATH.$info["oldfilename"], $info["newfilename"]);
	}
	break;
}



?>