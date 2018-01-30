<?php
require_once('base.php');

require_once('SetumeiBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');

$smarty = new Smarty();
$setumei = new SetumeiBusiness();

TplConstTitle::setPageTitle($smarty, 'Setumei');
$Year = date('Y',time());
$Month = date('m',time());
$Day = date('d',time());

for($i=2012;$i<=2020;$i++)
{
	$year[] = $i;
}
for($i=1;$i<=12;$i++)
{
	$month[] = $i;
}
for($i=1;$i<=31;$i++)
{
	$day[] = $i;
}

$smarty->assign("year",$year);
$smarty->assign("month",$month);
$smarty->assign("day",$day);

$nowtime=$Year."年".$Month."月".$Day."日";
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

	$smarty->assign('data', $data);
	$smarty->display('setumei/setumei_shibu_list.tpl');
}
else if($_REQUEST['module'] == "new")
{
	$comid = $_REQUEST['company'];
	$headtext = ConstUtil::getManageNoHead('1');
	$resonse = $setumei->getManaNo_byId($comid);
	
	 
	echo $comid.":".$headtext."-".$resonse;
	
}
//新规初始化页面
else if ($_REQUEST['module'] == "ini")
{
	$class=$_REQUEST['k'];
	if($class=='4')
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','New_1');
	}
	elseif($class=='5')
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','New_2');
	}
	elseif($class=='6')
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','New_3');
	}
	$companyname = $setumei->getcompanyname(0);
	//echo "<pre>";
	//print_r($companyname);
	$data['issuedate'] = $Year."年".$Month."月".$Day."日";
	$smarty->assign('k',$class);
	$smarty->assign('companyname',$companyname);
	$smarty->assign('data',$data);
	$smarty->display('setumei/setumei_syousai_new.tpl');
	
}

