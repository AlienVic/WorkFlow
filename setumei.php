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


$seconddata = $setumei->getCurrUserUrls_instruct('1',$_SESSION['userinfo']['comtype']);

$firstdata = $setumei->getUrls('1');
//echo "<pre>";
//print_r($firstdata);
//exit;

$idm = $_SESSION['userinfo']['idm_company'];


$smarty->assign("secondurls",$seconddata);
$smarty->assign("firsturls",$firstdata);
//m_urls的useright 1：协力  0：ETA  2：协力和ETA
if ( empty($_REQUEST['module']) || $_REQUEST['module'] == "sel" )
{
	 $smarty->display('setumei/setumei.tpl');
}

?>