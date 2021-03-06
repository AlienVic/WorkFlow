<?php

require_once('base.php');
  
require_once('LoginBusiness.php');

require_once('SessionUtil.php');
require_once('ConstUtil.php');


/**
 * ログイン機能　コントロール
 *
 * @author zhao
 * @version 2011/08/16
 */
     $logger = LoggerManager::getLogger('login');


    $logger->info('ユーザログイン：開始');


    $loginBusiness = new LoginBusiness();

	

	
    // ログイン操作
if ($_GET['module'] == 'login') {
    try {
		// ユーザログインチェック
      	$staffInfo = $loginBusiness->validateLogin($_POST['staffName'], $_POST['password']);

        //设置Session的值
        $_SESSION['userinfo'] = $staffInfo;
        //echo '<pre>';
        //print_r($staffInfo);
		//取当前时间，和星期
		//$currentdate = date('Y/m/d');
		$currentweekindex = date('w');
		$currentweekday = ConstUtil::getConstArray('WeekDayList');
		$_SESSION['CurrWeekDay'] = $currentweekday[$currentweekindex];

		//print_r($_SESSION['CurrWeekDay']  );
		       //exit;

        $logger->info('ユーザログイン：終了');

        //$smarty->display('index/index.php');
        echo "<script language='javascript'>";
		echo "location='index.php';";
		echo "</script>";

    } catch (SupportException $e) {
    	$logger->error($e);
    	header('X-JSON: ' . createJsonError($e));
		$test = createJsonError($e);
        $smarty->assign('array', $test);
        $smarty->display('login.tpl');
    }

}
// ログアウト操作
elseif ($_GET['module'] == 'logout') {
	session_destroy();
	$smarty->display('login.tpl');

// 初期化
} else {
    $smarty->display('login.tpl');
}




?>