<?php
require_once('base.php');
require_once('UserBusiness.php');
require_once('SessionUtil.php');
require_once('DependBusiness.php');


$usebusi = new UserBusiness();
$data = $usebusi->getProUsers();
$smarty->assign('data',$data);
$smarty->display('depend/model.tpl');
?>