//确认按钮后对数据的处理
elseif ($_REQUEST['module'] == "data")
{
	//追加完毕，回到List
	$id = $_POST['id'];
	$data = $_POST['setumei'];
	$instructitems = $_POST['checkbox'];
	$arr = ConstUtil::getCheckbox();
	for($i=0;$i< count($instructitems);$i++)
	{
		$item .= $instructitems[$i].":";
		$instructitemstext .= $arr[$instructitems[$i]].":";
	}
	$item = substr($item, '0',strlen($item)-1);
	$instructitemstext = substr($instructitemstext, '0',strlen($instructitemstext)-1);
	
	$k = $_REQUEST['k'];
	if (empty($id))
	{
		$filename=date('YmdGis')."setumei";
		
		
		$upfile= './uploadfiles/'.$filename;
		$do = move_uploaded_file($_FILES['files']['name'],$filename);
		if (!$do)  echo 'failed!';
		$data2 = array(
						"creattime" => $Year."-".$Month."-".$Day,
						"creater" => $_SESSION["userinfo"]["ID"],
						"status" => "0"	
						);
		//向表：t_form_head插入数据
		$setumei->insert("t_form_head",$data2);
		$idt_form_head = mysql_insert_id();
		
		//向表t_file插入数据
		$idt_files = $setumei->Insert_file($_FILES['files']['name'],$filename);
		
		//向表：t_instruct_form插入数据
		$data['idt_files'] = $idt_files;
		$data['manageno'] = $_POST['rs_manageno'];
		$data['companyname'] = $_POST['comname'];
		$data['idm_company'] = $_POST['companyname'];
		$data['issuedate'] = $Year."-".$Month."-".$Day;
		$data['expiredate'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
		$data['instructitems'] = $item;
		$data['sampledate'] = $_POST['y2']."-".$_POST['m2']."-".$_POST['d2'];
		$data['instructitemstext'] = $instructitemstext;
		$data['idt_form_head'] = $idt_form_head;
		$data['idm_urls'] = $k;
		$data['delflag']=0;
		//echo "<pre>";
		//print_r($data);	
		$setumei->insert('t_instruct_form',$data);
		$id = mysql_insert_id();
		
		
		//向表:t_instruct_form_content插入数据
		$data3['idt_instruct_form'] = $id;
		$data3['content'] .= $_POST['instruct_content_1'].'/'.$_POST['instruct_content_2'].'/'.$_POST['instruct_content_3'];
		$setumei->insert('t_instruct_form_content',$data3);
		//跳转至show画面
		echo "<script language='javascript'>";
		echo "location='setumei_syousai.php?module=show&s=1&k=$k&id=$id';";
		echo "</script>";
			
	}
	else
	{
		$id = $_POST['id'];
		$filename=date('YmdGis')."setumei";
		$upfile= './uploadfiles/'.$filename;
		$do = move_uploaded_file($_FILES['files']['name'],$filename);
		//if (!$do)  echo 'failed!';
		
		$data2 = array(
						"id" => $_POST['idt_form_head'],
						"editor" => $_SESSION["userinfo"]["ID"],
						"edittime" => $Year."-".$Month."-".$Day,
						"status" => "0"	
						);
		
		
		$data['id'] = $_POST['id'];
		$data['manageno'] = $_POST['rs_manageno'];
		$data['expiredate'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
		$data['instructitems'] = $item;
		$data['sampledate'] = $_POST['y2']."-".$_POST['m2']."-".$_POST['d2'];
		$data['instructitemstext'] = $instructitemstext;		
		
		$data3['idt_instruct_form'] = $id;
		$data3['content'] = $_POST['instruct_content'];
		
		$setumei->Update($data);
		$setumei->updata_file($_POST['idt_files'],$_FILES['files']['name'],$filename);
		$setumei->updata_all('t_form_head',$data2);
		$setumei->updata_all('t_instruct_form_content', $data3);
		//$setumei->overflow('1',$_POST['id'], $_POST['id_form_head']);
		
		
		echo "<script language='javascript'>";
		echo "location='setumei_syousai.php?module=show&s=1&k=$k&id=$id';";
		echo "</script>";
	}	
}


//指示书内容确认画面
elseif($_REQUEST['module'] == "show")
{
	$class = $_REQUEST['k'];
	if($class==4)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Show_1');
	}
	elseif($class==5)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Show_2');
	}
	elseif($class==6)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Show_3');
	}
	
	$step = $_REQUEST['s'];
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$file = $setumei->search_file($id);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    $data[0]['expiredate'] = substr($data[0]['expiredate'], 0,4)."年"
    						.substr($data[0]['expiredate'],5,2)."月"
    						.substr($data[0]['expiredate'],8,2)."日";
    $data[0]['sampledate'] = substr($data[0]['sampledate'], 0,4)."年"
    						.substr($data[0]['sampledate'],5,2)."月"
    						.substr($data[0]['sampledate'],8,2)."日";
	//echo "<pre>";
	//print_r($data);
	$smarty->assign('file',$file[0]);
	$smarty->assign('data', $data[0]);
	$smarty->assign('step', $step);
    $smarty->display('setumei/setumei_syousai_show.tpl');
}

//指示书内容编辑画面
elseif ($_REQUEST['module'] == "edit")
{
	$class=$_REQUEST['k'];
	if($class==4)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Edit_1');
	}
	elseif($class==5)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Edit_2');
	}
	elseif($class==6)
	{
		TplConstTitle::setTplTitle($smarty, 'Setumei','Edit_3');
	}
	
	
	
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$str = $data[0]['instructitems'];
	$v = split(':', $str);
	$able = array();		
	foreach($v as $key => $val)
	{
		$able[$val] = "1";
	}
	$content = split('/', $data[0]['content']);
	
	$file = $setumei->search_file($id);
	$setumei->setCheckbox($smarty, $data[0][istructitemstext]);
	$companyname = $setumei->getcompanyname(0);
	$data[0]['companyname'] = $_SESSION["userinfo"]["companyname"];
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
	$data[0]['year'] = substr($data[0]['expiredate'], 0,4);
    $data[0]['month'] = substr($data[0]['expiredate'],5,2);
    $data[0]['day'] = substr($data[0]['expiredate'],8,2);
    $data[0]['year2'] = substr($data[0]['sampledate'], 0,4);
    $data[0]['month2'] = substr($data[0]['sampledate'],5,2);
    $data[0]['day2'] = substr($data[0]['sampledate'],8,2);
    
	$smarty->assign('able',$able);
	$smarty->assign('content',$content);
	$smarty->assign('file',$file[0]);
    $smarty->assign('data',$data[0]);
    $smarty->assign('companyname',$companyname);
	$smarty->display('setumei/setumei_syousai_new.tpl');
}


