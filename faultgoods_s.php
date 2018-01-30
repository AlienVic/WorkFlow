<?php
require_once('base.php');
require_once('FaultgoodsBusiness.php');
require_once('TemplateBusiness.php');
require_once('BugSearchBusiness.php');

$logger = LoggerManager::getLogger('faultgoods_s');
$faultgoodsBusiness = new FaultgoodsBusiness();
$bugSearchBusiness = new BugSearchBusiness();

if(!isset($_REQUEST['module']) || $_REQUEST['module'] == 'list') {

    $whe = '1 = 1 ';
    if ($_REQUEST['begin_date']!='' ){
        $whe.=" AND t_customer_support.create_date >='" . $_REQUEST['begin_date'] . " 00:00:00'";
    }
    if ($_REQUEST['end_date']!=''){
        $whe.= " AND t_customer_support.create_date <= '" . $_REQUEST['end_date'] . " 23:59:59'";
    }
    if ($_REQUEST['difficulty_no']!=''){
        $whe.=" AND t_customer_support.difficulty_no LIKE '" . $_REQUEST['difficulty_no'] . "%'";
    }

    try {
        $rs = $faultgoodsBusiness->SearchList($whe);
    }catch (SupportException $e){
        $logger->error($e);
        exit();
    }

    $smarty->assign("rs",$rs);
    $smarty->assign("begin_date",$_REQUEST['begin_date']);
    $smarty->assign("end_date",$_REQUEST['end_date']);
    $smarty->assign("difficulty_no",$_REQUEST['difficulty_no']);
    $smarty->assign('tplTitle', '返却品登録状況検索');
    $smarty->display('faultgoods_s/list.tpl');
} else if($_REQUEST['module'] == 'index') {
    $id = $_REQUEST['id_t_customer_support'];
    try {
        $rs1 = $faultgoodsBusiness->SearchDetailed1($id);
    	if ($rs1[0]['modify_date'] == '1999-11-30 00:00:00' || $rs1[0]['modify_date'] == '0000-00-00 00:00:00') {
			$rs1[0]['modify_date'] = '';
		}

        $caseno = $faultgoodsBusiness->CaseNumber($id);

        $rs2 = $faultgoodsBusiness->SearchDetailed2($id);
        if ($rs2[0]['purchase_date'] == '1999-11-30 00:00:00' || $rs2[0]['purchase_date'] == '0000-00-00 00:00:00') {
			$rs2[0]['purchase_date'] = '';
		}

        $supportDetailData = $bugSearchBusiness->getSupportDetailData($_GET['difficulty_no']);
		for ($i=0; $i<count($supportDetailData); $i++) {
			if ($supportDetailData[$i]['detail_id'] != null) {
				// Detailから
				$rs3[$i] = $bugSearchBusiness->getSupportCheckData1($supportDetailData[$i]['detail_id']);
			} else {
				// Inkから
				$rs3[$i] = $bugSearchBusiness->getSupportCheckData2($supportDetailData[$i]['id']);
			}
		}
        //$rs3 = $faultgoodsBusiness->SearchDetailed3($id);

        $rs4 = $faultgoodsBusiness->SearchDetailed4($id);

        $smarty->assign("caseno",$caseno);
        $smarty->assign("rs1",$rs1);
        $smarty->assign("rs2",$rs2);
        $smarty->assign("rs3",$rs3);
        $smarty->assign("rs4",$rs4);
        $smarty->assign("parent",$parent);
        $smarty->assign("child",$child);
        $smarty->assign('tplTitle', '返却品登録状況詳細');
        $smarty->display('faultgoods_s/index.tpl');
    }catch (SupportException $e){
        $logger->error($e);
        exit();
    }
} else if($_REQUEST['module'] == 'new_complete') {
    $uploaddir = BaseUrl . '/uploadfiles/faultgoods/';
    $uploadfile = $uploaddir . basename($_FILES['myFile']['name']);
    if(is_uploaded_file($_FILES['myFile']['tmp_name'])) {
        if(move_uploaded_file($_FILES['myFile']['tmp_name'], $uploadfile)) {
            $fileName = StringUtil::inputFilter($_FILES['myFile']['name']);
            $fileSize = $_FILES['myFile']['size'];
        }
    }

    $data = array('id_t_customer' => $_POST['id_t_customer'],
                  'faultgoods_date' => $_POST['faultgoods_date'],
                  'faultgoods_title' => StringUtil::inputFilter($_POST['faultgoods_title']),
                  'faultgoods_comment' => StringUtil::inputFilter($_POST['faultgoods_comment']),
                  'file_name' => $fileName,
                  'file_size' => $fileSize);

    try {
        $logger->info('不具合保存開始');
        $faultInfo = $faultgoodsBusiness->saveFaultGood($data);
        if ($_REQUEST['title']) {
            $flg = 'edit';
        }
    if ($flg == 'edit') {
        $tpl_onload = "window.opener.document.getElementById('faultgoods_date').innerHTML = '" . $faultInfo['faultgoods_date'] . "';
                window.opener.document.getElementById('faultgoods_title').innerHTML ='" . $faultInfo['faultgoods_title'] ."';
                window.opener.document.getElementById('faultgoods_comment').innerHTML ='" . $faultInfo['faultgoods_comment'] ."';
                window.close();";
    }
    else {
        $tpl_onload = "var record_prev = window.opener.document.getElementById('tab').rows.length;
                var row  = window.opener.document.getElementById('tab').insertRow(record_prev);

                var col0 = row.insertCell(0);
                var col1 = row.insertCell(1);
                var col2 = row.insertCell(2);
                var col3 = row.insertCell(3);

                col0.align = 'center';
                col1.align = 'center';
                col2.align = 'left';
                col2.width = '36%';
                col3.align = 'center';
                col3.width = '13%';

                var temp0 = '<span id=\"faultgoods_date\">" . $faultInfo['faultgoods_date'] . "</span>';
                var temp1 = '<span id=\"faultgoods_title\">" . $faultInfo['faultgoods_title'] . "</span>';
                var temp2 = '<span id=\"faultgoods_comment\">" . $faultInfo['faultgoods_comment'] . "</span>';
                var temp3 = '<span id=\"down_id\"><input type = \"button\"  value = \"ダウンロード\" class=\"anniu10\" onclick=\"window.location=\'faultgoods_s.php?module=download&id=" . $faultInfo['id'] . "\'\" ></span>';
                col0.innerHTML = temp0;
                col1.innerHTML = temp1;
                col2.innerHTML = temp2;
                col3.innerHTML = temp3;
                window.close();";
    }
        $smarty->assign("tpl_onload",$tpl_onload);
        $smarty->assign("flg",$flg);
        //$smarty->assign("tpl",$tpl);
        $_SESSION['message'] = '登録成功しました。';
        $logger->info('不具合保存終了');
    } catch (SupportException $e){
        $logger->error($e);
        exit();
    }
    $smarty->display('faultgoods_s/new.tpl');

} else if($_REQUEST['module'] == 'download') {
    $templateBusiness = new TemplateBusiness();
    $bugSerachBusiness = new BugSearchBusiness();
    $id = $_REQUEST['id'];
    $fileName = $bugSerachBusiness->getFiles($id);
    $uploaddir = BaseUrl . '/uploadfiles/faultgoods/' . $fileName['showname'];
    $templateBusiness->downFileFromServer($fileName['showname'], $uploaddir);
} else if($_REQUEST['module'] == 'new_fault') {
    $tpl_onload = '';
    $tpl = '';
    $id = $_REQUEST['id_t_customer'];
    $faultInfo = $faultgoodsBusiness -> getFaultGoodsInfo($id);
    $smarty->assign('tpl_onload', $tpl_onload);
    $smarty->assign('tpl', $tpl);
    $smarty->assign('faultInfo', $faultInfo);
    $smarty->assign("id",$id);
    $smarty->display('faultgoods_s/new.tpl');
} else if($_REQUEST['module'] == 'update') {
    $id = $_REQUEST['t_customer_id'];
    try {
        $faultgoodsBusiness->modifyFaultGoods($id);
        $_SESSION['message'] = '登録成功しました。';
        header('X-JSON: ' . createJonSuccess('modifyFaultGoodsSuccess', null));
    }catch (SupportException $e){
        $logger->error($e);
        exit();
    }
} else if($_REQUEST['module'] == 'delete') {
    $id = $_REQUEST['t_customer_id'];
    try {
        $faultgoodsBusiness->deleteFaultGoods($id);
        $_SESSION['message'] = '削除成功しました。';
        header('X-JSON: ' . createJonSuccess('deleteFaultGoodsSuccess', null));
    }catch (SupportException $e){
        $logger->error($e);
        exit();
    }
}