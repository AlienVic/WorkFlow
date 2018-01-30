<?php
require_once('base.php');

require_once('SetumeiBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');

$smarty = new Smarty();
$setumei = new SetumeiBusiness();

TplConstTitle::setPageTitle($smarty, 'Setumei');
TplConstTitle::setTplTitle($smarty, 'Setumei','List');
$status=ConstUtil::getConstArray('Status');
$comtype = ConstUtil::getConstArray("CompanyType");

$Year = date('Y',time());
$Month = date('m',time());
$Day = date('d',time());
$smarty->assign("Year",$Year);
$smarty->assign("Month",$Month);
$smarty->assign("Day",$Day);
$smarty->assign('status', $status);

if(empty($_REQUEST['module']) || $_REQUEST['module']=="list")
{
	TplConstTitle::setTplTitle($smarty, 'Setumei','List');
	$ID = $_SESSION['userinfo']['ID'];
	$data = $setumei->search_setumei($ID);
    $length = count($data);
	
	while ($length){
			$data[$length-1]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";           
    	$length--;
	}
	$right = $_SESSION['userinfo']['userright'];
	$c_type = $setumei->select_cType($_SESSION['userinfo']['idm_company']);
	
	$smarty->assign("type",$c_type[0][0]);
	$smarty->assign("right",$right);
	$smarty->assign('data', $data);
	
	//echo $right;
	//echo "<pre>";
	//print_r($data);
	$smarty->display('setumei/setumei_shibu_list.tpl');
}

elseif ($_REQUEST['module'] == "show")
{
	$step = $_REQUEST['s'];
	$ID = $_POST['id'];
	if($step == 1)
	{
		$data['id'] = $_POST['idt_form_head'];
		$data['confirmtime'] = date('Y-m-d H:i:s',time());
		$data['status'] = '1';
		$setumei->updata_all('t_form_head', $data);
		echo "<script language='javascript'>";
		echo "location='setumei_syousai.php?module=show&s=2&id=$ID';";
		echo "</script>";
		
	}
	elseif($step == 2)
	{
		$data['id'] = $_POST['idt_form_head'];
		$data['contacttime'] = date('Y-m-d H:i:s',time());
		$data['status'] = '2';
		$setumei->updata_all('t_form_head', $data);
		$setumei->send_Message($_POST['idt_form_head'], '2', '0');			//发送消息 sendstatus=2
		
		echo "<script language='javascript'>";
		echo "location='setumei_shibu.php?module=list';";
		echo "</script>";
	}
	
}