//指示书ETA确认画面
elseif($_REQUEST['module'] == "E_quren")
{
	$affirmantperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];			
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$file = $setumei->search_file($id);
	$companyname = $setumei->getcompanyname(0);
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$str = $data[0]['instructitems'];
	$v = split(':', $str);
	$able = array();		
	foreach($v as $key => $val)
	{
		$able[$val] = "1";
	}
	$content = split('/', $data[0]['content']);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
	
	$data[0]['year'] = substr($data[0]['expiredate'], 0,4);
    $data[0]['month'] = substr($data[0]['expiredate'],5,2);
    $data[0]['day'] = substr($data[0]['expiredate'],8,2);
    $data[0]['year2'] = substr($data[0]['sampledate'], 0,4);
    $data[0]['month2'] = substr($data[0]['sampledate'],5,2);
    $data[0]['day2'] = substr($data[0]['sampledate'],8,2);
    //$data[0][]=explode(' ',$data[0]['']);
    
	$smarty->assign('file',$file[0]);
	$smarty->assign('able',$able);
	$smarty->assign('content',$content);
	$smarty->assign('nowtime',$nowtime);
	$smarty->assign('affirmantperson',$affirmantperson);
	$smarty->assign('data',$data[0]);
	$smarty->assign('companyname',$companyname);
	$smarty->display('setumei/setumei_syousai_quren.tpl');
}


//指示书ETA承认画面
elseif($_REQUEST['module'] == "E_chren")
{
	$approveperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$file = $setumei->search_file($id);
	$companyname = $setumei->getcompanyname(0);
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$str = $data[0]['instructitems'];
	$v = split(':', $str);
	$able = array();		
	foreach($v as $key => $val)
	{
		$able[$val] = "1";
	}
	$content = split('/', $data[0]['content']);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
	$data[0]['year'] = substr($data[0]['expiredate'], 0,4);
    $data[0]['month'] = substr($data[0]['expiredate'],5,2);
    $data[0]['day'] = substr($data[0]['expiredate'],8,2);
    $data[0]['year2'] = substr($data[0]['sampledate'], 0,4);
    $data[0]['month2'] = substr($data[0]['sampledate'],5,2);
    $data[0]['day2'] = substr($data[0]['sampledate'],8,2);
    $chrentime = $Year."年".$Month."月".$Day."日";
    $idt_form_head = $data[0]['idt_form_head'];
    $data4 = $setumei->getformhead($idt_form_head);
    
    $smarty->assign('data4',$data4[0]);
    $smarty->assign('file',$file[0]);
    $smarty->assign('able',$able);
	$smarty->assign('content',$content);
    $smarty->assign('nowtime',$nowtime);
	$smarty->assign('approveperson',$approveperson);
	$smarty->assign('data',$data[0]);
	$smarty->assign('companyname',$companyname);
	$smarty->display('setumei/setumei_syousai_chren.tpl');
}

