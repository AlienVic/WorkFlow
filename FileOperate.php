<?php
require_once('base.php');
require_once('FilesOperateBusiness.php');
$filesoperatebusiness = new FilesOperateBusiness();
$flag = "no";
if(!isset($_REQUEST['module'])){
	$flag = "no";
}
else{
	switch ($_REQUEST['module']){
		case 'delete':
			try {
				$file = $filesoperatebusiness->getFileInfo($_POST["id"]);
				$msg = $filesoperatebusiness->deleteFile($_POST["id"]);
				if($msg){
					$filesoperatebusiness->delete_file(UPLOAD_FILES_PATH.$file["linkname"]);
					echo 1;
				}
				else{
					echo 2;
				}
			}catch (SupportException $e){
				$logger->error($e);
				exit();
			}
			break;
		case 'download':
			if(isset($_GET["id"]) && $_GET["id"] > 0){
				$info = $filesoperatebusiness->getFileInfo($_GET["id"]);
				FilesOperateBusiness::downloadFile(UPLOAD_FILES_PATH.$info["linkname"], $info["showname"], $info["size"]);
			}
			break;
		case 'showfile':
			try {
				$file = $filesoperatebusiness->getFileInfo($_GET["id"]);
				$smarty->assign("type", $filesoperatebusiness->getFileType($file["showname"]));
				$smarty->assign("linkname", $file["linkname"]);
				$smarty->display('seefiles/onefile.tpl');
			}catch (SupportException $e){
				$logger->error($e);
				exit();
			}
			break;
	}
}
?>