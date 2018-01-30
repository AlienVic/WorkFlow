<?php
require_once('base.php');

require_once('UserBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');





TplConstTitle::setPageTitle($smarty, 'User');
//TplConstTitle::setTplTitle($smarty, 'User','New');

$ubusi = new UserBusiness();
$userright=ConstUtil::getConstArray('UserRight');
$usertype=ConstUtil::getConstArray('UserType');
$companylist = $ubusi->getCompanyList();
$smarty->assign('userright', $userright);
$smarty->assign('usertype', $usertype);
$smarty->assign('companylist', $companylist);




if (empty($_REQUEST['module']) || $_REQUEST['module']=="list")
{
	TplConstTitle::setTplTitle($smarty, 'User','List');
	$data = $ubusi->search();
	$users = $ubusi->getProUsers();
	$smarty->assign('user',$users);
	$smarty->assign('data', $data);
    $smarty->display('user/list.tpl');

}
else if ($_REQUEST['module'] == "data")//插入数据用
{
	//追加完毕，回到List
	//$id = $_POST['id'];
	$data = $_POST['user'];
	$id = $_REQUEST['id'];
	
	//echo "<pre>";
	//print_r($data);
	if (empty($id))
	{
		$data['delflag']=0;
		$ubusi->insert($data);
		$id = mysql_insert_id();
			
	}
	else
	{
		$ubusi->Update($data);
	}
	
	
	TplConstTitle::setTplTitle($smarty, 'User','Show');

	$data2 = $ubusi->search($id);
	$step = "1";
	
	//echo "123";
	//echo "<pre>";
	//print_r($data2[0]);
	
	
	$smarty->assign('data', $data2[0]);
	$smarty->assign('step', $step);	
    $smarty->display('user/show.tpl');
    
    
	//echo "<script language='javascript'>";
	//echo "location='user.php?module=show&s=1&id=$id';";
	//echo "</script>";
	
}
else if ($_REQUEST['module'] == "show")
{
	TplConstTitle::setTplTitle($smarty, 'User','Show');
	$id = $_REQUEST['id'];
	$data = $ubusi->search($id);
	$step = $_REQUEST['s'];
	//echo "<pre>";
	//print_r($data[0]);
	
	
	$smarty->assign('data', $data[0]);
	$smarty->assign('step', $step);
	
    $smarty->display('user/show.tpl');
    
    
    
}
else if ($_REQUEST['module'] == "edit")
{
	TplConstTitle::setTplTitle($smarty, 'User','Edit');
	$id = $_REQUEST['id'];
	$data = $ubusi->search($id );
	//echo "<pre>";
	//print_r($data[0]);
	$smarty->assign('data', $data[0]);
    $smarty->display('user/new.tpl');
}
else if ($_REQUEST['module'] == "del")
{
	//删除完毕后返回到List画面
	$ubusi->DeleteLogical($_REQUEST['id']);
	echo "<script language='javascript'>";
	echo "location='user.php?module=list';";
	echo "</script>";
}
else if ($_REQUEST['module'] == "ini")
{
	TplConstTitle::setTplTitle($smarty, 'User','New');
	//$comtype = ConstUtil::getConstArray("CompanyType");
	//$smarty->assign('comtype', $comtype);
    $smarty->display('user/new.tpl');

}
else if ($_REQUEST['module'] == "depart")//将想要返回HTML页面的结果组成一个字符串
{
	$comid = $_REQUEST['comid'];
	$departlist = $ubusi->getDepartListBycomId($comid);
	$response = "";
	foreach($departlist as $key => $value)
	{
		$response = $response.$value['ID'].":".$value['departname'];
		$response = $response."|";	
	}
	$response = substr($response, 0,strlen($response)-1);
	echo $response;//拼接完毕之后，使用echo输出到前端(返回给js文件)
	
}
else if($_REQUEST['module'] == "pro")//------------------------------------------------------------
{
	$data = $ubusi->getProUsers($_REQUEST['idm_comapny']);
	
	$response = "";
	foreach($data as $key => $value)
	{
		$response = $response.$value['ID'].":".$value['name'].":".$value['usertype'].":".$value['departname'].":".$value['companyname']; 
		$response = $response."|";
	}
	$response = substr($response, 0,strlen($response)-1);
	echo $response;

	
}
else if($_REQUEST['module'] == "agent")
{
	$proid = $_REQUEST['ID'];
	$userid = $_REQUEST['userid'];
	$data = array("proxyflg" => "1", "proxyid" => $proid,"id" => $userid);
	$ubusi->Update($data);
	
	
	//处理完后跳转到开头页面
	echo "<script language='javascript'>";
	echo "location='user.php?module=list';";
	echo "</script>";
}
else if($_REQUEST['module'] == "cancelagent")
{
	$userid = $_REQUEST['userid'];
	$data = array("id" => $userid, "proxyid" => "", "proxyflg" => "0");
	$ubusi->Update($data);
	
	//处理完后跳转到开头页面
	echo "<script language='javascript'>";
	echo "location='user.php?module=list';";
	echo "</script>";
}
else if($_REQUEST['module'] == "search")
{
	$keyword = $_REQUEST['keyword'];
	$data = $ubusi->getSearch($keyword);
	$smarty->assign('user',$users);
}






?>