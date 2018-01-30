<?php
require_once('base.php');

require_once('SetumeiBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');

$smarty = new Smarty();
$setumei = new SetumeiBusiness();

if($_REQUEST['module']=="new")
{
	TplConstTitle::setTplTitle($smarty, 'Setumei','Depend_New');
	$id = $_REQUEST['id'];
	$data = $setumei->search_instruct($id);
		
	$smarty->assign('data', $data[0]);
	$smarty->display('setumei/setumei_result_new.tpl');	
}

elseif($_REQUEST['module']=="edit")
{
	$data = $setumei->search_instruct($id);
	$smarty->assign('data', $data[0]);
	$smarty->display('setumei/setumei_result_new.tpl');	
}
elseif($_REQUEST['module']=="data")
{
	$filename=date('YmdGis')."setumei";
	$upfile= './uploadfiles/'.$filename;
	$do = move_uploaded_file($_FILES['files']['name'],$filename);
	if (!$do)  echo 'failed!';
	$idt_files_ins = $setumei->Insert_file($_FILES['files']['name'],$filename);
	$id = $_POST['id'];
	$data['id'] = $_POST['id'];
	$data['instruction'] = $_POST['instruction'];
	$data['attachmentflg'] = $_POST['instru_con'];
	$data['idt_files_ins'] = $idt_files_ins;
	$setumei->Update($data);
	
	echo "<script language='javascript'>";
	echo "location='setumei_result.php?module=show&s=1&id=$id';";
	echo "</script>";
}
elseif($_REQUEST['module']=="show")
{
	$id = $_REQUEST['id'];
	$step = $_REQUEST['s'];
	$data = $setumei->search_instruct($id);
	$smarty->assign('data', $data[0]);
	$smarty->display('setumei/setumei_result_show.tpl');	
}

?>