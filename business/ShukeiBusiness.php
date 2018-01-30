<?php
	require_once('BaseBusiness.php');
	require_once('ConstUtil.php');
	
	
	
class ShukeuBusiness extends BaseBusiness
{
	function select_renraku($time1=null,$time2=null,$cod=null){
		
		    //issue_d 发行日   cmpl_inst_d 安装日期
		    
			$sql = "SELECT * FROM v_contact_form";
			if($time1 != null || $time2 || null || $cod != null)
			{
				$sql = $sql." WHERE ";
			}
			if($time1 != null){
				$sql = $sql." issue_d >= '$time1'";
			}
			if($time2 != null){
				$time2 = $time2." 23:59:59";
				if($time1 != null){
					$sql = $sql." AND issue_d <= '$time2' ";
				}else {
					$sql = $sql." issue_d <= '$time2' ";
				}
			}
			if($cod != null){
				if($time1 != null || $time2 != null){
					$sql = $sql." AND goods_cod = '$cod' ";
				}else{
					$sql = $sql." goods_cod = '$cod' ";
				}
			}
			
			$rs = $this->db->exceuteQuery($sql);
		
			return $rs;
	}
	
	function select_shiji($type=6,$time1=null,$time2=null,$cod=null){
		
			$sql = "SELECT * FROM v_instruct_form WHERE idm_urls='$type'";
			
			if($time1 != null){
				$sql = $sql." AND issue_d >= '$time1'";
			}
			if($time2 != null){
				$time2 = $time2." 23:59:59";
				$sql = $sql." AND issue_d <= '$time2' ";
				
			}
			if($cod != null){
				$sql = $sql." goods_cod = '$cod'";
			}
			$rs = $this->db->exceuteQuery($sql);
			return $rs;
	
		
	}
	
