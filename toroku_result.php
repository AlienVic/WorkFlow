<?php
    require_once('base.php');
	require_once('SessionUtil.php');
	require_once('ConstUtil.php');
	require_once('TorokuBusiness.php');

	$massage = new TorokuBusiness();
	
	
	if($_REQUEST['module']=="new")
	{		
		TplConstTitle::setPageTitle($smarty, 'TorokuResultnew');
        TplConstTitle::setTplTitle($smarty, 'Toroku','Resultnew');
  		  
		$ID = $_REQUEST['ID'];
		
		$manageno=$massage->select_manageno($ID);
		
		$data = $manageno[0];	
		
		$smarty->assign("ID",$ID);
		$smarty->assign("data",$data);
		$smarty->display("toroku/toroku_result_new.tpl");
	}
    else if($_REQUEST['module']=="show")
    {
    	TplConstTitle::setPageTitle($smarty, 'TorokuResultShow');
        TplConstTitle::setTplTitle($smarty, 'Toroku','ResultShow');
        
        //echo "<pre>";
        //print_r($_FILES['picPath']);
        
        $file = $massage->select_file_id_name1($_REQUEST['ID']);
    	$idt_files = $file['ID'];
    	$filename  = $file['newfilename'];
    	
        $ID = $_REQUEST['ID'];
        
		if($_REQUEST['radio'] == 1)
		{
			if($_FILES['picPath']['name'] != NULL){
				
	        $name_head = date('YmdHis',time()).rand(0, 100); ///生成图片名字的时间和随机数
	        $name_end = $_FILES['picPath']['name'];
	    	$filename = $name_head.$_FILES['picPath']['name'];
	    	$upfile='./uploadfiles/torokuImage/'.$filename;
	    	//echo $upfile;
	    	move_uploaded_file($_FILES['picPath']['tmp_name'], $upfile);
	     	$idt_files = $massage->Insert_file($name_end,$filename);
	     	
    		}
    		
	    	$s=1;
		}
		else {
			$s=0;
			$idt_files=null;
		}
		
        $data = array(
        					"ID"=>$ID,
        					"instruction"=>$_REQUEST['textarea'],
							"attachmentflg"=>$s,
        					"idt_files_ins"=>$idt_files
        										     					
        			 );
        $rs = $massage->Update1($data);

        $manageno=$massage->select_manageno($ID);
		$manageno = $manageno[0];	
		
		//echo "<pre>";
		//print_r($data);
		$smarty->assign("filename",$filename);
		$smarty->assign("manageno",$manageno);
        $smarty->assign("data",$data);
        $smarty->display("toroku/toroku_result_show.tpl");
        
        
    }else if ($_REQUEST['module']=="showOK"){
    	    	
    	TplConstTitle::setPageTitle($smarty, 'TorokuResultShow');
        TplConstTitle::setTplTitle($smarty, 'Toroku','ResultShow');
         
        $ID = $_REQUEST['ID'];
       
        $data = $massage->select_result_message($ID);
        $data = $data[0];
        $smarty->assign("data",$data);
        $smarty->display("toroku/toroku_result_show_ok.tpl");
    	
    }else if($_REQUEST['module']=="edit"){
    	
    	TplConstTitle::setPageTitle($smarty, 'TorokuResultEdit');
        TplConstTitle::setTplTitle($smarty, 'Toroku','ResultEdit');
    	
    	$ID = $_REQUEST['ID'];
       
        $data = $massage->select_result_message($ID);
        $data = $data[0];
    	
        $smarty->assign("data",$data);
        $smarty->display("toroku/toroku_result_edit.tpl");
    	
    }else if($_REQUEST['module']=="sub")
    {        

    	    $id = $massage->Updata_head($_GET['ID2'],4);
    	    $id = $massage->Insert_massage5($_GET['ID2'], $_SESSION['userinfo']['idm_company']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],2,0);
    		
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    }





?>