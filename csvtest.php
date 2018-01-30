<?php
//ini_set('display_errors','on');
//error_reporting(E_ALL);
require_once('base.php');
require_once('BpocsvBusiness.php');

$BpocsvBusiness = new BpocsvBusiness();
if (isset($_FILES['pure_ink'])) {
	//echo '111';exit();
	$fileName = date('YmdGis').'pure';
	if ($_FILES['pure_ink']['type'] =='application/vnd.ms-excel'){
		//echo '<pre>';print_r($_FILES['pure_ink']);
		$do = copy($_FILES['pure_ink']['tmp_name'],'templates/upload/bpocsv/'.$fileName.'.csv');
		if ($do){echo 'successful!';}else{echo 'failed!';}
	}else {
		echo 'file type is error  must csv!';
	}
	$file = fopen('templates/upload/bpocsv/'.$fileName.'.csv','r');
       while ($data = fgetcsv($file)) {    //每次读取CSV里面的一行内容
          //echo "<pre>"; print_r(encodeUTF8($data)); //此为一个数组，要获得每一个数据，访问数组下标即可
          $data = encodeUTF8($data);
          	$ink['id'] = $data[0];
          	$ink['id_m_maker'] = $data[1];
          	$ink['ink_name']= $data[2];
          	$ink['ink_code']= $data[3];
          	$ink['capacity']= $data[4];
          	$ink['unit']= $data[5];
          	$ink['color']= $data[6];
          	$ink['unit_price']= $data[7];
          	$ink['busy_safe_number']= $data[8];
          	$ink['busy_order_number']= $data[9];
          	$ink['safe_number']= $data[10];
          	$ink['order_number']= $data[11];
          	$ink['comment']= $data[12];
          	$month = array_flip(explode('/',$data[13]));
          	$msg =$BpocsvBusiness->pure_ink($ink);
          	$id = $BpocsvBusiness->select_pure_ink($ink);
          	$msg =$BpocsvBusiness->busy_season($month,'1',$id[0]['id']);
     }
        fclose($file);

}
if (isset($_FILES['m_printer'])){
$fileName = date('YmdGis').'printer';
	if ($_FILES['m_printer']['type'] =='application/vnd.ms-excel'){
		$do = copy($_FILES['m_printer']['tmp_name'],'templates/upload/bpocsv/'.$fileName.'.csv');
		if ($do){echo 'successful!';}else{echo 'failed!';}
	}else {
		echo 'file type is error  must csv!';
	}
	$file = fopen('templates/upload/bpocsv/'.$fileName.'.csv','r');
       while ($data = fgetcsv($file)) {    //每次读取CSV里面的一行内容
          //echo "<pre>"; print_r(encodeUTF8($data)); //此为一个数组，要获得每一个数据，访问数组下标即可
          $data = encodeUTF8($data);
          	$ink['id'] = $data[0];
          	$ink['id_m_maker'] = $data[1];
          	$ink['printer_name']= $data[2];
          	$ink['printer_code']= $data[3];
          	$ink['comment']= $data[4];
          	$pure = explode('/',$data[5]);
          	$msg =$BpocsvBusiness->m_printer($ink);
          	$id =$BpocsvBusiness->select_m_printer($ink);
          	for ($j=0;$j<count($pure);$j++){
          		if (!empty($pure[$j])) {
          			$msg =$BpocsvBusiness->m_printer_ink($pure[$j],$id[0]['id']);
          		}
          	}
     }
        fclose($file);

}
$flag='';
if (isset($_FILES['st'])){
	$fileName = date('YmdGis').'st';
	if ($_FILES['st']['type'] =='application/vnd.ms-excel'){
		$do = copy($_FILES['st']['tmp_name'],'templates/upload/bpocsv/'.$fileName.'.csv');
		if ($do){echo 'successful!';}else{echo 'failed!';}
	}else {
		echo 'file type is error  must csv!';
	}
	$file = fopen('templates/upload/bpocsv/'.$fileName.'.csv','r');
       while ($data = fgetcsv($file)) {    //每次读取CSV里面的一行内容
          //echo "<pre>"; print_r(encodeUTF8($data)); //此为一个数组，要获得每一个数据，访问数组下标即可
          	$data = encodeUTF8($data);


          //m_st_ink
          	$ink['id'] = $data[0];

          	//set or 单品


          	$ink['id_m_maker'] = $data[1];
          	$ink['id_m_st_brand']= $data[2];
          	$ink['ink_code']= $data[3];
          	$ink['ink_name']= $data[4];
          	$ink['capacity']= $data[5];
          	$ink['unit']= $data[6];
          	$ink['comment']= $data[7];
          	$ink['total_price'] = $data[18];
          	//m_st_ink_number
          	$stdata['id']= $data[8];
          	$stdata['ink_code']= $data[9];
          	$stdata['color']= $data[10];
          	$stdata['busy_safe_number']= $data[11];
          	$stdata['busy_order_number']= $data[12];
          	$stdata['safe_number']= $data[13];
          	$stdata['order_number']= $data[14];
          	$stdata['unit_price']= $data[15];
          	//echo '<pre>';print_r($stdata);exit();
          	$st_ink=explode('/',$data[16]);

          	$month = array_flip(explode('/',$data[17]));

          	if ($data[0] == $flag && $data[6] =='1') {

	       			$id =$BpocsvBusiness->select_m_st_ink($ink);
	       			$msg =$BpocsvBusiness->m_st_ink_number($stdata,$id[0]['id']);
	       }else {
		          	$msg =$BpocsvBusiness->m_st_ink($ink);
		          	$id =$BpocsvBusiness->select_m_st_ink($ink);

		          	$msg =$BpocsvBusiness->m_st_ink_number($stdata,$id[0]['id']);
		          	for ($j=0;$j<count($st_ink);$j++){
		          		if (!empty($st_ink[$j])) {
		          			$msg =$BpocsvBusiness->m_printer_st_ink($st_ink[$j],$id[0]['id']);
		          		}
		          	}
		          	$msg =$BpocsvBusiness->busy_season($month,'0',$id[0]['id']);

	       }

          	$flag=$data[0];
     }
        fclose($file);


}


