<?php
    require_once('base.php');
	require_once('SessionUtil.php');
	require_once('ConstUtil.php');
	require_once('TorokuBusiness.php');

    $massage = new TorokuBusiness();
    

    if($_GET['module']=="X")
    {
    	if($_GET['admit']=="1")
    	{
    		   
    		  $sign = 0;
    		  TplConstTitle::setPageTitle($smarty, 'TorokuQuren');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Quren');
  		  
    		  $ID = $_GET['ID'];
    		  $img = $massage->check_img($ID);
    		  
    		  $data  = $massage->select_all_admin($ID,1,$img);
    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0]; 
              $data = $massage->check_data($data,100);
              $user = $massage->user_message();
              
              //echo "<pre>";
              //print_r($data);
			  //print_r($user);
              
              $smarty->assign("sign",$sign);
              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
    		  $smarty->display("toroku/toroku_admit.tpl");
    	}
    	else if($_GET['admit']=="2"){
    		
    	      $sign = 1;

    		  TplConstTitle::setPageTitle($smarty, 'TorokuQuren');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Quren');
  		  
    		  $ID = $_GET['ID'];
    		  $img = $massage->check_img($ID);
    		  $data  = $massage->select_all_admin($ID,1,$img);

    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0]; 
              $data = $massage->check_data($data,100);
              $user = $massage->user_message();
              $last_user = $massage->get_last_user_message($ID);
              
              
              $last_user[0]['affirmanttime'] = substr($last_user[0]['affirmanttime'], 0,4)."年"
    						.substr($last_user[0]['affirmanttime'],5,2)."月"
    						.substr($last_user[0]['affirmanttime'],8,2)."日";
              $last_user[0]['username1'] = $last_user[0]['username1'].$last_user[0]['username2'];
              $last_user = $last_user[0]; 
            
    		  $smarty->assign("sign",$sign);
              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
              $smarty->assign("last_user",$last_user);
    		  $smarty->display("toroku/toroku_admit.tpl");
    	}else if($_GET['admit']=="0"){
    		
    	      
    		  TplConstTitle::setPageTitle($smarty, 'TorokuShoulin');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Shoulin');
  		  
    		  $ID = $_GET['ID'];
    		  $img = $massage->check_img($ID);
    		  
    		  $data  = $massage->select_all_admin($ID,2,$img);
    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0];
              $data = $massage->check_data($data,$data['project']);
              $user = $massage->user_message();
              $accept_user = $massage->get_EAT_user_message($ID,0);
              $confirm_user = $massage->get_EAT_user_message($ID,1);
              $admit_user = $massage->get_EAT_user_message($ID,2);

              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
              $smarty->assign("accept_user", $accept_user);
              $smarty->assign("confirm_user", $confirm_user);
              $smarty->assign("admit_user", $admit_user);
              
    		  $smarty->display("toroku/toroku_last.tpl");
    	}
    }else if ($_GET['module']=="E"){
    	
    	if($_GET['admit']=="0"){
    		
    		  TplConstTitle::setPageTitle($smarty, 'TorokuShoulin');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Shoulin');
  		
    		  $ID = $_GET['ID'];
    		  
    		  
    		  
    		  $img = $massage->check_img($ID);
    		  $data  = $massage->select_all_admin($ID,1,$img);
    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0]; 
    		  $id = $data["project"];
              $data = $massage->check_data($data,$id);          
              
              $user = $massage->user_message();
              
              $last_user = $massage->get_last_user_message($ID);
              $last_user1 = $massage->get_last_user_message1($ID);
              
              $last_user[0]['affirmanttime'] = substr($last_user[0]['affirmanttime'], 0,4)."年"
    						.substr($last_user[0]['affirmanttime'],5,2)."月"
    						.substr($last_user[0]['affirmanttime'],8,2)."日";
              $last_user[0]['username1'] = $last_user[0]['username1'].$last_user[0]['username2'];
              $last_user = $last_user[0]; 
    		
              
              $last_user1[0]['approvetime'] = substr($last_user1[0]['approvetime'], 0,4)."年"
    						.substr($last_user1[0]['approvetime'],5,2)."月"
    						.substr($last_user1[0]['approvetime'],8,2)."日";
              $last_user1[0]['username1'] = $last_user1[0]['username1'].$last_user1[0]['username2'];
              $last_user1 = $last_user1[0]; 
              
              
              $smarty->assign("sign",$sign);
              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
              $smarty->assign("last_user",$last_user);
              $smarty->assign("last_user1",$last_user1);
    		  $smarty->display("toroku/toroku_accept.tpl");
    	}
    	else if($_REQUEST['admit']=="1"){
    		  
    		  $sign = 0;
    		  $ID = $_GET['ID'];
    		  
    		  
    		  TplConstTitle::setPageTitle($smarty, 'TorokuQuren');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Quren');
    		  
    		  
    		  $img = $massage->check_img($ID);
    		  $data  = $massage->select_all_admin($ID,2,$img);
    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0]; 
    		  $id = $data["project"];
              $data = $massage->check_data($data,$id);
    		  
              $user = $massage->user_message();
              $last_user = $massage->get_last_user_message($ID);
              $last_user1 = $massage->get_last_user_message1($ID);
              
              
                
              $last_user[0]['affirmanttime'] = substr($last_user[0]['affirmanttime'], 0,4)."年"
    						.substr($last_user[0]['affirmanttime'],5,2)."月"
    						.substr($last_user[0]['affirmanttime'],8,2)."日";
              $last_user[0]['username1'] = $last_user[0]['username1'].$last_user[0]['username2'];
              $last_user = $last_user[0]; 
    		
              
              $last_user1[0]['approvetime'] = substr($last_user1[0]['approvetime'], 0,4)."年"
    						.substr($last_user1[0]['approvetime'],5,2)."月"
    						.substr($last_user1[0]['approvetime'],8,2)."日";
              $last_user1[0]['username1'] = $last_user1[0]['username1'].$last_user1[0]['username2'];
              $last_user1 = $last_user1[0]; 
              
              
              $smarty->assign("sign",$sign);
              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
              $smarty->assign("last_user",$last_user);   
              $smarty->assign("last_user1",$last_user1);   

    		  $smarty->display("toroku/toroku_admit_eat.tpl");
    	}else if($_REQUEST['admit']=="2"){
    		  
    		  $sign = 1;
    		  $ID = $_GET['ID'];
    		  
    		  TplConstTitle::setPageTitle($smarty, 'TorokuQuren');
    		  TplConstTitle::setTplTitle($smarty, 'Toroku','Quren');
    		  
    		  
    		  $img = $massage->check_img($ID);
    		  $data  = $massage->select_all_admin($ID,2,$img);
    		  $data[0]['issuedate'] = substr($data[0]['issuedate'], 0,4)."年"
    						.substr($data[0]['issuedate'],5,2)."月"
    						.substr($data[0]['issuedate'],8,2)."日";
    		  $data = $data[0]; 
    		  $id = $data["project"];
              $data = $massage->check_data($data,$id);
    		  
              $user = $massage->user_message();
              $last_user = $massage->get_last_user_message($ID);
              $last_user1 = $massage->get_last_user_message1($ID);
              
              
                
              $last_user[0]['affirmanttime'] = substr($last_user[0]['affirmanttime'], 0,4)."年"
    						.substr($last_user[0]['affirmanttime'],5,2)."月"
    						.substr($last_user[0]['affirmanttime'],8,2)."日";
              $last_user[0]['username1'] = $last_user[0]['username1'].$last_user[0]['username2'];
              $last_user = $last_user[0]; 
    		
              
              $last_user1[0]['approvetime'] = substr($last_user1[0]['approvetime'], 0,4)."年"
    						.substr($last_user1[0]['approvetime'],5,2)."月"
    						.substr($last_user1[0]['approvetime'],8,2)."日";
              $last_user1[0]['username1'] = $last_user1[0]['username1'].$last_user1[0]['username2'];
              $last_user1 = $last_user1[0]; 
              
              
              $smarty->assign("sign",$sign);
              $smarty->assign("data",$data);
              $smarty->assign("user",$user);
              $smarty->assign("last_user",$last_user);   
              $smarty->assign("last_user1",$last_user1);   

    		  $smarty->display("toroku/toroku_admit_eat.tpl");
    	}
    	
    }
    else if($_GET['module']=="Quren")
    {
 
    	if($_REQUEST['radio31']=="0")
    	{
    		 $ID = $_GET['ID'];
    		 
    		 $file = $massage->select_file_id_name($_REQUEST['ID']);
    		 $idt_files = $file['ID'];
    		 $filename  = $file['newfilename'];
    		 
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
				
    		$data = array(
	    			   "ID"=>$ID
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
	                  ,"stopline"=>$_REQUEST['textfield9']
	                  ,"stoplineflg"=>$s
	                  ,"badcontent"=>$_REQUEST['textarea2']
	                  ,"badpictureflg"=>$t
	                  ,"idt_files"=>$idt_files
	                  ,"delflag"=>0
	    			);
	    	
	    	$id = $massage->Update1($data); //更新t_contact_form 中的数据
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);//更新t_form_head
    		$id = $massage->Insert_massage1($_GET['ID2'], $_SESSION['userinfo']['idm_company']); //发送消息
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],0,0);//反写

	        echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    		
    	}
    	else if($_REQUEST['radio31']=="1"){
    		$ID = $_GET['ID'];
    		
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);
    		$id = $massage->Insert_massage2($_GET['ID2']); 
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],0,1);
    		
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}
    	else if($_REQUEST['radio31']=="2"){
    		
    		$ID = $_GET['ID'];
     	
 			$file = $massage->select_file_id_name($_REQUEST['ID']);
    		$idt_files = $file['ID'];
    		$filename  = $file['newfilename'];
    		 
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
			
    		$data = array(
	    			   "ID"=>$ID
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
	                  ,"stopline"=>$_REQUEST['textfield9']
	                  ,"stoplineflg"=>$s
	                  ,"badcontent"=>$_REQUEST['textarea2']
	                  ,"badpictureflg"=>$t
	                  ,"idt_files"=>$idt_files
	                  ,"delflag"=>0
	    			);
	    	
	    	$id = $massage->Update1($data); 
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);
    		
    		$id = $massage->Insert_massage3($_GET['ID2'], $_SESSION['userinfo']['idm_company']); //发送消息
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],1,0);//反写
    		
    		
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    		
    	}
    	else if($_REQUEST['radio31']=="3"){
    		$ID = $_GET['ID'];
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);
    		$id = $massage->Insert_massage4($_GET['ID2']); 
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],1,1);
    		
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}
    	else if($_REQUEST['radio31']=="4"){
    		$ID = $_GET['ID'];
    		
    		//$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);
    		//$id = $massage->Reverse_writing($ID, $_SESSION['userinfo']['idm_company'],2);

    		echo "<script language='javascript'>";
			echo "location='toroku_result.php?module=new&ID=$ID';";
			echo "</script>";
    	}else if($_REQUEST['radio31']=="5"){
    		$ID = $_GET['ID'];
    		
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);   		
    		$id = $massage->Insert_massage6($_GET['ID2'],$_SESSION['userinfo']['idm_company']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],2,1);  

    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}else if($_REQUEST['radio31']=="6"){
    		
    
    		$ID = $_REQUEST['ID'];

        	$data = array(
        					"ID"=>$ID,
        					"instruction"=>$_REQUEST['textarea'],
							"attachmentflg"=>$_REQUEST['radio']     /////////图片未做     					
        			 );
        	$rs = $massage->Update1($data);
        	
        	$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);   		
    		$id = $massage->Insert_massage7($_GET['ID2'],$_SESSION['userinfo']['idm_company']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],3,0);  
        	
        	echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    		
    	}else if($_REQUEST['radio31']=="7"){
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);   		
    		$id = $massage->Insert_massage8($_GET['ID2']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],3,1);  
    			
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}else if($_REQUEST['radio31']=="8")
    	{
 
    		$ID = $_REQUEST['ID'];

        	$data = array(
        					"ID"=>$ID,
        					"instruction"=>$_REQUEST['textarea'],
							"attachmentflg"=>$_REQUEST['radio']     /////////图片未做     					
        			 );
        	$rs = $massage->Update1($data);
        	
        	$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);
    		$id = $massage->Insert_massage9($ID,$_GET['ID2'],$_SESSION['userinfo']['idm_company']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],4,0);  
        	
        	echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}else if($_REQUEST['radio31']=="9")
    	{
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);   		
    		$id = $massage->Insert_massage10($_GET['ID2']);
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],4,1);  
    			
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}else if($_REQUEST['radio31']=="10")
    	{
    		$id = $massage->Updata_head($_GET['ID2'],$_REQUEST['radio31']);   		
    		$id = $massage->Reverse_writing($_GET['ID2'], $_SESSION['userinfo']['idm_company'],5,0);  
    			
    		echo "<script language='javascript'>";
			echo "location='toroku_list.php';";
			echo "</script>";
    	}
    	//echo "<pre>";
    	//print_r($data);
    	//print_r($id);
    	//print_r($_REQUEST['radio31']);
     	
    }




?>