<?php

require_once('BaseBusiness.php');
require_once('ConstUtil.php');



class TorokuBusiness extends BaseBusiness
{
    /**
     * construct メソッドが定義する。<br>
     */
	
	public $level ;
	public $typeform;
	static public $a=0;
	
    function __construct()
    {
        $this->className = "TorokuBussiness";

        parent::__construct();
        
    }

    //SELECT
	function select($TYPEVALUE,$level){
		$sql = "SELECT showtext
		        ,linkurl
		        FROM 
		        m_urls 
		        WHERE 
		        formtype='$TYPEVALUE'
		        AND
		        level='$level'";
		$data = $this->db->exceuteQuery($sql);
		return $data;		
	}

	
   	function select_toroku($level,$type)
   	{
   		if($type == 1){
   			$sql = "SELECT ID
		   	    ,showtext
		   	    ,linkurl
		        FROM 
		        m_urls 
		        WHERE
		        level='$level'
		        AND formtype='0'
		        AND userright!=1";
			$data = $this->db->exceuteQuery($sql);
			return $data;	
   		}else{
		   	$sql = "SELECT ID
		   	    ,showtext
		   	    ,linkurl
		        FROM 
		        m_urls 
		        WHERE
		        level='$level'
		   		AND formtype='0'";
			$data = $this->db->exceuteQuery($sql);
			return $data;	
   		}	
   	}
   	
   	
   	function select_product($value)
   	{
   			$sql = "SELECT 
   			goods_cod
   			FROM 
   			m_product
   			WHERE 
   			m_no='$value'";
   			
   			$data = $this->db->exceuteQuery($sql);
   			return $data;	
   	}
   
   	
	function select_project($projecet)
	{
		$projecet=ConstUtil::GetProject($projecet);
		return $projecet;
	}
	function select_cType($id)
	{
		$sql = "SELECT DISTINCT type 
				FROM m_company c
				INNER JOIN m_user u
				ON u.idm_company = c.id
				WHERE u.idm_company = '$id'";
		$data = $this->db->exceuteQuery($sql);
		
		return $data;
	}
	
	function select_torokuMessage()
	{
		$c_id = $_SESSION['userinfo']['idm_company'];
		$sql = "SELECT type FROM m_company WHERE ID = '$c_id'";
		$type = $this->db->exceuteQuery($sql);
		$ID = $_SESSION['userinfo']['ID'];
		if($type[0][0]=="0"){

		$sql="SELECT
			  DISTINCT
				*
			FROM
		(
		(
			SELECT
				m.manageno,
				m.idt_form_head,
				m.issuedate,
				m.goods_cod,
				m.m_no,
				m.ID,
				m.companyname,
				h. STATUS,
				h.creater,
				h.creattime
			FROM
				t_contact_form m
			INNER JOIN t_form_head h ON h.id = m.idt_form_head AND h.status != '0'
			INNER JOIN t_message msg ON msg.overflg = '0'
			AND formtype = '0'
			AND receiverid = '$ID'
			AND msg.idt_form = h.id
			AND msg.result = '0'
			
		)
		UNION ALL
			(
				SELECT
					m.manageno,
					m.idt_form_head,
					m.issuedate,
					m.goods_cod,
					m.m_no,
					m.ID,
					m.companyname,
					h. STATUS,
					h.creater,
					h.creattime
				FROM
					t_contact_form m
				INNER JOIN t_form_head h ON h.id = m.idt_form_head
				WHERE
					h.creater = '$ID'
				AND(
				    h.status='0'
				    OR h. STATUS = '1'                
				)
			)
	)rstab
		ORDER BY
			STATUS ASC,
			creattime DESC";//改动
		
		$rs = $this->db->exceuteQuery($sql);
		
		return $rs;
		}else {
			$sql = "SELECT
			  DISTINCT
				*
			FROM
		(
		(
			SELECT
				m.manageno,
				m.idt_form_head,
				m.issuedate,
				m.goods_cod,
				m.m_no,
				m.ID,
				m.companyname,
				h. STATUS,
				h.creater,
				h.creattime,
				msg.result
			FROM
				t_contact_form m
			INNER JOIN t_form_head h ON h.id = m.idt_form_head AND h.status != '0'
			INNER JOIN t_message msg ON msg.overflg = '0'
			AND formtype = '0'
			AND receiverid = '$ID'
			AND msg.idt_form = h.id
			AND msg.result = '0'
			
		)
		UNION ALL
			(
				SELECT
					m.manageno,
					m.idt_form_head,
					m.issuedate,
					m.goods_cod,
					m.m_no,
					m.ID,
					m.companyname,
					h. STATUS,
					h.creater,
					h.creattime,
					msg.result
				FROM
					t_contact_form m
				INNER JOIN t_form_head h ON h.id = m.idt_form_head
				INNER JOIN t_message msg ON h.id = msg.idt_form
				WHERE
					h.bacceptperson = '$ID'
				AND
					msg.overflg = '0'
				AND
					msg.result != '0'
				AND(
				    h.status='5'               
				)
			)
	)rstab
		ORDER BY
			STATUS ASC,
			creattime DESC";
		}
		
		$rs = $this->db->exceuteQuery($sql);
		
		return $rs;
		
	}
	
		
	function select_all($m_no){
			$sql = "SELECT
					badpictureflg
					FROM
					t_contact_form
					WHERE
					m_no = '$m_no'
					";
			$rs = $this->db->exceuteQuery($sql);
			
			if($rs[0][0]==1){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					,newfilename
				FROM t_contact_form
				INNER JOIN t_files
				ON t_files.ID = t_contact_form.idt_files
		        WHERE
		        m_no='$m_no'";
				
				$data = $this->db->exceuteQuery($sql);
				return $data;
			}else{
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
				FROM t_contact_form
		        WHERE
		        m_no='$m_no'";
				
				$data = $this->db->exceuteQuery($sql);
				return $data;
			}	
	}
	