//指示书协力会社担当画面
elseif($_REQUEST['module'] == "X_dang")
{
	TplConstTitle::setTplTitle($smarty, 'Setumei','Show');
	$bacceptperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$file = $setumei->search_file($id);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    $data[0]['expiredate'] = substr($data[0]['expiredate'], 0,4)."年"
    						.substr($data[0]['expiredate'],5,2)."月"
    						.substr($data[0]['expiredate'],8,2)."日";
    $data[0]['sampledate'] = substr($data[0]['sampledate'], 0,4)."年"
    						.substr($data[0]['sampledate'],5,2)."月"
    						.substr($data[0]['sampledate'],8,2)."日";
    $data4 = $setumei->getformhead($data[0][idt_form_head]);
    $data4[0]['affirmanttime'] = substr($data4[0]['affirmanttime'], 0,4)."年"
    							.substr($data4[0]['affirmanttime'],5,2)."月"
    							.substr($data4[0]['affirmanttime'],8,2)."日";
    $data4[0]['approvetime'] = substr($data4[0]['approvetime'], 0,4)."年"
    							.substr($data4[0]['approvetime'],5,2)."月"
    							.substr($data4[0]['approvetime'],8,2)."日";
    $data4[0]['affirmantperson'] = $setumei->getusername($data4[0]['affirmantperson']);
    $data4[0]['approveperson'] = $setumei->getusername($data4[0]['approveperson']);
    $smarty->assign('data4',$data4[0]);						
    $smarty->assign('file',$file[0]);
    $smarty->assign('nowtime',$nowtime);
	$smarty->assign('bacceptperson',$bacceptperson);
	$smarty->assign('data',$data[0]);
	$smarty->display('setumei/setumei_syousai_show_ok_ok.tpl');
}

//指示书协力会社确认画面
elseif($_REQUEST['module'] == "X_queren")
{
	$bconfirmperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$data2 = $setumei->search_instruct($id);
	$file = $setumei->search_file($id);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    $data[0]['expiredate'] = substr($data[0]['expiredate'], 0,4)."年"
    						.substr($data[0]['expiredate'],5,2)."月"
    						.substr($data[0]['expiredate'],8,2)."日";
    $data[0]['sampledate'] = substr($data[0]['sampledate'], 0,4)."年"
    						.substr($data[0]['sampledate'],5,2)."月"
    						.substr($data[0]['sampledate'],8,2)."日";
    $idt_form_head = $data[0]['idt_form_head'];
    $data4 = $setumei->getformhead($idt_form_head);
    $data4[0]['affirmanttime'] = substr($data4[0]['affirmanttime'], 0,4)."年"
    							.substr($data4[0]['affirmanttime'],5,2)."月"
    							.substr($data4[0]['affirmanttime'],8,2)."日";
    $data4[0]['approvetime'] = substr($data4[0]['approvetime'], 0,4)."年"
    							.substr($data4[0]['approvetime'],5,2)."月"
    							.substr($data4[0]['approvetime'],8,2)."日";
    $data4[0]['baccepttime'] = substr($data4[0]['baccepttime'], 0,4)."年"
    							.substr($data4[0]['baccepttime'],5,2)."月"
    							.substr($data4[0]['baccepttime'],8,2)."日";
    $data4[0]['affirmantperson'] = $setumei->getusername($data4[0]['affirmantperson']);
    $data4[0]['approveperson'] = $setumei->getusername($data4[0]['approveperson']);
    $data4[0]['bacceptperson'] = $setumei->getusername($data4[0]['bacceptperson']);
    
    $smarty->assign('data4',$data4[0]);
	$smarty->assign('file',$file[0]);
	$smarty->assign('nowtime',$nowtime);
	$smarty->assign('bconfirmperson',$bconfirmperson);
	$smarty->assign('data', $data[0]);
	$smarty->assign('data2', $data2[0]);
	$smarty->display('setumei/setumei_result_queren.tpl');
}