/*----------------------------------------------------------------------------------------------------------------*/
if(isset($_FILES['t_customer'])){

	$fileName = date('YmdGis').'t_customer';
	if ($_FILES['t_customer']['type'] =='application/vnd.ms-excel'){
		$do = copy($_FILES['t_customer']['tmp_name'],'templates/upload/bpocsv/'.$fileName.'.csv');
		if ($do){echo 'successful!';}else{echo 'failed!';}
	}else {
		echo 'file type is error  must csv!';
	}
	$file = fopen('templates/upload/bpocsv/'.$fileName.'.csv','r');
	 while ($data = fgetcsv($file)) {
          	$data = encodeUTF8($data);
          	$support['difficulty_no'] = $data[0];
          	$support['create_date'] = $data[1];
          	$support['before_date'] = $data[1];
          	switch ($data[2]){
          		case '石原':
          			$support['create_user']='117';
          			$support['before_user']='117';
          			break;
          		case '中澤':
          			$support['create_user']='118';
          			$support['before_user']='118';
          			break;
          		case '田中(正)':
          			$support['create_user']='119';
          			$support['before_user']='119';
          			break;
          		case '橘田':
          			$support['create_user']='120';
          			$support['before_user']='120';
          			break;
          		case '大久保':
          			$support['create_user']='140';
          			$support['before_user']='140';
          			break;
          		case '田中(文)':
          			$support['create_user']='141';
          			$support['before_user']='141';
          			break;
          		case '志村':
          			$support['create_user']='142';
          			$support['before_user']='142';
          			break;
          		case '金丸':
          			$support['create_user']='143';
          			$support['before_user']='143';
          			break;
          		case '保坂':
          			$support['create_user']='144';
          			$support['before_user']='144';
          			break;
          		case '依田':
          			$support['create_user']='145';
          			$support['before_user']='145';
          			break;
          		case '花輪':
          			$support['create_user']='146';
          			$support['before_user']='146';
          			break;
          		case '齊藤':
          			$support['create_user']='147';
          			$support['before_user']='147';
          			break;
          		case '斉藤':
          			$support['create_user']='147';
          			$support['before_user']='147';
          			break;
          		case '山上':
          			$support['create_user']='148';
          			$support['before_user']='148';
          			break;
          		case '遠藤':
          			$support['create_user']='149';
          			$support['before_user']='149';
          			break;
          		case '塩釜':
          			$support['create_user']='150';
          			$support['before_user']='150';
          			break;
          		case '深澤':
          			$support['create_user']='151';
          			$support['before_user']='151';
          			break;
          		default :
          			$support['create_user']='0';
          			$support['before_user']='0';
          	}
          	if (count(explode(':',$data[3]))>0){
          		$support['create_date'].=' '.$data[3];
          		$support['before_date'].=' '.$data[3];

          	}else {
          		$support['create_date'].=' 00:00';
          		$support['before_date'].=' 00:00';
          	}
          	switch ($data[4]){//id_m_st_brand
          		case 'エコリカ':
          			$support['id_m_st_brand']='1';break;
          		case 'INNOTECH':
          			$support['id_m_st_brand']='2';break;
          		case 'プレジール':
          			$support['id_m_st_brand']='3';break;
          		case '一般':
          			$support['id_m_st_brand']='0';break;
          		default:
          			$support['id_m_st_brand']='0';
          	}
          	switch ($data[5]){//accept_flg
          		case '新規':
          			$support['accept_flg']='0';break;
          		case '再クレーム':
          			$support['accept_flg']='1';break;
          		case '誤送付':
          			$support['accept_flg']='2';break;
          		case '先行納入':
          			$support['accept_flg']='3';break;
          		case '代替機発送':
          			$support['accept_flg']='5';break;
          		case 'プリンタ返却':
          			$support['accept_flg']='6';break;
          		default:
          			$support['accept_flg']='4';
          	}
          	$support['zip_code'] = $data[6];

          	//
	 	  	$address1 = explode("県",$data[7]);

          	if (count($address1) > 1) {
          		$support['prefecture'] = $address1[0].'県';
          		$support['address_1'] = $address1[1];
          	} else {
          		$address2 = explode("府",$data[7]);
          		if (count($address2) > 1) {
          			$support['prefecture'] = $address2[0].'府';
          			$support['address_1'] = $address2[1];
          		} else {
          			$address3 = explode("道",$data[7]);
          			if (count($address3) > 1) {
	          			$support['prefecture'] = $address3[0].'道';
	          			$support['address_1'] = $address3[1];
	          		} else {
		          		$address4 = explode("都",$data[7]);
	          			if (count($address4) > 1) {
		          			$support['prefecture'] = $address4[0].'都';
		          			$support['address_1'] = $address4[1];
		          		}
	          		}
          		}
          	}

          	$support['address_2'] = $data[8];
          	$support['tel_1'] = $data[9];
          	$support['tel_2'] = $data[10];
          	$support['id_m_st_ink_input'] = $data[17];

	 		if ($data[11] !='' || $data[12] !=''){
          		$support['object_flg']='1';
          		$support['customer_name']=$data[11];
          		$support['customer_kana']=$data[12];
          		$support['contact_name']=$data[13];
          		$support['contact_kana']=$data[14];

          	}else {
          		$support['object_flg']='0';
          		$support['customer_name']=$data[13];
          		$support['customer_kana']=$data[14];
          		$support['contact_name']='';
          		$support['contact_kana']='';
          	}
          	$support['printer_maker_no_input'] = $data[15];
          	$support['store_name_input'] = $data[16];
          	$id =$BpocsvBusiness->select_t_customer($support);
          	if ($id[0]['id']!=''){
          		$support['id_t_customer']= $id[0]['id'];
          		$msg =$BpocsvBusiness->t_customer_support($support);
          	}else {
          		$msg =$BpocsvBusiness->t_customer($support);
          		$support['id_t_customer']= mysql_insert_id();
          		$msg =$BpocsvBusiness->t_customer_support($support);
          	}
	 }
}