	function select_file_id_name($ID){
		$sql = "SELECT f.ID,
				f.newfilename
				FROM t_files f
				INNER JOIN t_contact_form t
				ON t.idt_files = f.ID
				WHERE t.ID = '$ID'";
		$data = $this->db->exceuteQuery($sql);
		return $data[0];
	}
	
	function select_file_id_name1($ID){
		$sql = "SELECT f.ID,
				f.newfilename
				FROM t_files f
				INNER JOIN t_contact_form t
				ON t.idt_files_ins = f.ID
				WHERE t.ID = '$ID'";
		$data = $this->db->exceuteQuery($sql);
		return $data[0];
	}
	
	function check_img($ID)
	{
		$sql = "SELECT t.idt_files ,
					   t.idt_files_ins
				FROM t_contact_form t
				WHERE t.ID='$ID'";
		$data = $this->db->exceuteQuery($sql);
		if(($data[0]['idt_files']==null|| $data[0]['idt_files']==0) &&($data[0]['idt_files_ins']==null || $data[0]['idt_files_ins']==0))
		{
			return 0;
		}
		elseif(($data[0]['idt_files']==null|| $data[0]['idt_files']==0)&&($data[0]['idt_files_ins']!=null&&$data[0]['idt_files_ins']!=0))
		{
			return 2;
		}
		elseif(($data[0]['idt_files']!=null && $data[0]['idt_files']!=0)&&($data[0]['idt_files_ins']==null || $data[0]['idt_files_ins']==0))
		{
			return 3;
		}elseif(($data[0]['idt_files']!=null && $data[0]['idt_files']!=0)&&($data[0]['idt_files_ins']!=null&&$data[0]['idt_files_ins']!=0))
		{
			return 1;
		}
	}
	
	function select_all_admin($ID,$a,$img=1){
		if($a==1 && $img==3){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					,t.newfilename
					FROM t_contact_form
				INNER JOIN t_files t
				ON t.ID = t_contact_form.idt_files
				WHERE
		        t_contact_form.ID='$ID'";
			$data = $this->db->exceuteQuery($sql);
			return $data;
		}
	elseif($a==1 && $img==2){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					FROM t_contact_form
				WHERE
		        t_contact_form.ID='$ID'";
			$data = $this->db->exceuteQuery($sql);
			return $data;
		}
		elseif($a==2 && $img==2){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					,m.newfilename new2
				FROM t_contact_form
				INNER JOIN t_files m
				ON m.ID = t_contact_form.idt_files_ins
		        WHERE
		        t_contact_form.ID='$ID'";
		$data = $this->db->exceuteQuery($sql);
		return $data;		
		}elseif($a==2 && $img==1){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					,t.newfilename new1
					,m.newfilename new2
				FROM t_contact_form
				INNER JOIN t_files t
				ON t.ID = t_contact_form.idt_files
				INNER JOIN t_files m
				ON m.ID = t_contact_form.idt_files_ins
		        WHERE
		        t_contact_form.ID='$ID'";
		$data = $this->db->exceuteQuery($sql);
		return $data;		
		}elseif($a==2 && $img==3){
			$sql = "SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					,t.newfilename new1
				FROM t_contact_form
				INNER JOIN t_files t
				ON t.ID = t_contact_form.idt_files
		        WHERE
		        t_contact_form.ID='$ID'";
		$data = $this->db->exceuteQuery($sql);
		return $data;		
		}
		elseif($img==0)
		{
			$sql ="SELECT t_contact_form.ID
					,manageno
					,issuedate
					,companyname
					,idm_company
					,goods_cod
					,m_no
					,partcode
					,specification
					,project
					,device
					,lotnum
					,badnum
					,uncheckednum
					,manualnum
					,badproportion
					,stopline
					,stoplineflg
					,badcontent
					,badpictureflg
					,idt_files
					,idt_form_head
					,t_contact_form.delflag
					,instruction
					,attachmentflg
					,idt_files_ins
					FROM t_contact_form
					WHERE
		       		t_contact_form.ID='$ID'
					";
			$data = $this->db->exceuteQuery($sql);
			return $data;
		}
	}

