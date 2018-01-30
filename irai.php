<?php
require_once('base.php');
require_once('DependBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');



TplConstTitle::setPageTitle($smarty, 'Dep');
TplConstTitle::setTplTitle($smarty, 'Dep','Search1');
TplConstTitle::setSubTplTitle($smarty, 'Dep','Search2');

$depbusi = new DependBusiness();
$seconddata = $depbusi->getCurrUserUrls('2',$_SESSION['userinfo']['comtype']);
$firstdata = $depbusi->getUrls('1');
//echo "<pre>";
//print_r($firstdata);
//exit;

$idm = $_SESSION['userinfo']['idm_company'];


$smarty->assign("secondurls",$seconddata);
$smarty->assign("firsturls",$firstdata);
//m_urls的useright 1：协力  0：ETA  2：协力和ETA
if ( empty($_REQUEST['module']) || $_REQUEST['module'] == "sel")
{
	
    $smarty->display('depend/irai.tpl');
}





?>