/*----------------------------------------------------------------------------------------------------------------*/
if (isset($_FILES['m_zip_master'])){
	$fileName = date('YmdGis').'zip';
	if ($_FILES['m_zip_master']['type'] =='application/vnd.ms-excel'){
		$do = copy($_FILES['m_zip_master']['tmp_name'],'templates/upload/zipcsv/'.$fileName.'.csv');
		if ($do){echo 'successful!';}else{echo 'failed!';}
	}else {
		echo 'file type is error  must csv!';
	}

	$file = fopen('templates/upload/zipcsv/'.$fileName.'.csv','r');
       while ($data = fgetcsv($file)) {
          	$data = encodeUTF8($data);

          	$temp['no'] = $data[0];
          	$temp['zip_code_before'] = $data[1];

          	$zip_code = substr($data[2], 0, 3).'-'.substr($data[2], 3, 6);
          	$temp['zip_code']= $zip_code;

          	$temp['prefecture_kana']= $data[3];
          	$temp['city_kana']= $data[4];
          	$temp['address_kana']= $data[5];
          	$temp['prefecture']= $data[6];
          	$temp['city']= $data[7];
          	$temp['address'] = $data[8];
          	$temp['flg1'] = $data[9];
          	$temp['flg2'] = $data[10];
          	$temp['flg3'] = $data[11];
          	$temp['flg4'] = $data[12];
          	$temp['flg5'] = $data[13];
          	$temp['flg6'] = $data[14];

   			$BpocsvBusiness->insertMZipMaster($temp);
     }

     fclose($file);
}
/*----------------------------------------------------------------------------------------------------------------*/



function encodeUTF8($array) {
		foreach ( $array as $key => $value ) {
			if (! is_array ( $value )) {
				$array [$key] = mb_convert_encoding ( $value, "utf-8", "sjis-win" );
			} else {
				$array [$key] = encodeUTF8 ( $value );
			}
		}
	return $array;
}
$smarty->display('csv/index.tpl')
?>