	//UPDATE
	function Update_head($date,$id2)
	{
		  $data = array(
		          "contacttime" => $date
		  		  ,"status"=>2
		  );
		  parent::Update('t_form_head', $data,"u.ID='$id2'");
	}
	

	function Update1($data)
	{
		parent::Update('t_contact_form', $data,"u.ID='$data[ID]'");
	}
	
	
	function Update_admit($data)
	{
		parent::Update('t_contact_form', $data,"u.ID='$data[ID]'");
	}
	
	function Updata_head($ID,$result)
	{
		if($result==0){
			$data = array(
						"affirmanttime"=> date('Y-m-d H:i:s',time()),
		                "affirmantperson"=>$_SESSION['userinfo']['ID'],
						"affirmantresult"=>0,
						"status"=>3
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==1){
			$data = array(
						"affirmanttime"=> date('Y-m-d H:i:s',time()),
		                //"affirmantperson"=>$_SESSION['userinfo']['ID'],
						"affirmantresult"=>1,
						"status"=>0
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==2){
			$data = array(
						"approvetime"=> date('Y-m-d H:i:s',time()),
		                "approveperson"=>$_SESSION['userinfo']['ID'],
						"approveresult"=>0,
						"status"=>4
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==3){
			$data = array(
						"approvetime"=> date('Y-m-d H:i:s',time()),
		                //"approveperson"=>$_SESSION['userinfo']['ID'],
						"approveresult"=>1,
						"status"=>0
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==4){
			$data = array(
						"baccepttime"=> date('Y-m-d H:i:s',time()),
		                "bacceptperson"=>$_SESSION['userinfo']['ID'],
						"bacceptresult"=>0,
						"status"=>5
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==5){
			$data = array(
						"baccepttime"=> date('Y-m-d H:i:s',time()),
		                //"bacceptperson"=>$_SESSION['userinfo']['ID'],
						"bacceptresult"=>1,
						"status"=>0
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==6){
			$data = array(
						"bconfirmtime"=> date('Y-m-d H:i:s',time()),
		                "bconfirmperson"=>$_SESSION['userinfo']['ID'],
						"bconfirmresult"=>0,
						"status"=>6
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==7){
			$data = array(
						"bconfirmtime"=> date('Y-m-d H:i:s',time()),
		                //"bconfirmperson"=>$_SESSION['userinfo']['ID'],
						"bconfirmresult"=>1,
						"status"=>5
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==8){
			$data = array(
						"badmittime"=> date('Y-m-d H:i:s',time()),
		                "badmitperson"=>$_SESSION['userinfo']['ID'],
						"badmitresult"=>0,
						"status"=>7
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==9){
			$data = array(
						"badmittime"=> date('Y-m-d H:i:s',time()),
		                //"badmitperson"=>$_SESSION['userinfo']['ID'],
						"badmitresult"=>1,
						"status"=>5
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}elseif ($result==10){
			$data = array(
						"endtime"=> date('Y-m-d H:i:s',time()),
		                "endperson"=>$_SESSION['userinfo']['ID'],
						"endresult"=>0,
						"status"=>11
					);
		
			parent::Update('t_form_head', $data,"u.ID='$ID'");
		}
		return $data;
	
	}

	//INSERT
	function Insert($data)
	{
		
		$rs = parent::Insert('t_contact_form', $data);
		return $rs;
	}
	
	function Insert_file($oldname,$newname)
	{
		$data = array(
				"creattime" =>date('Y-m-d H:i:s',time()),
				"creater"=>$_SESSION['userinfo']['ID'],
				"oldfilename" =>$oldname,
				"newfilename"=>$newname,
				"delflag"=>0
		);
		$rs = parent::Insert('t_files', $data);
		
		$sql = "SELECT ID FROM t_files WHERE newfilename = '$newname'";
		
		
		$rs = $this->db->exceuteQuery($sql);
		
		return $rs[0][0];
	}
	
	function Updata_file($ID)
	{
		$data = array(
			"editor" =>$_SESSION['userinfo']['ID'],
			"edittime"=>date('Y-m-d H:i:s',time())
		);
		
		parent::Update('t_file', $data,"u.ID='$ID'");
	}
	
	function Insert_head($id,$date)
	{
		
		$data  = array(
						"creattime"=>$date
					   ,"creater"=>$id
					   ,"editor"=>$id
					   ,"edittime"=>$date
					   ,"status"=>0
						);
		
		
		$rs = parent::Insert('t_form_head',$data);
		
		
		
		$sql = "SELECT ID FROM t_form_head WHERE creattime='$date' AND editor = '$id'";
	    $rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	
	function Insert_massage($id2,$idcompany)
	{
		
		
		$sql = "SELECT u.ID 
		        FROM m_user u 
		        INNER JOIN m_company com 
		        ON u.idm_company=com.id 
		        WHERE (u.userright = '1'  OR u.userright='2')   
		        AND  com. Type = '0'  
		        AND u.idm_company='$idcompany'";
		$rs = $this->db->exceuteQuery($sql);
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>2
		    			  ,"type"=>1
		    			  ,"creater"=>$rs1[0]['creater']
		    			  ,"editor"	=>$rs1[0]['editor']
		    			  ,"creattime"=>$rs1[0]['creattime']
		    			  ,"edittime"=>$rs1[0]['edittime']
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>1
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$rs1[0]['creater']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
		
	}
	
	
	function Insert_massage1($id2,$idcompany="")
	{
		
		
		$sql = "SELECT u.ID
		        FROM m_user u 
		        INNER JOIN m_company com 
		        ON u.idm_company=com.id 
		        WHERE u.userright='2'   
		        AND  com. Type = '0'  
		        AND u.idm_company='$idcompany'";
			
		$rs = $this->db->exceuteQuery($sql);
		$date = date('Y-m-d H:i:s',time());
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		while ($lengh)
		{
			$data = array ("sendstatus"=>2
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>2
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
		
	}
	
	function Insert_massage2($id2,$idcompany="")
	{
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
					   ,h.creater
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$date = date('Y-m-d H:i:s',time());
		
		$data = array ("sendstatus"=>3
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>3
		    			  ,"result"=>2
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs1[0]['creater']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);
		
		return $rs1;
		
	}
	
	function Insert_massage4($id2,$idcompany="")
	{
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
					   ,h.creater
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$date = date('Y-m-d H:i:s',time());
		
		$data = array ("sendstatus"=>4
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>3
		    			  ,"result"=>3
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs1[0]['creater']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);
		
		return $rs1;
		
	}
	
	
	function Insert_massage6($id2,$idcompany="")
	{
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
					   ,h.creater
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$date = date('Y-m-d H:i:s',time());
		
		$data = array ("sendstatus"=>5
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>3
		    			  ,"result"=>1
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs1[0]['creater']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);
		
		return $rs1;
		
	}

	
	function Insert_massage8($id2,$idcompany="")
	{
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
					   ,h.bacceptperson
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$date = date('Y-m-d H:i:s',time());
		
		$data = array ("sendstatus"=>5
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>0
		    			  ,"result"=>2
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs1[0]['bacceptperson']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);
		
		return $rs1;
		
	}	

	
	function Insert_massage10($id2,$idcompany="")
	{
		
		$sql1 = "SELECT 
						h.ID
					   ,t.manageno
					   ,h.bacceptperson
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$date = date('Y-m-d H:i:s',time());
		
		$data = array ("sendstatus"=>7
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>0
		    			  ,"result"=>3
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs1[0]['bacceptperson']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);
		
		return $rs1;
		
	}	
	
	function Insert_massage3($id2,$idcompany="")
	{
		
		
		$sql = "SELECT  u.ID																				
				FROM 	m_user u																								
				INNER JOIN 	m_company com																								
				ON u.idm_company=com.id																						
				WHERE u.userright = '0'   AND  com. Type = '1'																								
				";
		$rs = $this->db->exceuteQuery($sql);
		
		
		$date = date('Y-m-d H:i:s',time());
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>4
		    			  ,"type"=>0
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>0
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
		
	}
	
	
	function Insert_massage5($id2,$idcompany="")
	{
		
		$sql = "SELECT 	u.ID							
				 FROM 		m_user  u							
				 WHERE		u.idm_company = '$idcompany'                
				 AND		(u.userright = '1'  OR u.userright='2') AND u.proxyflg= '0'							
				 UNION  ALL 									
				 SELECT 		u.proxyid							
				 FROM 		m_user  u							
				 WHERE		u.idm_company ='$idcompany'                
				 AND		(u.userright = '1'  OR u.userright='2')   AND u.proxyflg= '1'";
		
		$rs = $this->db->exceuteQuery($sql);
		
		$date = date('Y-m-d H:i:s',time());
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>5
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>1
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
	}
	
	function Insert_massage7($id2,$idcompany="")
	{
		
		$sql = "SELECT	u.ID												
				FROM 	m_user u																							
				INNER JOIN 	m_company com																							
				ON  u.idm_company=com.id																							
				WHERE 	u.userright='2'   AND  com. Type = '1'  AND u.idm_company='1'";
		
		$rs = $this->db->exceuteQuery($sql);
		
		$date = date('Y-m-d H:i:s',time());
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>6
		    			  ,"type"=>1
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>2
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
	}
	
	function Insert_massage9($id,$id2,$idcompany="")
	{
		
		$sql = "SELECT
				u.ID
				FROM
				m_user u
				INNER JOIN m_company com ON u.idm_company = com.id
				WHERE
				u.userright = '0'
				AND com.Type = '0'
				AND u.idm_company =(
				SELECT
				idm_company
				FROM
				t_contact_form
				WHERE
				id = '$id'
				)";
		
		$rs = $this->db->exceuteQuery($sql);
		
		$date = date('Y-m-d H:i:s',time());
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>7
		    			  ,"type"=>0
		    			  ,"creater"=>$_SESSION['userinfo']['ID']
		    			  ,"editor"	=>$_SESSION['userinfo']['ID']
		    			  ,"creattime"=>$date
		    			  ,"edittime"=>$$date
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>0
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$_SESSION['userinfo']['ID']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
	}
	
	function Insert_admit_massage($id2,$idcompany)
	{
		
		
		$sql = "SELECT u.ID 
		        FROM m_user u 
		        INNER JOIN m_company com 
		        ON u.idm_company=com.id 
		        WHERE (u.userright = '1'  OR u.userright='2')   
		        AND  com. Type = '0'  
		        AND u.idm_company='$idcompany'";
		$rs = $this->db->exceuteQuery($sql);
		
		$sql1 = "SELECT h.creater
					   ,h.editor
					   ,h.creattime
					   ,h.edittime
					   ,h.ID
					   ,t.manageno
			     FROM t_form_head h
			     INNER JOIN t_contact_form t
			     ON h.ID = t.idt_form_head
			     WHERE h.ID='$id2'";
		$rs1 = $this->db->exceuteQuery($sql1);
		
		$lengh = count($rs);

		
		while ($lengh)
		{
			$data = array ("sendstatus"=>2
		    			  ,"type"=>1
		    			  ,"creater"=>$rs1[0]['creater']
		    			  ,"editor"	=>$rs1[0]['editor']
		    			  ,"creattime"=>$rs1[0]['creattime']
		    			  ,"edittime"=>$rs1[0]['edittime']
		    			  ,"manageno"=>$rs1[0]['manageno']
		    			  ,"formtype"=>0
		    			  ,"idt_form"=>$rs1[0]['ID']
		    			  ,"status"=>2
		    			  ,"result"=>0
		    			  ,"delflag	"=>0
		    			  ,"senderid"=>$rs1[0]['creater']
		    			  ,"receiverid"=>$rs[$lengh-1]['ID']
		    			  ,"overflg"=>0
						);	
		    $id = parent::Insert('t_message', $data);

			$lengh--;
		}
		
		return $rs1;
		
	}
	
	
	//check
	function check_data($data,$id)
	{
		if($data['lotnum']==null||$data['lotnum']==0){
			$data['lotnum']="-";
		}
		if($data['badnum']==null||$data['badnum']==0){
			$data['badnum']="-";
		}
		if($data['uncheckednum']==null||$data['uncheckednum']==0){
			$data['uncheckednum']="-";
		}
		if($data['manualnum']==null||$data['manualnum']==0){ 
			$data['manualnum']="-";
		}
		if($data['badproportion']==null||$data['badproportion']==0){
			$data['badproportion']="-";
		}
		if($data['stopline']==null){
		    $data['stopline']="無";
		}
		
		
		if($id==0) $data['project']="検品";
		else if($id==1) $data['project']="前加工";
		else if($id==2) $data['project']="SMT";
        else if($id==3) $data['project']="ﾘﾌﾛｰ炉"; 
		else if($id==4) $data['project']="画像検査"; 
		else if($id==5) $data['project']="自動挿入"; 
		else if($id==6) $data['project']="先付け"; 
		else if($id==7) $data['project']="手挿入"; 
		else if($id==8) $data['project']="一次ディップ"; 
		else if($id==9) $data['project']="自動ﾘｰﾄﾞｶｯﾄ"; 
		else if($id==10) $data['project']="一次後付け"; 
		else if($id==11) $data['project']="二次ディップ"; 
		else if($id==12) $data['project']="二次後付け"; 
		else if($id==13) $data['project']="修正"; 
		else if($id==14) $data['project']="ICT"; 
		else if($id==15) $data['project']="ＦＣＴ"; 
		else if($id==16) $data['project']="火入れ"; 
		else if($id==17) $data['project']="調整"; 
		else if($id==18) $data['project']="耐湿処理"; 
		else if($id==19) $data['project']="ﾎﾞﾝﾄﾞ塗布"; 
		else if($id==20) $data['project']="組み立て"; 
		else if($id==21) $data['project']="絶縁・耐圧";
		else if($id==22) $data['project']="ｴｰｼﾞﾝｸﾞ"; 
		else if($id==23) $data['project']="最終検査"; 
		else if($id==24) $data['project']="外観検査"; 
		else if($id==25) $data['project']="梱包"; 
		else if($id==26) $data['project']="日常点検"; 
		else if($id==27) $data['project']="設備校正"; 
		 
		
		return $data;
	}
	
	function  check_status($status)
	{
		if($status=="0"||$status=="1"||$status=="2"||$status=="3")
		{
			return "（協）処理中" ;
		}
		elseif ($status=="4")
		{
			return "（協）処理済";
		}
		else if($status=="5"||$status=="6")
		{
			return "（Ｅ）処理中";
		}
		else if($status=="7")
		{
			return "（E）承認→（協）未受領";
		}
		else if($status=="11")
		{
			return "（協）受領  すべての完成";
		}
	}
	
    //other	
	function user_message()
	{
		$date = date('Y年m月d日',time());
		//print_r($date);
		//print_r($_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2']);
		
		$user = array(
						"time"=>$date,
						"name"=>$_SESSION['userinfo']['username1'].$_SESSION['userinfo']['username2']
					);
					
		return $user;
	}
	
	
	function get_last_user_message($ID)
	{
		$sql = "SELECT 
				h.affirmanttime
			   ,h.affirmantperson
			   ,u.username1
			   ,u.username2
			   FROM 
			   t_form_head h
			   INNER JOIN 
			   t_contact_form m
			   ON m.id = '$ID'
			   AND m.idt_form_head = h.id
			   INNER JOIN 
			   m_user u
			   ON h.affirmantperson = u.id";
		
		
		$rs = $this->db->exceuteQuery($sql);
		
		if($rs[0]['affirmantperson']==NULL)
		{
			$rs[0]['affirmantperson']= -1;
		}
		return $rs;
	}
	
	function get_last_user_message1($ID)
	{
		$sql = "SELECT 
				h.approvetime
			   ,h.approveperson
			   ,u.username1
			   ,u.username2
			   FROM 
			   t_form_head h
			   INNER JOIN 
			   t_contact_form m
			   ON m.id = '$ID'
			   AND m.idt_form_head = h.id
			   INNER JOIN 
			   m_user u
			   ON h.approveperson = u.id";
		
		
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function get_EAT_user_message($ID,$id)
	{
		if($id==0){
		$sql = "SELECT 
				h.baccepttime
			   ,h.bacceptperson
			   ,u.username1
			   ,u.username2
			   FROM 
			   t_form_head h
			   INNER JOIN 
			   t_contact_form m
			   ON m.id = '$ID'
			   AND m.idt_form_head = h.id
			   INNER JOIN 
			   m_user u
			   ON h.bacceptperson = u.id";
		
			   $rs = $this->db->exceuteQuery($sql);
		
			   
			   $rs[0]['baccepttime'] = substr($rs[0]['baccepttime'], 0,4)."年"
    						.substr($rs[0]['baccepttime'],5,2)."月"
    						.substr($rs[0]['baccepttime'],8,2)."日";
               $rs[0]['username1'] = $rs[0]['username1'].$rs[0]['username2'];
			   return $rs[0];
			   
		}else if ($id==1){
				$sql = "SELECT 
						h.bconfirmtime
					   ,h.bconfirmperson
					   ,u.username1
					   ,u.username2
					   FROM 
					   t_form_head h
					   INNER JOIN 
					   t_contact_form m
					   ON m.id = '$ID'
					   AND m.idt_form_head = h.id
					   INNER JOIN 
					   m_user u
					   ON h.bconfirmperson = u.id";
				$rs = $this->db->exceuteQuery($sql);
				
				 $rs[0]['bconfirmtime'] = substr($rs[0]['bconfirmtime'], 0,4)."年"
    						.substr($rs[0]['bconfirmtime'],5,2)."月"
    						.substr($rs[0]['bconfirmtime'],8,2)."日";
               $rs[0]['username1'] = $rs[0]['username1'].$rs[0]['username2'];
               if($rs[0]['bconfirmperson']==NULL)
               {
               		$rs[0]['bconfirmperson']=-1;
               }
               
			   return $rs[0];
		}else if ($id==2){
				$sql = "SELECT 
						h.badmittime
					   ,h.badmitperson
					   ,u.username1
					   ,u.username2
					   FROM 
					   t_form_head h
					   INNER JOIN 
					   t_contact_form m
					   ON m.id = '$ID'
					   AND m.idt_form_head = h.id
					   INNER JOIN 
					   m_user u
					   ON h.badmitperson = u.id";
				$rs = $this->db->exceuteQuery($sql);
				
				 $rs[0]['badmittime'] = substr($rs[0]['badmittime'], 0,4)."年"
    						.substr($rs[0]['badmittime'],5,2)."月"
    						.substr($rs[0]['badmittime'],8,2)."日";
               $rs[0]['username1'] = $rs[0]['username1'].$rs[0]['username2'];
			   return $rs[0];
		}
	}
	
	
	function Reverse_writing($ID,$idcompany,$a,$b){
		If($a==0){
			if($b==0){
			$sql = "UPDATE   t_message msg SET   msg.overflg='1' WHERE msg.receiverid  
					IN ( SELECT ID FROM 
					     m_user  u WHERE idm_company = '$idcompany' AND userright = '1'
					     UNION  ALL 
					     SELECT u.proxyid   ID FROM m_user  u WHERE u.idm_company = '$idcompany' 
					     AND u.userright ='1'
					   ) AND msg.sendstatus = '2'   AND msg.overflg='0' 
					     AND msg.idt_form='$ID'
					     AND msg.formtype='0'
					  ";
			  $rs = $this->db->exceuteUpdate($sql);
			}else{
			$sql = "UPDATE   t_message msg SET   msg.overflg='1' WHERE msg.receiverid  
					IN ( SELECT ID FROM 
					     m_user  u WHERE idm_company = '$idcompany' AND ( u.userright ='1' OR  u.userright ='2')
					     UNION  ALL 
					     SELECT u.proxyid   ID FROM m_user  u WHERE u.idm_company = '$idcompany' 
					     AND ( u.userright ='1' OR  u.userright ='2')
					   ) AND msg.sendstatus = '2'   AND msg.overflg='0' 
					     AND msg.idt_form='$ID'
					     AND msg.formtype='0'
					  ";
			  $rs = $this->db->exceuteUpdate($sql);
			}
		}elseif ($a==1)
		{   if($b==0){
			$sql = "UPDATE   t_message msg SET   msg.overflg='1' WHERE
					msg.receiverid  IN	( SELECT ID   FROM   m_user  u 
				    WHERE  idm_company = '$idcompany' AND  (userright = '2' or userright = '1')
					UNION  ALL   SELECT  u.proxyid   ID
					FROM  m_user  u WHERE u.idm_company = '$idcompany' AND  (userright = '2' or userright = '1')
					) AND 
					(msg.sendstatus = '2'   OR   msg.sendstatus = '3' )  AND msg.overflg='0' 
				    AND msg.idt_form='$ID'
					AND msg.formtype='0'
					  ";			
		   $rs = $this->db->exceuteUpdate($sql);
		}else{
			$sql = "UPDATE   t_message msg SET   msg.overflg='1' WHERE
					msg.receiverid  IN	( SELECT ID   FROM   m_user  u 
				    WHERE  idm_company = '$idcompany' AND  ( u.userright ='1' OR  u.userright ='2')
					UNION  ALL   SELECT  u.proxyid   ID
					FROM  m_user  u WHERE u.idm_company = '$idcompany' AND ( u.userright ='1' OR  u.userright ='2')
					) AND 
					(msg.sendstatus = '2'   OR   msg.sendstatus = '3' )  AND msg.overflg='0' 
				    AND msg.idt_form='$ID'
					AND msg.formtype='0'
					  ";			
		   $rs = $this->db->exceuteUpdate($sql);
		}
		}elseif ($a==2)
		{   if($b==0){
			$sql = "UPDATE   t_message msg	SET   msg.overflg='1' WHERE  msg.receiverid  
					IN ( SELECT   ID  FROM   m_user  u  WHERE idm_company = '$idcompany' 
					AND userright = '0' UNION  ALL 
					SELECT u.proxyid   ID  FROM  m_user  u  WHERE
					u.idm_company = '$idcompany' AND
					u.userright ='0')
					AND  (msg.sendstatus = '4' OR msg.sendstatus = '6' OR msg.sendstatus = '7')
					AND msg.overflg='0' AND msg.idt_form='$ID'
				    AND msg.formtype='0'";
			
		   $rs = $this->db->exceuteUpdate($sql);
			}else{
			$sql = "UPDATE   t_message msg	SET   msg.overflg='1' WHERE  msg.receiverid  
					IN ( SELECT   ID  FROM   m_user  u  WHERE idm_company = '$idcompany' 
					AND u.userright ='0'  UNION  ALL 
					SELECT u.proxyid   ID  FROM  m_user  u  WHERE
					u.idm_company = '$idcompany' AND
					 u.userright ='0')
					AND  msg.sendstatus = '4'    AND msg.overflg='0' AND msg.idt_form='$ID'
				    AND msg.formtype='0'";
			
		   $rs = $this->db->exceuteUpdate($sql);
			}
		}elseif ($a==3)
		{
			if($b==0){
			$sql = "UPDATE   t_message msg
					SET   msg.overflg='1'
					WHERE
					msg.receiverid  
					IN
					( SELECT 
					  ID 
					  FROM 
					  m_user  u 
					  WHERE
					  idm_company = '$idcompany' 
					  AND
					  userright = '1'
					  UNION  ALL 
					  SELECT 
					  u.proxyid   ID
					  FROM 
					  m_user  u
					  WHERE
					  u.idm_company = '$idcompany' 
					  AND
					  u.userright ='1'
					 )
					 AND 
					 msg.sendstatus = '5'    AND msg.overflg='0' 
					 AND msg.idt_form='$ID'
					 AND msg.formtype='0'
					  ";
			
		   $rs = $this->db->exceuteUpdate($sql);}
				else{
						$sql = "UPDATE   t_message msg
								SET   msg.overflg='1'
								WHERE
								msg.receiverid  
								IN
								( SELECT 
								  ID 
								  FROM 
								  m_user  u 
								  WHERE
								  idm_company = '$idcompany' 
								  AND
								  ( u.userright ='1' OR  u.userright ='2')
								  UNION  ALL 
								  SELECT 
								  u.proxyid   ID
								  FROM 
								  m_user  u
								  WHERE
								  u.idm_company = '$idcompany' 
								  AND
								 ( u.userright ='1' OR  u.userright ='2')
								 )
								 AND 
								 msg.sendstatus = '5'    AND msg.overflg='0' 
								 AND msg.idt_form='$ID'
								 AND msg.formtype='0'
								  ";
			
		  			 $rs = $this->db->exceuteUpdate($sql);
				}
		}else if($a==4){
			if($b==0){
			$sql = "UPDATE   t_message msg
					SET   msg.overflg='1'
					WHERE
					msg.receiverid  
					IN
					( SELECT 
					  ID 
					  FROM 
					  m_user  u 
					  WHERE
					  idm_company = '$idcompany' 
					  AND
					  (userright = '2' or userright = '1')
					  UNION  ALL 
					  SELECT 
					  u.proxyid   ID
					  FROM 
					  m_user  u
					  WHERE
					  u.idm_company = '$idcompany' 
					  AND
					  (u.userright ='2' or userright = '1')
					 )
					 AND 
					 (msg.sendstatus = '5'   OR   msg.sendstatus = '6' )   AND msg.overflg='0' 
					 AND msg.idt_form='$ID'
					 AND msg.formtype='0'
					  ";
			
		      $rs = $this->db->exceuteUpdate($sql);
			}
			else {
			$sql = "UPDATE   t_message msg
					SET   msg.overflg='1'
					WHERE
					msg.receiverid  
					IN
					( SELECT 
					  ID 
					  FROM 
					  m_user  u 
					  WHERE
					  idm_company = '$idcompany' 
					  AND
					 	 ( u.userright ='1' OR  u.userright ='2')
					  UNION  ALL 
					  SELECT 
					  u.proxyid   ID
					  FROM 
					  m_user  u
					  WHERE
					  u.idm_company = '$idcompany' 
					  AND
				     ( u.userright ='1' OR  u.userright ='2')
					 )
					 AND 
					 (msg.sendstatus = '5'   OR   msg.sendstatus = '6' )   AND msg.overflg='0' 
					 AND msg.idt_form='$ID'
					 AND msg.formtype='0'
					  ";
			
		   $rs = $this->db->exceuteUpdate($sql);
		}
		}
		
		else if ($a==5){
			$sql = "
					UPDATE t_message msg
					SET msg.overflg = '1'
					WHERE
						msg.receiverid IN(
							SELECT
								ID
							FROM
								m_user u
							WHERE
								idm_company = '$idcompany'
							AND userright = '0'
							UNION ALL
								SELECT
									u.proxyid ID
								FROM
									m_user u
								WHERE
									u.idm_company = '$idcompany'
								AND u.userright = '0'
						)
					AND msg.sendstatus = '7'
					AND msg.overflg = '0'
					AND msg.idt_form = '$ID'
					AND msg.formtype = '0'";
					   
			$rs = $this->db->exceuteUpdate($sql);
		}
	}
	function select_manageno($ID)
	{
		$sql = "SELECT m.ID,m.manageno
				FROM t_contact_form m
				WHERE  m.ID='$ID'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function select_result_message($ID)
	{
		$sql = "SELECT attachmentflg
				FROM t_contact_form
				WHERE ID='$ID'";
		$data= $this->db->exceuteQuery($sql);
		if($data[0][0]==0){
			$sql = "SELECT
		 		 m.ID
		 		,m.manageno
		 		,m.idt_form_head 
		 		,m.instruction
		 		,m.attachmentflg
        		FROM t_contact_form m
        		WHERE m.ID='$ID'";
			$rs = $this->db->exceuteQuery($sql);
			return $rs;
		}else{
		 	$sql = "SELECT
		 		 m.ID
		 		,m.manageno
		 		,m.idt_form_head 
		 		,m.instruction
		 		,m.attachmentflg
		 		,t.newfilename
        		FROM t_contact_form m
        		INNER JOIN t_files t
        		ON t.ID = m.idt_files_ins
        		WHERE m.ID='$ID'";
			$rs = $this->db->exceuteQuery($sql);

			return $rs;
		}
	}
	
}


?>