//指示书协力会社承认画面
elseif($_REQUEST['module'] == "X_chren")
{
	$badmitperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$id = $_REQUEST['id'];
	$idt_form_head = $_REQUEST['idt_form_head'];
	$data = $setumei->search($id);
	$data2 = $setumei->search_instruct($id);
	$file = $setumei->search_file($id);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    $data[0]['expiredate'] = substr($data[0]['expiredate'], 0,4)."年"
    						.substr($data[0]['expiredate'],5,2)."月"
    						.substr($data[0]['expiredate'],8,2)."日";
    $data[0]['sampledate'] = substr($data[0]['sampledate'], 0,4)."年"
    						.substr($data[0]['sampledate'],5,2)."月"
    						.substr($data[0]['sampledate'],8,2)."日";
    $idt_form_head = $data[0]['idt_form_head'];
    $data4 = $setumei->getformhead($idt_form_head);
    
    
    $data4[0]['affirmanttime'] = substr($data4[0]['affirmanttime'], 0,4)."年"
    							.substr($data4[0]['affirmanttime'],5,2)."月"
    							.substr($data4[0]['affirmanttime'],8,2)."日";
    $data4[0]['approvetime'] = substr($data4[0]['approvetime'], 0,4)."年"
    							.substr($data4[0]['approvetime'],5,2)."月"
    							.substr($data4[0]['approvetime'],8,2)."日";
    $data4[0]['baccepttime'] = substr($data4[0]['baccepttime'], 0,4)."年"
    							.substr($data4[0]['baccepttime'],5,2)."月"
    							.substr($data4[0]['baccepttime'],8,2)."日";
    $data4[0]['bconfirmtime'] = substr($data4[0]['bconfirmtime'], 0,4)."年"
    							.substr($data4[0]['bconfirmtime'],5,2)."月"
    							.substr($data4[0]['bconfirmtime'],8,2)."日";
    $data4[0]['affirmantperson'] = $setumei->getusername($data4[0]['affirmantperson']);
    $data4[0]['approveperson'] = $setumei->getusername($data4[0]['approveperson']);
    $data4[0]['bacceptperson'] = $setumei->getusername($data4[0]['bacceptperson']);
    $data4[0]['bconfirmperson'] = $setumei->getusername($data4[0]['bconfirmperson']);
	
    $smarty->assign('file',$file[0]);
	$smarty->assign('data', $data[0]);
	$smarty->assign('data2', $data2[0]);
	$smarty->assign('data4',$data4[0]);
	$smarty->assign('nowtime',$nowtime);
	$smarty->assign('badmitperson',$badmitperson);
	$smarty->display('setumei/setumei_result_chren.tpl');
}

//指示书ETA会社担当画面
elseif($_REQUEST['module'] == "E_dang")
{
	$endperson = $_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2'];
	$id = $_REQUEST['id'];
	$data = $setumei->search($id);
	$data2 = $setumei->search_instruct($id);
	$file = $setumei->search_file($id);
	$data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    $data[0]['expiredate'] = substr($data[0]['expiredate'], 0,4)."年"
    						.substr($data[0]['expiredate'],5,2)."月"
    						.substr($data[0]['expiredate'],8,2)."日";
    $data[0]['sampledate'] = substr($data[0]['sampledate'], 0,4)."年"
    						.substr($data[0]['sampledate'],5,2)."月"
    						.substr($data[0]['sampledate'],8,2)."日";
    $idt_form_head = $data[0]['idt_form_head'];
    $data4 = $setumei->getformhead($idt_form_head);
    $data4[0]['badmittime'] = substr($data4[0]['badmittime'], 0,4)."年"
    							.substr($data4[0]['badmittime'],5,2)."月"
    							.substr($data4[0]['badmittime'],8,2)."日";
    $data4[0]['baccepttime'] = substr($data4[0]['baccepttime'], 0,4)."年"
    							.substr($data4[0]['baccepttime'],5,2)."月"
    							.substr($data4[0]['baccepttime'],8,2)."日";
    $data4[0]['bconfirmtime'] = substr($data4[0]['bconfirmtime'], 0,4)."年"
    							.substr($data4[0]['bconfirmtime'],5,2)."月"
    							.substr($data4[0]['bconfirmtime'],8,2)."日";
    $data4[0]['badmitperson'] = $setumei->getusername($data4[0]['badmitperson']);
    $data4[0]['bacceptperson'] = $setumei->getusername($data4[0]['bacceptperson']);
    $data4[0]['bconfirmperson'] = $setumei->getusername($data4[0]['bconfirmperson']);
    $smarty->assign('data4',$data4[0]);						
	$smarty->assign('file',$file[0]);
	$smarty->assign('data', $data[0]);
	$smarty->assign('data2', $data2[0]);
	$smarty->assign('nowtime',$nowtime);
	$smarty->assign('endperson',$endperson);
	$smarty->display('setumei/setumei_result_dang.tpl');
}


?>