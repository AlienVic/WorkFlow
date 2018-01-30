<?php
	require_once('BaseBusiness.php');
	require_once('ConstUtil.php');
	
	
	
class IndexBusiness extends BaseBusiness
{
    /**
     * construct メソッドが定義する。<br>
     */
	
	
    function __construct()
    {
        $this->className = "IndexBusiness";

        parent::__construct();
        
    }
    
	function select_notice ($category)
	{
		$date = date('Y-m-d H:i:s',time());
		$c_id = $_SESSION['userinfo']['idm_company'];
		$sql = "SELECT  n.*
				,f.oldfilename
				,f.newfilename
		        FROM 
		        t_notice n
		        INNER JOIN 
		        t_files f
		        ON
		        n.idt_files=f.id
		        WHERE 
		        category='$category'
		        AND n.period_from < '$date'
		        AND n.period_to >'$date'
		        AND n.referrange = '$c_id'";
		$data = $this->db->exceuteQuery($sql);
		$length = count($data);
		
		/*<option value="1">生産</option>
      	<option value="2">技術</option>
      	<option value="3">その他</option>
      	<option value="4">試験用</option>
      	<option value="5">品質保証</option>*/
		
		
		if($length>0){
			
			if($category=="1"){
				$data[$length-1]['category'] = "生産";
				$data[$length-1]['creattime']=substr($data[$length-1]['creattime'], 0,4)."年"
    						.substr($data[$length-1]['creattime'],5,2)."月"
    						.substr($data[$length-1]['creattime'],8,2)."日";
			}else if($category=="2"){
				$data[$length-1]['category'] = "技術";
				$data[$length-1]['creattime']=substr($data[$length-1]['creattime'], 0,4)."年"
    						.substr($data[$length-1]['creattime'],5,2)."月"
    						.substr($data[$length-1]['creattime'],8,2)."日";
			}else if($category=="3"){
				$data[$length-1]['category'] = "その他";
				$data[$length-1]['creattime']=substr($data[$length-1]['creattime'], 0,4)."年"
    						.substr($data[$length-1]['creattime'],5,2)."月"
    						.substr($data[$length-1]['creattime'],8,2)."日";
			}else if($category=="4"){
				$data[$length-1]['category'] = "試験用";
				$data[$length-1]['creattime']=substr($data[$length-1]['creattime'], 0,4)."年"
    						.substr($data[$length-1]['creattime'],5,2)."月"
    						.substr($data[$length-1]['creattime'],8,2)."日";
			}else if($category=="5"){
				$data[$length-1]['category'] = "品質保証";
				$data[$length-1]['creattime']=substr($data[$length-1]['creattime'], 0,4)."年"
    						.substr($data[$length-1]['creattime'],5,2)."月"
    						.substr($data[$length-1]['creattime'],8,2)."日";
			}
			
			$length--;
		}
		
		return $data;		
	}
	function checkResult($id){
		if($id==0){							
				return "が発行されています。";
		}else if($id==1){
			return "が確認待ちなので、確認願います。";
		}else if($id==2){
			return "が承認待ちなので、承認願います。";
		}elseif($id==3){
			return "がリライト待ちなので、リライト願います。";
		}
	}
	
	
	function check_company($id){
		$sql = "SELECT t.appreviation 
				FROM m_company t
				INNER JOiN m_user u
				ON u.idm_company = t.ID
				WHERE u.id = '$id'";
		$data = $this->db->exceuteQuery($sql);
		return $data[0]['appreviation'];
				
	}
	function select_mno($ID)
	{
		$sql="SELECT DISTINCT	t.m_no											
					FROM	t_message msg														
					INNER JOIN 	t_form_head frm 														
					ON	msg.idt_form=frm.id AND msg.type=0 AND msg.overflg=0 
					INNER JOIN  t_contact_form t
					ON t.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	function select_new ($ID)
	{
		$sql = "(
					SELECT DISTINCT	t.ID,t.m_no,t.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result													
					FROM	t_message msg														
					INNER JOIN 	t_form_head frm 														
					ON	msg.idt_form=frm.id AND msg.type=0 AND msg.overflg=0 
					INNER JOIN  t_contact_form t
					ON t.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)
				UNION ALL
				(
					SELECT DISTINCT	d.ID,d.m_no,d.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result													
					FROM	t_message msg														
					INNER JOIN 	t_form_head frm 														
					ON	msg.idt_form=frm.id AND msg.type=0 AND msg.overflg=0 
					INNER JOIN  t_depend_form d
					ON d.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)
				UNION ALL
				(
					SELECT DISTINCT	i.ID,i.idm_urls m_no,i.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result													
					FROM	t_message msg														
					INNER JOIN 	t_form_head frm 														
					ON	msg.idt_form=frm.id AND msg.type=0 AND msg.overflg=0 
					INNER JOIN  t_instruct_form i
					ON i.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)";
		
		$data = $this->db->exceuteQuery($sql);
		
		
		$length = count($data);
		
		while ($length>0){
			$data[$length-1]['result'] = $this->checkResult($data[$length-1]['msgstus']);
			
			if($data[$length-1]['msgstus']==0)
			{
				$data[$length-1]['creater'] = $this->check_company($data[$length-1]['creater']);
			}
			
			$length--;
		}

		return $data;
	}
	
	function select_repic($ID)
	{
		$sql = "(
					SELECT 	DISTINCT t.ID,t.m_no,t.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result														
					FROM t_message msg														
					INNER JOIN	t_form_head frm														
					ON msg.idt_form=frm.id AND msg.type=1 AND msg.overflg=0 	
				    INNER JOIN  t_contact_form t
					ON t.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)
				UNION
				(
					SELECT 	DISTINCT d.ID,d.m_no,d.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result														
					FROM t_message msg														
					INNER JOIN	t_form_head frm														
					ON msg.idt_form=frm.id AND msg.type=1 AND msg.overflg=0 	
				    INNER JOIN  t_depend_form d
					ON d.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)
				UNION
				(
					SELECT 	DISTINCT i.ID,i.idm_urls m_no,i.idt_form_head,msg.formtype,msg.manageno,msg.status  msgstus,msg.editor res,frm.status,frm.creattime,frm.creater
					,msg.result														
					FROM t_message msg														
					INNER JOIN	t_form_head frm														
					ON msg.idt_form=frm.id AND msg.type=1 AND msg.overflg=0 	
				    INNER JOIN  t_instruct_form i
					ON i.idt_form_head = frm.ID														
					WHERE msg.receiverid='$ID'
				)";
		
		$data = $this->db->exceuteQuery($sql);
		
		
		$length = count($data);
		
		while ($length>0){
			$data[$length-1]['result'] = $this->checkResult($data[$length-1]['msgstus']);
			$length--;
		}
		

		return $data;
		
	}
	
	function check_date($date)
	{
		$hour = substr($date, 11,2);
		if($hour>12)
		{
			$date = substr($date, 0,4)."/"
    						.substr($date,5,2)."/"
    						.substr($date,8,2)."/ "
    						." PM ".substr($date,11,5);
    		return $date;
		}else if($hour<=12){
			$date = substr($date, 0,4)."/"
    						.substr($date,5,2)."/"
    						.substr($date,8,2)."/ "
    						."AM ".substr($date,11,5);
    		return $date;
		}
	}
	

	

}
?>