	function select_irai($time1=null,$time2=null,$cod=null,$serialno=null){
		
			$sql = "SELECT * FROM v_depend_form";
			
			if($time1 != null || $time2 || null || $cod != null || $serialno != null)
			{
				$sql = $sql." WHERE ";
			}
			if($time1 != null){
				$sql = $sql." issue_d >= '$time1'";
			}
			if($time2 != null){
				$time2 = $time2." 23:59:59";
				if($time1 != null){
					$sql = $sql." AND issue_d <= '$time2' ";
				}else {
					$sql = $sql." issue_d <= '$time2' ";
				}
			}
			if($cod != null){
				if($time1 != null || $time2 != null){
					$sql = $sql." AND goods_cod = '$cod' ";
				}else{
					$sql = $sql." goods_cod = '$cod' ";
				}
			}
			
			if($serialno != null){
				if($time1 != null || $time2 != null || $cod != null){
					$sql = $sql." AND serialno = '$serialno' ";
				}else{
					$sql = $sql." serialno = '$serialno' ";
				}
			}
			$rs = $this->db->exceuteQuery($sql);
			return $rs;
	
		
	}
	/*function import_csv(){
		
			$filename = date('YmdHis',time()).".csv";
			
			$sql = "SELECT * FROM v_contact_form";
			$row = $this->db->exceuteQuery($sql);
			
		    $str = "ID,発行日,実装日期,製品コード,製番,型式,元数,AWｺｰﾄﾞ,AW協力会社名\n";
		    $str = iconv('utf-8','shift-jis',$str);
		    $lenth = count($row);
		    while($lenth){
    						
		        $ID = iconv('utf-8','shift-jis',$row[$lenth-1]['ID']);
		        $issue_d = iconv('utf-8','shift-jis',$row[$lenth-1]['issue_d']);
		        $cmpl_inst_d = iconv('utf-8','shift-jis',$row[$lenth-1]['cmpl_inst_d']);
		        $item_cod = iconv('utf-8','shift-jis',$row[$lenth-1]['item_cod']);
		        $m_no = iconv('utf-8','shift-jis',$row[$lenth-1]['m_no']);
		        $goods_cod = iconv('utf-8','shift-jis',$row[$lenth-1]['goods_cod']);
		        $m_o_q = iconv('utf-8','shift-jis',$row[$lenth-1]['m_o_q']);
		        $AWCode = iconv('utf-8','shift-jis',$row[$lenth-1]['AWCode']);
		        
		    	$str .= ID.",".
		    			$issue_d.",".
		    			$cmpl_inst_d.",".
		    			$item_cod.",".
		    			$m_no.",".
		    			$goods_cod.",".
		    			$m_o_q.",".
		    			$AWCode.",".
		    			$row[$lenth-1]['AWCompanyName']."\n";
		    	$lenth--;
		    }
		    
		    header("Content-type:text/csv");
		    header("Content-Disposition:attachment;filename=".$filename);
		    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		    header('Expires:0');
		    header('Pragma:public');
		    echo $str;
	}*/
	function import_csv($type,$select,$text1=NULL,$text2=NULL,$text3=NULL,$text4=NULL)
	{
	
	try { //echo (date("YmdH") ."<br/>". (int)date("Ymd").'18');exit();

		 $filename = date('YmdHis');
		 
		 if($type==0){
		 	
		 	$rs = $this->select_renraku($text1,$text2,$text3); 
		 	
		 }elseif($type==1)
		 {
		 	
		 	$rs = $this->select_shiji($select,$text1,$text2,$text3);
		 	
		 }else {
		 	
		 	$rs = $this->select_irai($text1,$text2,$text3,$text4);
		 	
		 }
		 
	  	 header("Content-Type: text/csv;charset=UTF-8");
	     header("Content-Disposition: attachment; filename=".$filename.".csv");
	     header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	     header('Expires:0');
	     header('Pragma:public');
	     
		 $str = "ID,発行日,実装日期,製品コード,製番,型式,元数,AWｺｰﾄﾞ,AW協力会社名\n";
		 
		 $str = mb_convert_encoding ($str, "shift-jis", "utf-8" );
		 
		 
		 
	     if (date("YmdHi")>(int)date("Ymd").'1800'){
	     	$time=date("Y/m/d",strtotime("+1 day"));
	     }else{
	     	$time=date("Y/m/d");
	     }
		function encodeUTF8($array) {
		foreach ( $array as $key => $value ) {
			if (! is_array ( $value )) {
				$array [$key] = mb_convert_encoding ( $value, "shift-jis", "utf-8" );
			} else {
				$array [$key] = encodeUTF8 ( $value );
			}
		}
			return $array;
		}
//echo "<pre>";print_r($rs);exit();
		$rs = encodeUTF8($rs);
	   // echo ',0,,,'.$time.',,,,'.$rs[0]['send_tel'].',,'.$rs[0]['send_zip_code'].','.$rs[0]['send_prefecture'].$rs[0]['send_address_1'].','.$rs[0]['send_address_2'].',,,'.$rs[0]['send_name'].',,,,'.$rs[0]['tel'].',,'.$rs[0]['zipCode'].','.$rs[0]['address'].',,'.$rs[0]['name'].',,,'.$rs[0]['codename'].',,,,,'.$rs[0]['send_remark'].',,,,037062,,,0556226511';
		//echo ',0,,,2011/10/20,,,,1-1-1,,060-0032,鍖楁捣閬撴湱骞屽競涓ぎ鍖哄寳浜屾潯鏉�-1-1,,,,灞辩敯澶儙,,,,0120-727-269,,400-0502,灞辨ⅷ鐪屽崡宸ㄦ懇閮″瘜澹窛鐢烘渶鍕濆1369-1,,銈ゃ兂銈偒銉笺儓銉儍銈搞亰瀹㈡鐩歌珖绐撳彛,,ink-004,銈ゃ兂銈偒銉笺儓銉儍銈革紣锛愶紨,,,,,,,,,037062,,,0556226511';
		$lenth = count($rs);
		 while($lenth){
		 	
		 		$rs[$lenth-1]['issue_d'] = substr($rs[$lenth-1]['issue_d'], 0,4)."-"
    						.substr($rs[$lenth-1]['issue_d'],5,2)."-"
    						.substr($rs[$lenth-1]['issue_d'],8,2);
    			$rs[$lenth-1]['cmpl_inst_d'] = substr($rs[$lenth-1]['cmpl_inst_d'], 0,4)."-"
    						.substr($rs[$lenth-1]['cmpl_inst_d'],5,2)."-"
    						.substr($rs[$lenth-1]['cmpl_inst_d'],8,2);
    						
		    	$str .= $rs[$lenth-1]['ID'].",".
		    			$rs[$lenth-1]['issue_d'].",".
		    			$rs[$lenth-1]['cmpl_inst_d'].",".
		    			$rs[$lenth-1]['item_cod'].",".
		    			$rs[$lenth-1]['m_no'].",".
		    			$rs[$lenth-1]['goods_cod'].",".
		    			$rs[$lenth-1]['m_o_q'].",".
		    			$rs[$lenth-1]['AWCode'].",".
		    			$rs[$lenth-1]['AWCompanyName']."\n";
		    	$lenth--;
		    }
		    echo $str;
		    
		}catch (SupportException $e){
		}
	
	}
}
 

?>