elseif($_REQUEST['module'] == "E_queren")//ETA确认
{
	$id = $_POST['id'];
	$data = $_POST['setumei'];
	$instructitems = $_POST['checkbox'];
	for($i=0;$i< count($instructitems);$i++)
	{
		$instructitemstext .= $instructitems[$i]." ";
	}
	
	$filename=date('YmdGis')."setumei";
	$upfile= './uploadfiles/'.$filename;
	$do = move_uploaded_file($_FILES['files']['name'],$filename);
	if (!$do)  echo 'failed!';
		
		
	$data['id'] = $_POST['id'];
	$data['expiredate'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
	$data['instructitems'] = intval($_POST['checkbox']);
	$data['sampledate'] = $_POST['y2']."-".$_POST['m2']."-".$_POST['d2'];
	$data['instructitemstext'] = $instructitemstext;		
		
		
	$data3['idt_instruct_form'] = $id;
	$data3['content'] = $_POST['instruct_content'];
		
	$setumei->Update($data);										//更改t_instruct_form表	
	$setumei->updata_file($_POST['idt_files'],$_FILES['files']['name'],$filename);
	$setumei->updata_all('t_instruct_form_content', $data3);
	$setumei->send_Message($_POST['idt_form_head'], '3', $_POST['E_queren']);						//发送消息 sendstatus=3
	
	//--------反写处理----------
	$idt_form_head = $_POST['idt_form_head'];
	if($_POST['E_queren']=='0')//确认ok时
	{
		//echo "aa";
		$setumei->overflow('4', $_POST['id'],$idt_form_head);
	}
	else//NG时
	{
		
		$data = array(
						"status"=>'0'
						);
		$setumei->update_s($data,"u.ID = '$idt_form_head'");
	}
	echo "<script language='javascript'>";
	echo "location='setumei_shibu.php?module=list';";
	echo "</script>";
}

elseif($_REQUEST['module'] == "E_chren")//ETA承认
{
	$id = $_POST['id'];
	$data = $_POST['setumei'];
	$instructitems = $_POST['checkbox'];
	for($i=0;$i< count($instructitems);$i++)
	{
		$instructitemstext .= $instructitems[$i]." ";
	}
	
	
	$filename=date('YmdGis')."setumei";
	$upfile= './uploadfiles/'.$filename;
	$do = move_uploaded_file($_FILES['files']['name'],$filename);
	if (!$do)  echo 'failed!';
		
		

		
		
	$data['id'] = $_POST['id'];
	$data['expiredate'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
	$data['instructitems'] = intval($_POST['checkbox']);
	$data['sampledate'] = $_POST['y2']."-".$_POST['m2']."-".$_POST['d2'];
	$data['instructitemstext'] = $instructitemstext;		
		
		
	$data3['idt_instruct_form'] = $id;
	$data3['content'] = $_POST['instruct_content'];
		
	//更改t_instruct_form表	
	$setumei->Update($data);
	$setumei->updata_all('t_instruct_form_content', $data3);
	$setumei->updata_file($_POST['idt_files'],$_FILES['files']['name'],$filename);
	$setumei->send_Message($_POST['idt_form_head'], '4', $_POST['E_chren']);			//发送消息 sendstatus=4
	
	//---------反写处理-------------
	$idt_form_head = $_POST['idt_form_head'];
	if($_POST['E_chren']=='0')//承认时
	{
		
		$setumei->overflow('5',$_POST['id'],$idt_form_head);						
	}
	else//却下时
	{
		$data = array(
						"status"=>'0'
						);
		$setumei->update_s($data,"u.ID = $idt_form_head");
	}
	echo "<script language='javascript'>";
	echo "location='setumei_shibu.php?module=list';";
	echo "</script>";
}

elseif ($_REQUEST['module'] == "X_dang") //协力受领
{
	$id = $_POST['id'];								
	//发送消息 sendstatus=5
	$setumei->send_Message($_POST['idt_form_head'], '5', $_POST['X_dang']);	
	//---------反写处理-------------
	$idt_form_head = $_POST['idt_form_head'];	
	if($_POST['E_queren']==0)
	{
		$setumei->overflow('6', $_POST['id'], $idt_form_head);
	}
	else
	{
		$data = array(
						"status"=>'0'
						);
		$setumei->update_s($data,"u.ID = '$idt_form_head'");
	}	
	echo "<script language='javascript'>";
	echo "location='setumei_result.php?module=new&id=$id';";
	echo "</script>";
	
	
}
elseif ($_REQUEST['module'] == "X_queren")
{
	$name_head = date('YmdHis',time()).rand(0, 100); ///生成图片名字的时间和随机数
    
    //echo $name_head.$_FILES['picPath']['name'];
    $name_end = $_FILES['picPath']['name'];
    $_FILES['picPath']['name'] = $name_head.$_FILES['picPath']['name'];
    $upfile='./Images/'.$_FILES['picPath']['name']; //////可以自定义存放的地址
    //echo $upfile;
    move_uploaded_file($_FILES['picPath']['tmp_name'], $upfile);
    echo "上传成功";
    
	
	//发送消息 sendstatus=6								
	$setumei->send_Message($_POST['idt_form_head'], '6', $_POST['X_queren']);	
	//-----------反写处理 番号No.7	----------------
	$idt_form_head = $_POST['idt_form_head'];	
	if($_POST['X_queren']=='0')
	{
		$setumei->overflow('7', $_POST['id'], $_POST['idt_form_head']);
	}
	else
	{
		$data = array(
						"status"=>'5'
						);
		$setumei->update_s($data,"u.ID = '$idt_form_head'");
	}	
	echo "<script language='javascript'>";
	echo "location='setumei_shibu.php?module=list';";
	echo "</script>";
	
	
}
elseif ($_REQUEST['module'] == "X_chren")
{
	$name_head = date('YmdHis',time()).rand(0, 100); ///生成图片名字的时间和随机数
    
    //echo $name_head.$_FILES['picPath']['name'];
    $name_end = $_FILES['picPath']['name'];
    $_FILES['picPath']['name'] = $name_head.$_FILES['picPath']['name'];
    $upfile='./Images/'.$_FILES['picPath']['name']; //////可以自定义存放的地址
    //echo $upfile;
    move_uploaded_file($_FILES['picPath']['tmp_name'], $upfile);
    echo "上传成功";
    
	
	//发送消息 sendstatus=7							
	$setumei->send_Message($_POST['idt_form_head'], '7', $_POST['X_chren']);
	//------------------反写处理 番号No.8	-----------------	
	$idt_form_head = $_POST['idt_form_head'];		
	if($_POST['X_chren']=='0')
	{
		$setumei->overflow('8', $_POST['id'], $idt_form_head);
	}
	else
	{
		$data = array(
						"status"=>'5'
						);
		$setumei->update_s($data,"u.ID =$idt_form_head");
	}
	echo "<script language='javascript'>";
	echo "location='setumei_shibu.php?module=list';";
	echo "</script>";
	
	
}
elseif ($_REQUEST['module'] == "E_dang")
{
	//反写处理 番号No.12		
	if($_POST['E_dang']=='0')
	{
		$setumei->overflow('12', $_POST['id'], $_POST['idt_form_head']);
	}
	else
	{}	
	echo "<script language='javascript'>";
	echo "location='setumei_shibu.php?module=list';";
	echo "</script>";
	
	
}
?>