<?php
require_once('base.php');
require_once('helpBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');
require_once('FilesOperateBusiness.php');


$smarty = new Smarty();
$filesoperatebusiness = new FilesOperateBusiness();

$help = new helpBusiness();
TplConstTitle::setPageTitle($smarty, 'help');
//进入new页面
if (empty($_REQUEST['module']))
{	
	TplConstTitle::setTplTitle($smarty, 'help','list');
	$data1=$help->Search();
	//$data2=$help->Searchkey();
	$smarty->assign('data', $data1);
	$smarty->assign('data3', $data1);
	$smarty->display('help/help_list1.tpl');
}
else if($_REQUEST['module']=="new")
{
	TplConstTitle::setTplTitle($smarty, 'help','new');
	$smarty->display('help/help_new.tpl');
	
}

//从show页面返回时进入edit页面
else if($_REQUEST['module']=="edit")
{
	TplConstTitle::setTplTitle($smarty, 'help','edit');
	$id = $_REQUEST['id'];
	$data = $help->Search($id);
	$smarty->assign('data', $data[0]);
	$smarty->display('help/help_edit.tpl');
}
//新增文件后点击下一步
else if($_REQUEST['module']=="next")
{	
		TplConstTitle::setTplTitle($smarty, 'help','show');
		$fileName = date('YmdGis').'help';	
		$upfile= './uploadfiles/help/'.$fileName;
		//echo $upfile;
		$do = move_uploaded_file($_FILES['fileFiled']['tmp_name'],$upfile);
		//echo $_FILES['fileField']['tmp_name'];
		if (!$do)  
		{
			echo 'failed!';
			echo '请返回上一页';
			exit;
		}
		//$data = $_POST['data'];
		$data1['oldfilename']=$_FILES['fileFiled']['name'];
		$data1['newfilename']=$fileName;
		//echo "<pre>";
		//print_r($data1);
		$help->Insertfile($data1);
		//$data[idt_files] = mysql_insert_id();
		//print_r($data[id]);
		$data = $_POST['data'];
		$data[idt_files] = mysql_insert_id();
		$help->Inserthelp($data);
		$id=mysql_insert_id();
		$data3 = $help->Search($id);
		$smarty->assign('data', $data3[0]);//传到tpl
		$smarty->display('help/help_show.tpl');
		
}
//返回edit后点击下一步
else if($_REQUEST['module']=="update")
{	
		TplConstTitle::setTplTitle($smarty, 'help','show');
		$data=$_REQUEST['help'];
		$data2=$help->Search($data[id]);
		$data1['ID']=$data2[0][idt_files];
		//echo $data1[ID];
	   
		$fileName = date('YmdGis').'help';	
		$upfile= './uploadfiles/help/'.$fileName;
	//	echo $upfile;
		$do = move_uploaded_file($_FILES['fileFiled']['tmp_name'],$upfile);
		//echo $_FILES['fileField']['tmp_name'];
		if (!$do)  
		{
			echo 'failed!';
			echo '请返回上一页';
			exit;
		}
		$data1['oldfilename']=$_FILES['fileFiled']['name'];
		$data1['newfilename']=$fileName;
		//$data1['delflag']=0;
		$help->Update($data);
		$help->Updatefile($data1);
		$data3 = $help->Search($data[id]);
		$smarty->assign('data', $data3[0]);//传到tpl
		$smarty->display('help/help_show.tpl');
		
}
//点击帮助或show的确认按钮
else if($_REQUEST['module']=="yes")
{
	TplConstTitle::setTplTitle($smarty, 'help','list');
	$data1=$help->Search();
	//$data2=$help->Searchkey();
	$smarty->assign('data', $data1);
	$smarty->assign('data3', $data1);
	$smarty->display('help/help_list.tpl');
	
}
//点击lisi页面上的检索
else if($_REQUEST['module']=="find")
{
	//echo '123';
	$data1=$help->Search();
	$smarty->assign('data3', $data1);
	$chaxun = $_REQUEST['chaxun'];
	//echo $chaxun;
	$data=$help->searchkey($chaxun);
	//echo "<pre>";
	//print_r($data);
	$smarty->assign('data', $data);
	$smarty->display('help/help_list.tpl');
	
}
//点击下载
else if ($_REQUEST['module'] == 'download') 
{
	
	//echo $_GET['id'];
	if( isset($_GET['id']) && $_GET['id'] > 0)
	{	
		
		$info = $filesoperatebusiness->getFileInfo($_GET["id"]);
		FilesOperateBusiness::downloadFile(UPLOAD_FILES_HELP_PATH.$info["newfilename"], $info["oldfilename"]);
		
		
	}
	break;
}

?>