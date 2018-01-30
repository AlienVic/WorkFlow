<?php

require_once('base.php');
require_once('SessionUtil.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once ('TorokuBusiness.php');




require_once('FilesOperateBusiness.php');

//TplConstTitle::setTplTitle($smarty, 'User','New');

$massage = new TorokuBusiness();

$Year = date('Y',time());
$Month = date('m',time());
$Day = date('d',time());
$date = date('Y-m-d H:i:s',time());

$company = $_SESSION['userinfo']['companyname'];
$tail = $massage->getManaNo($_SESSION['userinfo']['appreviation']);
$tail1 = $tail[0][0];
$head = ConstUtil::getManageNoHead(0);
$smarty->assign("No",$head."-".$tail1);



$smarty->assign("Year",$Year);
$smarty->assign("Month",$Month);
$smarty->assign("Day",$Day);
$smarty->assign("company",$company);



if($_GET['module']=="ajax"){
	$s = $massage->select_product($_GET['id']);
	foreach($s as $key => $value)
	{
		$n= $value['goods_cod'];  // AJAX的内容
	}
	if(!empty($n)){
    echo $n;
	}
	else{
		echo null;
	}
}

  
else if($_GET['module']=="sub"){  //提交内容，并且向数据库插入数据
	TplConstTitle::setPageTitle($smarty, 'TorokuShow');
    TplConstTitle::setTplTitle($smarty, 'Toroku','Show');

    //echo "<pre>";
    //print_r($_FILES['picPath']);
    $idt_files = NULL;
    $name_end = NULL;
	
    $id2 = $massage->Insert_head($_SESSION['userinfo']['ID'],$date);
    $id2 = $id2[0][0];
  
    
	if($_REQUEST['radio1']==28){
	
		$s=1;
		$stopline = $_REQUEST['textfield9'];
	}
	else {
	    $s=0;
	    $stopline = null;
	}
	if($_REQUEST['radio2']==30){
		
			if($_FILES['picPath']['name']!=NULL){
			    $name_head = date('YmdHis',time()).rand(0, 100); ///生成图片名字的时间和随机数
			    
			    //echo $name_head.$_FILES['picPath']['name'];
			    $name_end = $_FILES['picPath']['name'];
			    $_FILES['picPath']['name'] = $name_head.$_FILES['picPath']['name'];
			    $upfile='./uploadfiles/torokuImage/'.$_FILES['picPath']['name'];
			    //echo $upfile;
			    $a = move_uploaded_file($_FILES['picPath']['tmp_name'], $upfile);
			    $idt_files = $massage->Insert_file($name_end,$_FILES['picPath']['name']);
			}
			$t=1;

	}
	else {
		$t=0;
	}
    $data = array("manageno"=>$head."-".$tail1
    			  ,"idt_form_head"=>$id2
    			  ,"issuedate"=>$date
    			  ,"companyname"=>$company
    			  ,"idm_company"=>$_SESSION['userinfo']['idm_company']
                  ,"goods_cod"=>$_REQUEST['textfield10']
                  ,"m_no"=>$_REQUEST['textfield']
                  ,"partcode"=>$_REQUEST['textfield2']
                  ,"specification"=>$_REQUEST['textfield3']
                  ,"project"=>intval($_REQUEST['radio'])
                  ,"device"=>$_REQUEST['textarea']
                  ,"lotnum"=>intval($_REQUEST['textfield4'])
                  ,"badnum"=>intval($_REQUEST['textfield5'])
                  ,"uncheckednum"=>intval($_REQUEST['textfield6'])
                  ,"manualnum"=>intval($_REQUEST['textfield7'])
                  ,"badproportion"=>floatval($_REQUEST['textfield8'])
                  ,"stopline"=>$stopline
                  ,"stoplineflg"=>$s
                  ,"badcontent"=>$_REQUEST['textarea2']
                  ,"badpictureflg"=>$t
                  ,"idt_files"=>$idt_files
                  ,"delflag"=>0
    );

    
    $id1 = $massage->insert($data);   
    $id = $data["project"];
    $data = $massage->check_data($data,$id); //修改显示数据
    
    $smarty->assign("newname",$_FILES['picPath']['name']);
    $smarty->assign("id2",$id2);
    $smarty->assign("data",$data);
    $smarty->display("toroku/toroku_syousai_show.tpl");
    /*echo "<pre>";
    print_r($data);
    print_r($data['project']);
    var_dump($data['project']);*/

}



else if($_GET['module']=="updata"){ 
	TplConstTitle::setPageTitle($smarty, 'TorokuEdit');
    TplConstTitle::setTplTitle($smarty, 'Toroku','Edit');
    
    $file = $massage->select_file_id_name($_REQUEST['ID']);
    $idt_files = $file['ID'];
    $filename  = $file['newfilename'];
    
   
    //echo  $_FILES['picPath']['name'];
	//echo "<pre>";
	if($_REQUEST['radio1']==29){
	
		$s=0;
		$stopline = NULL;
	}
	else {
	    $s=1;
	    $stopline = $_REQUEST['textfield9'];
	}  
	if($_REQUEST['radio2']==30){
			 if($_FILES['picPath']['name']!=NULL){
				    $name_head = date('YmdHis',time()).rand(0, 100); ///生成图片名字的时间和随机数
				    //echo $name_head.$_FILES['picPath']['name'];
				    $name_end = $_FILES['picPath']['name'];
				    $filename = $name_head.$_FILES['picPath']['name'];
				    $upfile='./uploadfiles/torokuImage/'.$filename;
				    $idt_files = $massage->Insert_file($name_end,$filename);
				    //echo $upfile;
				    move_uploaded_file($_FILES['picPath']['tmp_name'], $upfile);

			  }
			  $t=1;

	}else {
		$t=0;
		$idt_files = NULL;
	}
		
    $data = array("ID"=>$_REQUEST['ID']
    			  ,"manageno"=>$head."-".$tail1
    			  ,"goods_cod"=>$_REQUEST['textfield10']
    			  ,"issuedate"=>$date
    			  ,"companyname"=>$company
                  ,"m_no"=>$_REQUEST['textfield']
                  ,"partcode"=>$_REQUEST['textfield2']
                  ,"specification"=>$_REQUEST['textfield3']
                  ,"project"=>intval($_REQUEST['radio'])
                  ,"device"=>$_REQUEST['textarea']
                  ,"lotnum"=>intval($_REQUEST['textfield4'])
                  ,"badnum"=>intval($_REQUEST['textfield5'])
                  ,"uncheckednum"=>intval($_REQUEST['textfield6'])
                  ,"manualnum"=>intval($_REQUEST['textfield7'])
                  ,"badproportion"=>floatval($_REQUEST['textfield8'])
                  ,"stopline"=>$stopline
                  ,"stoplineflg"=>$s
                  ,"badcontent"=>$_REQUEST['textarea2']
                  ,"badpictureflg"=>$t
                  ,"idt_files"=>$idt_files
                  ,"delflag"=>0
    );
    $id1 = $massage->Update1($data);   //更改
    $id = $data["project"];
    //print_r($id);
    $data = $massage->check_data($data,$id); //修改显示数据
    
    $smarty->assign("newname",$filename);
    $smarty->assign("id2",$_GET['id2']);
    $smarty->assign("data",$data);
    $smarty->display("toroku/toroku_syousai_show.tpl");
}




else if($_GET['module']=="ok")
{
	TplConstTitle::setPageTitle($smarty, 'TorokuShow');
    TplConstTitle::setTplTitle($smarty, 'Toroku','Show');
	//echo "<pre>";
	$m_no = $_GET['m_no'];
	
	$data1 = $massage->select_all($m_no);
	$data = $data1[0];
	$id = $data["project"];
    $data = $massage->check_data($data,$id);
    
    $smarty->assign("id2",$_GET['id2']);
	$smarty->assign("data",$data);
    $smarty->display("toroku/toroku_syousai_show_ok.tpl");
}



else if($_GET['module']=="edit")
{
	TplConstTitle::setPageTitle($smarty, 'TorokuEdit');
    TplConstTitle::setTplTitle($smarty, 'Toroku','Edit');
	
	$m_no = $_GET['m_no'];
	
	$data1 = $massage->select_all($m_no);
	$data = $data1[0];
    $data = $massage->check_data($data,100);
    
	$smarty->assign("data",$data);

	$smarty->assign("id2",$_GET['id2']);
    $smarty->display("toroku/toroku_syousai_edit.tpl");
	
}

else if($_GET['module']=="end"){
	$id = $massage->Update_head($date,$_GET['id2']);

	$id = $massage->Insert_massage($_GET['id2'], 
	                               $_SESSION['userinfo']['idm_company']);
	
	
	echo "<script language='javascript'>";
	echo "location='toroku_list.php';";
	echo "</script>";
}

else{

	TplConstTitle::setPageTitle($smarty, 'TorokuNew');
    TplConstTitle::setTplTitle($smarty, 'Toroku','New');
    
	$smarty->display("toroku/toroku_syousai_new.tpl");
}

?>