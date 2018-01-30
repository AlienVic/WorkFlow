<?php
require_once('base.php');
require_once('productBusiness.php');
require_once('SessionUtil.php');
require_once('ConstUtil.php');
require_once('TplConstTitle.php');


function encodeUTF8($array) 
{
	foreach ( $array as $key => $value ) 
	{
		if (! is_array ( $value )) 
			{
				$array [$key] = mb_convert_encoding ( $value, "utf-8", "sjis-win" );
			} 
			else 
			{
				$array [$key] = encodeUTF8 ( $value );
			}
		}
	return $array;
}


$smarty = new Smarty();

TplConstTitle::setPageTitle($smarty, 'product');


$pro = new productBusiness();
if (empty($_REQUEST['module']))
{
	
	TplConstTitle::setTplTitle($smarty, 'product','list');
	$currpage = $_REQUEST['p'];
	if (empty($currpage))
	{
		$currpage = 1;
	}
	$numbersperpage = 20;
	$data2 = $pro->Search($currpage,$numbersperpage);
	$totalpages = $pro->getTotalPage();
	//echo "<pre>";
	//print_r($data2);
	$lngtype = 2;
	$smarty->assign("lngtype",$lngtype);

	$smarty->assign("pagecount",$totalpages);
	$smarty->assign('data', $data2);
	$smarty->display('product/product.tpl');
	
	
}
else if($_REQUEST['module']=="enter")
{
	

			$upfile= './uploadfiles/product/'.date('YmdGis').'product'.'.csv';
			$do = move_uploaded_file($_FILES['pure_ink']['tmp_name'],$upfile);
		//	echo $upfile;
			if (!$do) 
			{ 
				echo 'failed!';
				exit;
			}
	
		//删除记录
		$pro->Delete();
		//$pro->resetAutoIncre();
	 	$file = fopen($upfile,'r');
	 	$row=1;
	 	$inserdata = array();
	 	$insertbasenum = 200;
	 	$alldatanum = 0;
	 	$data1 = fgetcsv($file);//排除第1行，表头
	 	$data1=encodeUTF8($data1);
	 	//判断csv文件是否与数据库一致
	 if(!($data1[0]=='ISSUE_D'&&$data1[1]=='CMPL_INST_D'&&$data1[2]=='ITEM_COD'&&$data1[3]=='M_NO'&&$data1[4]=='GOODS_COD'&&$data1[5]=='M_O_Q'&&$data1[24]=='AWｺｰﾄﾞ'&&$data1[26]=='AW協力会社名'))
	 	{
	 		echo 'というわけではありませんの製番情報csv';
	 		echo '请返回';
			exit;
	 	}
	 	
     	while ($data = fgetcsv($file)) 
     	{    
      		$ink['issue_d'] = $data[0];
          	$ink['cmpl_inst_d'] = $data[1];
          	$ink['item_cod']= $data[2];
          	$ink['m_no']= $data[3];
          	$ink['goods_cod']= $data[4];
          	$ink['m_o_q']= $data[5];
          	$ink['AWCode']= $data[24];
          	$ink['AWCompanyName']= $data[26];
          	$alldatanum++;
          	if ($alldatanum % $insertbasenum == 0)//到了基数
          	{
          		$pro->Insert($inserdata);
          		$inserdata = array();
          	}
          	$inserdata[] = encodeUTF8($ink);
        }
        if ($alldatanum % $insertbasenum != 0)
        {
        	$pro->Insert($inserdata);
        }
       // echo $alldatanum;
  // }
    fclose($file);
    
   // 跳转到第List画面
	echo "<script language='javascript'>";
	echo "location='product.php';";
	echo "</script>";
	
	
}




?>