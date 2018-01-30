<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');
require_once('LoginBusiness.php');

class SetumeiBusiness extends BaseBusiness
{
	var $uploadpath="./uploadfiles/";
	
    function __construct()
    {
        $this->className = "SetumeiBusiness";

        parent::__construct();
		
    }

	function insert($tablename,$data)
	{		
		$rs = parent::Insert($tablename, $data);
		
		return $rs;
	}
	function getcompanyname($type)
	{
		$sql = "SELECT id,name 
				FROM m_company 
				WHERE type = '$type'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function getusername($id)
	{
		$sql = "SELECT 
				username1,
				username2 
				FROM m_user 
				WHERE id = '$id'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs[0]['username1'].$rs[0]['username2'];
	}
	//获取指示书类型的ID
	function getinstruct_type($instruct_ID)
	{
		$sql = "SELECT idm_urls 
				FROM t_instruct_form 
				WHERE ID= '$instruct_ID'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	//获取确认者，承认者，担当者的信息
	function getformhead($idt_form_head)
	{
		$sql = "SELECT u.*
				FROM t_form_head u
				WHERE u.ID = '".$idt_form_head."'
				";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	//获得setumei.php的下拉列表
    function getUrls($level=null)
    {
    	$sql = "SELECT * FROM m_urls WHERE formtype='1'";
    	if (!empty($level))
    	{
    		$sql .= " AND level='$level'";
    	}
    	$rs = $this->db->exceuteQuery($sql);
		return $rs;
    }
	//设置默认复选框状态
	function setCheckbox($smarty,$data)
	{
		for($i=0;$i< count($list1 = split(" ",$data))-1; $i++)
		{		
			for($j=0;$j < count($list2 = split(":",$list1[$i])); $j++)
			{
				$list3[$list2[0]] = "1";
			}
		}
		
		$smarty->assign('checkbox',$list3);
	}
	
	
   	function select_setumei($level)
   	{
		   	$sql = "SELECT ID
		   	    ,showtext
		        FROM 
		        m_urls 
		        WHERE 
		        formtype = 1
		        AND
		        level='$level'";
		$data = $this->db->exceuteQuery($sql);
		return $data;		
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
   
   
   function search_setumei($ID)
   {
		$sql = "SELECT			  
				*
			FROM(
			(
			SELECT
				m.manageno,
				m.issuedate,
				m.goods_cod,
				m.idm_urls,
				m.ID,
				h. status,
				h.creater,
				h.creattime
			FROM
				t_instruct_form m
			INNER JOIN t_form_head h 
			ON h.id = m.idt_form_head
			INNER JOIN t_message msg 
			ON msg.overflg = '0'
			AND msg.formtype = '1'
			AND msg.receiverid = '$ID'
			AND msg.idt_form = m.id
			
		)
		UNION
			(
				SELECT
					m.manageno,
					m.issuedate,
					m.goods_cod,
					m.idm_urls,
					m.ID,
					h. STATUS,
					h.creater,
					h.creattime
				FROM
					t_instruct_form m
				INNER JOIN t_form_head h ON h.id = m.idt_form_head
				WHERE
					h.creater = '$ID'
				AND(
				    h.status='0'
				    OR h. STATUS = '1'                
					OR h. STATUS = '2'
				)
			)
	)rstab
		ORDER BY
			status ASC,
			creattime DESC";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
   }
   
	function search($id)
	{
		$sql = "SELECT 
				u.*,
				i.content
				FROM t_instruct_form u
				INNER JOIN t_instruct_form_content i ON u.id=i.idt_instruct_form 
				WHERE u.delflag = 0
				";
		if (!empty($id))
		{
			$sql = $sql." AND u.ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
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
	
	function search_file($ID){
		$sql = "SELECT 
				f.ID,
				f.oldfilename,
				f.newfilename
				FROM t_files f
				INNER JOIN t_instruct_form t
				ON t.idt_files = f.ID
				WHERE t.ID = '$ID'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	function search_instruct($id)
	{
		$sql = "SELECT
				c.ID
				,c.manageno	
				,c.instruction
				,c.attachmentflg			
				,c.idt_files_ins				
				FROM	t_instruct_form  c					
				WHERE 	c.id='".$id."'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	function updata_file($id,$oldname,$newname)
	{
		$data = array(
				"id" =>$id,
				"edittime" =>date('Y-m-d H:i:s',time()),
				"editor"=>$_SESSION['userinfo']['ID'],
				"oldfilename" =>$oldname,
				"newfilename"=>$newname,
				"delflag"=>0
		);
		parent::Update('t_files', $data,"u.id='$data[id]'");
	}
	
	function updata_all($tablename,$data)
	{
		parent::Update($tablename, $data,"u.id='$data[id]'");
	}
	
	function Update($data)
	{
		parent::Update('t_instruct_form', $data,"u.id='$data[id]'");
	}
	function update_s($data,$where)
	{
		
		parent::Update('t_form_head',$data,$where);
	}
	
	function getManaNo_byId($comid)
	{
		$this->sql = "SELECT appreviation FROM m_company WHERE ID='$comid'";
		$rs = $this->db->exceuteQuery($this->sql);
		if (count($rs) > 0)
		{
			return parent::getManaNo($rs[0][0]);
		}
		else
		{
			return "";
		}
	}
	
	function DeleteLogical($id)
	{
		$delsql = "UPDATE
				   t_instruct_form
				   SET delflag = 1
				   WHERE
				   ID = '".$id."'";
		$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	function delete($id)
	{
		$delsql = "DELETE
				   FROM
				   t_instruct_form
				   WHERE 
				   ID = '".$id."'";
		$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	
    function getidm_company($idt_form_head)
    {
    	$sql = "SELECT idm_company
    			FROM t_instruct_form
    			WHERE idt_form_head = '$idt_form_head'  	
    			";
    	$rs = $this->db->exceuteQuery($sql);
		return $rs[0][0];
    }
	function getidm_company_ETA()
    {
    	$sql = "SELECT ID
    			FROM m_company
    			WHERE type = '1'
    			AND delflag = '0'
    			";
    	$rs = $this->db->exceuteQuery($sql);
		return $rs[0][0];
    }
	
	/**
	 * 检索会社对应的用户
	 * Enter description here ...
	 * @param unknown_type $idcompany：会社id
	 */
    function getUser($idcompany)
    {
    	$sql = "SELECT u.ID
		        FROM m_user u 
		        INNER JOIN m_company com 
		        ON u.idm_company=com.id 
		        WHERE u.userright='2'   
		        AND  com. Type = '0'  
		        AND u.idm_company='$idcompany'";
			
		$rs = $this->db->exceuteQuery($sql);
    }
    /** 
     */
    //取指定会社，且指定权限为"担当者"的用户或代理人ID,同时取得其用户权限
    //担当者(受领者)
    function getSenderId($idt_form_head)
    {
    	//从session获取公司ID
    	$companyType = $_SESSION['userinfo']['comtype'];
    	
    	
    	//0:协力会社  1：ETA会社
    	if($companyType == "0")//当如登陆者是协力会社，应该查找ETA会社的担当者
    	{
    		$companyid = $this->getidm_company_ETA();
    	}
    	else if($companyType == "1")//登陆者是ETA会社，应该查找协力会社的担当者
    	{
    		$companyid = $this->getidm_company($idt_form_head);
    	}
    	$sql = "SELECT
    			u.ID
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company='".$companyid."' 
    			AND u.userright = '0'   AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyid."' 
    			AND u.userright = '0'   AND u.proxyflg= '1'
    	";
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs;
    }
    
    
    //取指定会社，且指定权限为确认者和承认者的用户或代理人ID
    //确认者，承认者
    function getAllReceiverId()
    {
    	
    	$companyType = $_SESSION['userinfo']['idm_company'];
    	$sql = "SELECT 
    			u.ID
    			,u.userright
    			FROM
    			m_user u
    			WHERE u.idm_company='".$companyType."' 
    			AND (u.userright = '1'  OR u.userright='2') AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyType."' 
    			AND (u.userright = '1'  OR u.userright='2')   AND u.proxyflg= '1'		
    	";
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
    
    
    //取指定会社，且指定权限为承认者的用户或代理人ID
    //承认者
    function getReceiverId()
    {
    	
    	$companyType = $_SESSION['userinfo']['idm_company'];
    	$sql = "SELECT 
    			u.ID
    			,u.userright
    			FROM
    			m_user u
    			WHERE u.idm_company='".$companyType."'
    			AND u.userright = '2'   AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyType."'
    			AND u.userright = '2'   AND u.proxyflg= '1'		
    	";
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
    
    /**
     * 当处理结果result不正常（不为0）时
     * 取指定书类子表id的创建者、第2步受领者、第3步受领者
     * Enter description here ...
     * @param  $idt_form_head：子书中的idt_form_head字段
     */
    function getForm($idt_form_head)
    {
    	$sql = "SELECT
    			h.creater
    			,h.bacceptperson
    			,h.aacceptperson
    			,c.ID
    			,c.manageno
    			FROM 
    			t_instruct_form c
    			INNER JOIN t_form_head h
    			ON c.idt_form_head = h.id
    			AND (c.idt_form_head='".$idt_form_head."' OR c.ID='".$idt_form_head."')
    			
    	";
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
//---------------------------------------------发送消息--------------------------------------------------

    
	function send_Message($idt_form_head,$sendStatus,$result)
    {	
		//发送者为当前用户
		$senderid = $_SESSION['userinfo']['ID'];
		$creater = $_SESSION['userinfo']['ID'];
		$editor = $creater;
		$creattime = date("Y-m-d H:i:s");
		$edittime = $creattime;
				
		$info = $this->getForm($idt_form_head);
		$manageno = $info[0]['manageno'];
		$formtype = "1";
		$idt_form = $info[0]['ID'];
		$delflag = 0;
		
		$receiverid = null;
		$overflg = 0;
		$sendstatus = $sendStatus;
		
		
		$v = null;
		//当处理结果为0时
		if($result == "0")
		{

				
				//根据用户的权限确定接收的权限
		    	if($_SESSION['userinfo']['userright'] == "0")
		    	{
		    		$data = $this->getAllReceiverId();
		    	}
		    	else if($_SESSION['userinfo']['userright'] == "1")
		    	{
		    		$data = $this->getReceiverId();
		    	}
		    	else if($_SESSION['userinfo']['userright'] == "2")
		    	{
		    		$data = $this->getSenderId($idt_form_head);
		    	}
		    	$receiverid = $data;
		    	//根据用户权限确认消息表的type
				if($_SESSION['userinfo']['userright'] == "0")
				{
					$type = "1";
				}
				else { 
					$type = "0"; 
				}
				
				if(empty($receiverid))
				{
					echo "查找不到接收信息用户";
					return;
				}
				
				
				//循环拼串values((),(),...)
				foreach($receiverid as $key => $value)
				{
				 	//根据接收者权限确定笑下表中的status
			    	if($receiverid[$key]['userright'] == "1")
					{
						$status = "1";
					}
					else if($receiverid[$key]['userright'] == "2")
					{
						$status = "2";
					}
					else if($receiverid[$key]['userright'] == "0")
					{
						$status = "0";
					}
					
					$v = $v."('".$type.
							"','".$creater.
							"','".$editor.
							"','".$creattime.
							"','".$edittime.
							"','".$manageno.
							"','".$formtype.
							"','".$idt_form.
							"','".$status.
							"','".$result.
							"','".$delflag.
							"','".$senderid.
							"','".$receiverid[$key]['ID'].
							"','".$overflg.
							"','".$sendstatus.
					"'),";
					
				}
		
		}
		else	//当处理结果不为0时
		{
				$type = "1";
				//查询担当者，第2步，第3步受领
				//根据$sendStatus的值确定接收的ID,和status
				if($sendStatus == "3" || $sendStatus == "4" || $sendStatus == "5")
				{
					$receiverid = $info[0]['creater'];
					$status = "3";
				}
				else if($sendStatus == "6" || $sendStatus =="7" || $sendStatus == "8")
				{
					$receiverid = $info[0]['bacceptperson'];
					$status = "0";
				}
					
				//VALUES拼串
				$v = $v."('".$type.
					"','".$creater.
					"','".$editor.
					"','".$creattime.
					"','".$edittime.
					"','".$manageno.
					"','".$formtype.
					"','".$idt_form.
					"','".$status.
					"','".$result.
					"','".$delflag.
					"','".$senderid.
					"','".$receiverid.
					"','".$overflg.
					"','".$sendstatus.
					"'),";
				

					
				
		}
		
		
			
		//去掉最后一个逗号
		$v = substr($v,0,strlen($v)-1);
		
		$sql = "INSERT INTO
				t_message
				(type
				,creater
				,editor
				,creattime
				,edittime
				,manageno
				,formtype
				,idt_form
				,status
				,result
				,delflag
				,senderid
				,receiverid
				,overflg
				,sendstatus
				)
				VALUES
				";
		$sql = $sql.$v;
		
		$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
    
    
    
//----------------------------------------------------反写处理-------------------------------------------------------------	
	/**
	 * 反写处理
	 * @param $no :反写的番号
     * @param $id：当前处理的t_instruct_form的ID
     * @param $idt_form_head：t_form_head的id
	 */
	function overflow($no,$id,$id_form_head)
    {
    	$sql = null;
    	//0 連絡書;1 指示書; 2 依頼書	
    	if($no == "1")
    	{
    		$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE  msg.receiverid='".$_SESSION['userinfo']['ID']."'								
			AND (msg.sendstatus = '3'  OR msg.sendstatus = '4'  ORE msg.sendstatus = '5' ) 								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='1'					
			AND msg.overflg='0'	  		
    		";
    		
    	}
	    else if($no == "4")
	    {
	    	//----------------------改变status-----------------
	    	$data = array(
	    					"affirmanttime"=> date('Y-m-d H:i:s',time()),
			                "affirmantperson"=>$_SESSION['userinfo']['ID'],
							"affirmantresult"=>0,
							"status"=>3
	    					);
    		parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
    		//---------------------------------------
    		$idcom = $_SESSION['userinfo']['idm_company'];
    		$right = $_SESSION['userinfo']['userright'];
	    	$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE  msg.receiverid  IN (
				SELECT	ID			
				FROM 	m_user  u			
				WHERE	idm_company = '$idcom' 			
				AND		userright ='$right'	
				UNION  ALL			
				SELECT	u.proxyid   ID			
				FROM 	m_user  u			
				WHERE	u.idm_company = '$idcom' 			
				AND	    u.userright ='$right'	
							
			)								
			AND msg.sendstatus = '2'   AND msg.overflg='0'  								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='1' 	
    		";
	    }
	    else if($no == "5")
	    {
	    	$data = array(
	    					"approvetime"=> date('Y-m-d H:i:s',time()),
			                "approveperson"=>$_SESSION['userinfo']['ID'],
							"approveresult"=>0,
							"status"=>4);
    		parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
	    	$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE  msg.receiverid  IN (
				SELECT	ID			
				FROM 	m_user  u			
				WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
				AND		userright ='".$_SESSION['userinfo']['userright']."'	
				UNION  ALL			
				SELECT	u.proxyid   ID			
				FROM 	m_user  u			
				WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
				AND	    u.userright ='".$_SESSION['userinfo']['userright']."'	
							
			)								
			AND (msg.sendstatus = '2'   OR   msg.sendstatus = '3' )  
			AND msg.overflg='0' 							
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='1' 	
    		";
	    }
	    else if($no == "6")
	    {
	    	$data = array(
							"baccepttime"=> date('Y-m-d H:i:s',time()),
			                "bacceptperson"=>$_SESSION['userinfo']['ID'],
							"bacceptresult"=>0,
							"status"=>5
						);
    		parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
	    	$sql = "
		    	UPDATE  t_message  msg				
				SET	msg.overflg='1'				
				WHERE msg.receiverid  IN   (
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		userright ='".$_SESSION['userinfo']['userright']."'	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	    u.userright ='".$_SESSION['userinfo']['userright']."'	
								
				)			
				AND msg.sendstatus = '4'    
			    AND msg.overflg='0' 				
				AND msg.idt_form='$id'  				
				AND msg.formtype='1'				
					    	
	    	";
	    }
	    else if($no == "7")
	    {
	    	$data = array(
							"bconfirmtime"=> date('Y-m-d H:i:s',time()),
			                "bconfirmperson"=>$_SESSION['userinfo']['ID'],
							"bconfirmresult"=>0,
							"status"=>6
						);
    		parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
	    	
	    	$sql = "
		    	UPDATE  t_message  msg				
				SET msg.overflg='1'				
				WHERE	msg.receiverid  IN   (
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		userright ='".$_SESSION['userinfo']['userright']."'	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	    u.userright ='".$_SESSION['userinfo']['userright']."'	
								
				)			
				AND msg.sendstatus = '5'   
				AND msg.overflg='0' 				
				AND msg.idt_form='$id'  				
				AND msg.formtype='1'				
					    	
	    	";
	    }
	    else if($no == "8")
	    {
	    	$data = array(
							"badmittime"=> date('Y-m-d H:i:s',time()),
			                "badmitperson"=>$_SESSION['userinfo']['ID'],
							"badmitresult"=>0,
							"status"=>7
						);
    		parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
	    	
	    	$sql = "
		    	UPDATE  t_message  msg								
				SET msg.overflg='1'								
				WHERE  msg.receiverid  IN 	(
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		userright ='".$_SESSION['userinfo']['userright']."'	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	    u.userright ='".$_SESSION['userinfo']['userright']."'	
								
				)	
				AND (msg.sendstatus = '5'   OR   msg.sendstatus = '6' )
				AND msg.overflg='0' 								
				AND msg.idt_form='$id'  								
				AND msg.formtype='1'	
	    	
	    	";
	    }
	    else if($no == "12")
	    {
	    	$data = array(
							"endtime"=> date('Y-m-d H:i:s',time()),
			                "endperson"=>$_SESSION['userinfo']['ID'],
							"endresult"=>0,
							"status"=>11
						);
	    	parent::Update("t_form_head", $data,"u.ID = '$id_form_head'");
	    	$sql = "
	    		UPDATE  t_message  msg				
				SET msg.overflg='1'				
				WHERE  msg.receiverid  IN  	(
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		userright ='".$_SESSION['userinfo']['userright']."'	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	    u.userright ='".$_SESSION['userinfo']['userright']."'	
								
				)			
				AND msg.sendstatus = '10'     
				AND msg.overflg='0' 				
				AND msg.idt_form='$id'  				
				AND msg.formtype='1'			
					    	
	    	";
	    }
	    
	    
	   	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
}