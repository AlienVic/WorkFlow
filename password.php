<?php
require_once('base.php');
  
require_once('LoginBusiness.php');

require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('passwordBusiness.php');
//require_once('checkvalid.php');
 $pass = new passwordBusiness();

 TplConstTitle::setPageTitle($smarty, "password");
if (!isset($_REQUEST['module']) || $_REQUEST['module'] == 'show')
{
	 TplConstTitle::setTplTitle($smarty, "password","edit");
	$smarty->assign('pwdinfo',$_SESSION["userinfo"]);
	//echo "<pre>";
	//print_r($_SESSION["userinfo"]);
	//echo '123';
	unset($_SESSION['userinfo']);
	//echo "<pre>";
	//print_r($_SESSION["userinfo"]);
	$smarty->display('password/password.tpl');
	
	
	
	
}
else if ($_REQUEST['module'] == 'edit')
{
	
	TplConstTitle::setTplTitle($smarty, "password","edit");
	 $id=$_POST['ID'];
	
	 $password = $_POST['password_value'];
	 $pass->editPwdByID($password,$id);


	 $pwdinfo = $pass->getPwd($id);


	 $smarty->assign('pwdinfo',$pwdinfo);


	//TplConstTitle::setTplTitle($smarty, "pg_user_06", "02");
//	TplConstTitle::setPageTitle($smarty, "pg_user_06");

   echo "<script language='javascript'>";
	echo "location='login.php?modue=login';";
	echo "</script>";
}

?>