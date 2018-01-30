<?php

require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');
require_once('LoginBusiness.php');


class DependBusiness extends BaseBusiness
{	
	


    /**
     * construct メソッドが定義する。<br>
     */
    function __construct()
    {
        $this->className = "DependBusiness";

        parent::__construct();
		
    }

    
    
    //获得irai.php的下拉列表
    function getUrls($level=null)
    {
    	$sql = "SELECT * FROM m_urls WHERE formtype=2";
    	if (!empty($level))
    	{
    		$sql .= " AND level='$level'";
    	}
    	$rs = $this->db->exceuteQuery($sql);
		return $rs;
    }
    
    
    //数据库插入
	function insert($tablename,$data)
	{
		
		$rs = parent::Insert($tablename, $data);
		return $rs;
	}
	
	
	//数据库查找
	function search($id=null)
	{
		$sql = "SELECT 
				m.ID
				,m.manageno
				,m.issuedate
				,m.companyname
				,m.idm_company
				,m.goods_cod
				,m.m_no
				,m.m_o_q
				,m.solder
				,m.serialno
				,m.dependnum
				,m.project
				,m.baditems
				,m.baditemstext
				,m.baddetails
				,m.swaprecord
				,m.idt_files
				,m.idt_form_head
				,m.delflag
				,t.status	
				FROM t_depend_form m
				INNER JOIN t_form_head t ON m.idt_form_head=t.ID
				WHERE m.delflag=0";
		if (!empty($id))
		{
			$sql = $sql." AND (t.ID='".$id."' OR m.ID ='".$id."')";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	
	//更新t_depend_form
	function update($formname,$data,$idt_form_head)
	{

		$rs = parent::Update($formname,$data,"u.ID='$idt_form_head'");
		
		return $rs;
	}
	
	
	
	//查询产品表
	function searchProduct($id)
	{
		$sql = "SELECT
				m.goods_cod
				,m.m_o_q
				,m.issue_d
				FROM m_product m ";
		$sql .= "WHERE m_no=".$id;
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	
	//跟距idt_form_head查表
	function searchIdt_form_head($idt_form_head)
	{
		$sql = "SELECT 
				m.ID
				,m.manageno
				,m.issuedate
				,m.companyname
				,m.idm_company
				,m.goods_cod
				,m.m_no
				,m.m_o_q
				,m.solder
				,m.serialno
				,m.dependnum
				,m.project
				,m.baditems
				,m.baditemstext
				,m.baddetails
				,m.swaprecord
				,m.idt_files
				,m.idt_form_head
				,m.delflag	
				FROM t_depend_form m
				WHERE delflag=0";

		
		$sql = $sql." AND idt_form_head='".$idt_form_head."'";
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
		
		$smarty->assign('chb',$list3);
	}
	//返回$tail
    function getManageNoTail($data)
    {
    	$sql = "SELECT nextval_str('$data',4)";
    	$db = $this->db;
        $result = $db->exceuteQuery($sql);
    	return $result[0][0];
    }
    
    //返回$head
    function getManageNoHead()
    {
    	return "解";
    }
    
    
    
    
    /**
     * -------------------------------------------------------消息处理-------------------------------------------
     * 
     */
    
    
    
    
    

    
    
    //查找与当前登陆者相关的依赖书
    function searchFormInfo()
    {
    	$sql = "
    	SELECT * FROM  (
    	
	    	(SELECT 
	    		m.manageno
	    		,  m.issuedate
	    		,  m.goods_cod
	    		,  m.dependnum
	    		,  m.m_no				
				,  m.ID
				,  m.idt_form_head 				
				,  h.status				
				,  h.creater				
				,  h.creattime
				FROM t_depend_form m				
				INNER JOIN 	t_form_head  h																										
				ON  h.id = m.idt_form_head    																										
				INNER JOIN  t_message  msg																										
				ON 	msg.overflg = '0' 
				AND  msg.formtype='2' 
				AND  msg.receiverid ='". $_SESSION['userinfo']['ID']. 
				"' AND msg.idt_form = m.id
			)
		UNION
			(SELECT
				m.manageno						
				,  m.issuedate						
				,  m.goods_cod				
				,  m.dependnum						
				,  m.m_no
				,  m.ID
				,  m.idt_form_head 												
				,  h.status						
				,  h.creater						
				,  h.creattime						
				FROM  t_depend_form m																							
				INNER JOIN 	t_form_head  h																							
				ON 	h.id = m.idt_form_head    																							
				WHERE  h.creater  = '".$_SESSION['userinfo']['ID'].
				"' AND  (h.status = '0'  OR h.status = '1' OR  h.status = '2')
				 																							
			)																							
			
		)rstab
		ORDER  BY 	status  ASC,  creattime  DESC							
					    		
    	";
    	
    	$rs = $this->db->exceuteQuery($sql);
		return $rs;
    	
    }
    
    //设置按钮状态
    function initPage($smarty,$DependStatus)
    {
    	$data = $this->searchFormInfo();
    	
    	$able = array();//按钮的可见，可用，0为不可见，1为可见，2为不可用
    	foreach ($data as $key => $v)
		{
			
					
			if($v[status] == "0" || $v[status] == "1")//status= 0,1，  项目6-16  不可见
			{
				for($i = 6; $i <= 16; $i++)
				{
					$able[$key][$i] = "0";
				}
			}
			else if($v[status] == "2")//status= 2，  项目6-8  可见，9-16不可见
			{
				$able[$key]['6'] = "1";
				$able[$key]['7'] = "1";
				$able[$key]['8'] = "1";
				for($i=9; $i <= 16; $i++)
				{
					$able[$key][$i] = "0";
				}
			}
			else if($v[status] == "3")//status= 3， 项目6为不可用， 项目7-8  可见，9-16不可见
			{
				$able[$key]['6'] = "2";
				$able[$key]['7'] = "1";
				$able[$key]['8'] = "1";
				for($i=9; $i <= 16; $i++)
				{
					$able[$key][$i] = "0";
				}
			}
			else if($v[status] == "4")//status= 4， 项目6、7、8为不可用， 项目9 可见，10-16不可见
			{
				$able[$key]['6'] = "2";
				$able[$key]['7'] = "2";
				$able[$key]['8'] = "2";
				$able[$key]['9'] = "1";
				for($i=10; $i <= 16; $i++)
				{
					$able[$key][$i] = "0";
				}
			}
			else if($v[status] == "5")//status= 5， 项目6、7、8、9为不可用， 项目10-12可见，13-16不可见
			{
				$able[$key]['6'] = "2";
				$able[$key]['7'] = "2";
				$able[$key]['8'] = "2";
				$able[$key]['9'] = "2";
				$able[$key]['10'] = "1";
				$able[$key]['11'] = "1";
				$able[$key]['12'] = "1";
				$able[$key]['13'] = "0";
				$able[$key]['14'] = "0";
				$able[$key]['15'] = "0";
				$able[$key]['16'] = "0";
			}
			else if($v[status] == "6")//status= 6， 项目6、7、8、9、10为不可用， 项目11-12可见，13-16不可见
			{
				for($i=6; $i <= 10; $i++)
				{
					$able[$key][$i] = "2";
				}
				$able[$key]['11'] = "1";
				$able[$key]['12'] = "1";
				$able[$key]['13'] = "0";
				$able[$key]['14'] = "0";
				$able[$key]['15'] = "0";
				$able[$key]['16'] = "0";
			}
			else if($v[status] == "7")//status= 7， 项目6-12为不可用， 项目13可见，14-16不可见
			{
				for($i=6; $i <= 12; $i++)
				{
					$able[$key][$i] = "2";
				}
				$able[$key]['13'] = "1";
				$able[$key]['14'] = "0";
				$able[$key]['15'] = "0";
				$able[$key]['16'] = "0";
			}
			else if($v[status] == "8")//status= 8， 项目6-13为不可用， 项目14-15可见，16不可见
			{
				for($i=6; $i <= 13; $i++)
				{
					$able[$key][$i] = "2";
				}
				$able[$key]['14'] = "1";
				$able[$key]['15'] = "1";
				$able[$key]['16'] = "0";
			}
			else if($v[status] == "9")//status= 9， 项目6-14为不可用， 项目15可见，16不可见
			{
				for($i=6; $i <= 14; $i++)
				{
					$able[$key][$i] = "2";
				}
				$able[$key]['15'] = "1";
				$able[$key]['16'] = "0";
			}
			else if($v[status] == "10")//status= 10， 项目6-15为不可用， 项目16可见
			{
				for($i=6; $i <= 15; $i++)
				{
					$able[$key][$i] = "2";
				}
				$able[$key]['16'] = "1";
			}
			else if($v[status] == "11")//status= 11， 项目6-16为不可用
			{
				for($i=6; $i <= 16; $i++)
				{
					$able[$key][$i] = "2";
				}
			}
			
			$data[$key][sendstatus] = $DependStatus[$v[status]];
		}
		
		
		
		
		$smarty->assign('data', $data);
		$smarty->assign('able', $able);
	   	$smarty->display('depend/irai_list.tpl');
	   	
	   	
	   	
	 }
    
    
    /**
     * 
     * //取①与登录者同一会社; ②指定权限  用户
     * @param $companytype:当前用户的会社
     * @param $userright：当前用户的权限
     */
    function getUser($companytype,$userright)
    {
    	$sql = "SELECT
    			ID
    			FROM m_user u
    			WHERE idm_company='".$companytype."'".
    			"AND u.userright='".$userright."'".
    			"UNION ALL 
    			SELECT u.proxyid
    			FROM m_user u
    			WHERE u.idm_company='".$companytype."'".
    			"AND u.userright='".$userright."'
    			";
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs;
    }
    
   	
    
    
    
    
    /**
     * 承认者处理后要找下一个会社的担当者
     */
    //取指定会社，且指定权限为"担当者"的用户或代理人ID,同时取得其用户权限
    //担当者(受领者)
    function getSenderId($idt_form_head)
    {
    	//从session获取公司ID
    	$companyType = $_SESSION['userinfo']['comtype'];
    	//0:协力会社  1：ETA会社
    	if($companyType != "1")//当如登陆者是协力会社，应该查找ETA会社的担当者
    	{
    		$companyType = "1";
    	}
    	else if($companyType == "1")//登陆者是ETA会社，应该查找协力会社的担当者
    	{
    		$value = $this->searchIdt_form_head($idt_form_head);
    		$companyType = $value[0]['idm_company'];
    	}
    	$sql = "SELECT
    			u.ID
    			,u.userright
    			FROM
    			m_user u
    			WHERE u.idm_company='".$companyType."'".
    			"AND u.userright = '0'   AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyType."'".
    			"AND u.userright = '0'   AND u.proxyflg= '1'
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
    			WHERE u.idm_company='".$companyType.
    			"' AND (u.userright = '1'  OR u.userright='2') AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyType.
    			"' AND (u.userright = '1'  OR u.userright='2')   AND u.proxyflg= '1'		
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
    			WHERE u.idm_company='".$companyType."'".
    			"AND u.userright = '2'   AND u.proxyflg= '0'
    			UNION ALL
    			SELECT 
    			u.proxyid
    			,u.userright
    			FROM m_user u
    			WHERE u.idm_company ='".$companyType."'".
    			"AND u.userright = '2'   AND u.proxyflg= '1'		
    	";

    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
    
    
    /**
     * 当处理结果result不正常（不为0）时
     * //取指定书类子表id的创建者、第2步受领者、第3步受领者
     * @param unknown_type $formtype : 书名
     * @param unknown_type $idt_form_head:子书中idt_form_head字段(可传入idt_form_head 或者ID)
     * 
     */
    function getFormInfo($formname,$idt_form_head)
    {
    	$sql = "SELECT
    			h.creater
    			,h.bacceptperson
    			,h.aacceptperson
    			,c.ID
    			,c.manageno
    			FROM 
    			".$formname." c
    			INNER JOIN t_form_head h
    			ON c.idt_form_head = h.id
    			AND (c.idt_form_head='".$idt_form_head."' OR c.ID='".$idt_form_head."')
    			
    	";
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
 
    /**
     * 
     * 	
     *    //发送消息方法
     * @param $formtype	：书名
     * @param $idt_form_head：书子表中的idt_form_head字段
     * @param $sendstatus：消息表中的sendstatus字段
     * @param $result:处理结果
     */
    function sendMessage($formname,$idt_form_head,$sendStatus,$result)
    {
    	
    	

		
		//发送者为当前用户
		$senderid = $_SESSION['userinfo']['ID'];
		$creater = $_SESSION['userinfo']['ID'];
		$editor = $_SESSION['userinfo']['ID'];
		$creattime = date("Y-m-d H:i:s");
		$edittime = $creattime;
				
		$info = $this->getFormInfo($formname, $idt_form_head);
		$manageno = $info[0]['manageno'];
		$formtype = "2";
		$idt_form = $info[0]['ID'];
		$delflag = "0";
		
		$receiverid = null;
		$overflg = "0";
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
				else if($sendStatus == "9" || $sendStatus == "10")
				{
					$receiverid = $info[0]['aacceptperson'];
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
		$v = substr($v,0,strlen($setsql)-1);
		
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
    
    
    
    //反写已处理标志overflow
    /**
     * 
     * 反写函数
     * @param $no :反写的番号
     * @param $id：当前处理的t_depend_form的ID
     * @param $idt_form_head：t_form_head的id
     */
    function setOverflow($no,$id,$id_form_head)
    {
    	$sql = null;
    	//0 連絡書;1 指示書; 2 依頼書	
    	if($no == "1")
    	{
    		$data = array("status" => "0");
    		$this->update("t_form_head", $data, $id_form_head);
    		$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE  msg.receiverid='".$_SESSION['userinfo']['ID']."'								
			AND (msg.sendstatus = '3'  OR msg.sendstatus = '4'  OR msg.sendstatus = '5' ) 								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2'					
			AND msg.overflg='0'	  		
    		";
    		
    	}
	    else if($no == "4")
	    {
	    	$data = array("status" => "3");
    		$this->update("t_form_head", $data,$id_form_head);
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
			AND msg.sendstatus = '2'   AND msg.overflg='0'  								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2' 	
    		";
	    }
	    else if($no == "5")
	    {
	    	$data = array("status" => "4");
    		$this->update("t_form_head", $data,$id_form_head);
	    	$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE  msg.receiverid  IN (
				SELECT	ID			
				FROM 	m_user  u			
				WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
				AND		(userright ='1'	OR userright ='2')
				UNION  ALL			
				SELECT	u.proxyid   ID			
				FROM 	m_user  u			
				WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
				AND	    (u.userright ='1' OR u.userright ='2')	
							
			)								
			AND (msg.sendstatus = '2'   OR   msg.sendstatus = '3' )  
			AND msg.overflg='0' 							
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2' 	
    		";
	    }
	    else if($no == "6")
	    {
	    	$data = array("status" => "5");
    		$this->update("t_form_head", $data,$id_form_head);
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
				AND msg.formtype='2'				
					    	
	    	";
	    }
	    else if($no == "7")
	    {
	    	
	    	$data = array("status" => "6");
    		$this->update("t_form_head", $data,$id_form_head);
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
				AND msg.formtype='2'				
					    	
	    	";
	    }
	    else if($no == "8")
	    {
	    	
	    	$data = array("status" => "7");
    		$this->update("t_form_head", $data,$id_form_head);
	    	$sql = "
		    	UPDATE  t_message  msg								
				SET msg.overflg='1'								
				WHERE  msg.receiverid  IN 	(
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		(u.userright ='1' OR u.userright ='2')	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	    (u.userright ='1' OR u.userright ='2')	
								
				)	
				AND (msg.sendstatus = '5'   OR   msg.sendstatus = '6' )
				AND msg.overflg='0' 								
				AND msg.idt_form='$id'  								
				AND msg.formtype='2'	
	    	
	    	";
	    }
	    else if($no == "9")
	    {
	    	
	    	$data = array("status" => "8");
    		$this->update("t_form_head", $data,$id_form_head);
	    	$sql = "
		    	UPDATE  t_message  msg								
				SET msg.overflg='1'								
				WHERE  msg.receiverid  = '".$_SESSION['userinfo']['ID']."'								
				AND (msg.sendstatus = '6'   OR  msg.sendstatus = '7'   ) 
				AND msg.overflg='0' 								
				AND msg.idt_form='$id'  								
				AND msg.formtype='2'	
					    	
	    	";
	    }
	    else if($no == "10")
	    {
	    	
	    	$data = array("status" => "9");
    		$this->update("t_form_head", $data,$id_form_head);
	    	$sql = "
		    	UPDATE  t_message  msg						
				SET msg.overflg='1'						
				WHERE msg.receiverid  IN  (
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
				AND msg.sendstatus = '8'   
				AND msg.overflg='0' 						
				AND msg.idt_form='$id'  						
				AND msg.formtype='2'	
					    	
	    	";
	    }
	    else if($no == "11")
	    {
	    	
	    	$data = array("status" => "10");
    		$this->update("t_form_head", $data,$id_form_head);
	    	$sql = "
		    	UPDATE  t_message  msg							
				SET msg.overflg='1'							
				WHERE msg.receiverid  IN  (
					SELECT	ID			
					FROM 	m_user  u			
					WHERE	idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND		(u.userright ='1' OR u.userright ='2')	
					UNION  ALL			
					SELECT	u.proxyid   ID			
					FROM 	m_user  u			
					WHERE	u.idm_company = '".$_SESSION['userinfo']['idm_company']."' 			
					AND	   (u.userright ='1' OR u.userright ='2')	
								
				)	
				AND (msg.sendstatus = '8'   OR   msg.sendstatus = '9' )  
				AND msg.overflg='0' 							
				AND msg.idt_form='$id'  							
				AND msg.formtype='2'	
					    	
	    	";
	    }
	    else if($no == "12")
	    {
	    	
	    	$data = array("status" => "11");
    		$this->update("t_form_head", $data,$id_form_head);
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
				AND msg.formtype='2'			
					    	
	    	";
	    }
	    
	    
	   	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
    
    
    //result不为0的反写
    function setFailedOverflow($no,$id)
    {
	    $sql = null;
	    $v = null;
	    
	    $reciever = $this->getAllReceiverId();
		$v .= "(";
		foreach($reciever as $key => $value)
		{
			$v .= "msg.receiverid='".$reciever[$key]['ID']."'";
			$v .= " OR ";
		}
		$v = substr($v,0,strlen($setsql)-3);			
		$v .= ")";
    		
    	if($no == "1")
    	{

    		$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE ".$v."								
			AND (msg.sendstatus = '2'  OR msg.sendstatus = '3' OR msg.sendstatus = '4' ) 								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2'					
			AND msg.overflg='0'	  		
    		";
    		
    	}
    	else if($no == "2")
    	{

    		$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE ".$v."								
			AND (msg.sendstatus = '4'  OR msg.sendstatus = '5' OR msg.sendstatus = '6' ) 								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2'					
			AND msg.overflg='0'	  		
    		";
    		
    		
    	}
    	else if($no == "3")
    	{
    					
    		$sql = "
    		UPDATE 	t_message  msg								
			SET  msg.overflg='1'					
			WHERE ".$v."								
			AND (msg.sendstatus = '7'  OR msg.sendstatus = '8' OR msg.sendstatus = '9' ) 								
			AND msg.idt_form='".$id."'  								
			AND msg.formtype='2'					
			AND msg.overflg='0'";
    	}
    	
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs;
        
    }
    
    
    
    
    /**
     * ---------------------------------------------进步页面处理------------------------------------------
     */
    
    
    /**
     * 
     * 点击受领，检索依赖书
     * @param $id
     */
     
    function getAcceptInfo($id)
    {
    	$sql = "
    	SELECT	        c.*,h.aaccepttime,h.affirmanttime,h.approvetime
    					,CONCAT(u1.username1,u1.username2) acceptname
						,CONCAT(u2.username1,u2.username2) confirmname
						,CONCAT(u3.username1,u3.username2) admitname																	
		FROM	        t_depend_form  c																			
		INNER JOIN 	    t_form_head  h												
		ON	            h.id = c.idt_form_head																			
		LEFT  JOIN 		m_user  u1																	
		ON				u1.id = h.creater																			
		LEFT  JOIN 		m_user  u2																	
		ON				u2.id = h.affirmantperson																		
		LEFT  JOIN 		m_user  u3																			
		ON				u3.id = h.approveperson																	
		WHERE 			c.id='$id'   	
    	";
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    	
    }
    
    
    /**
     * 
     * 协力点击受领，检索依赖书
     * @param $id
     */
    function getAcceptInfo2($id)
    {
    	$sql = "
    	SELECT	        c.*
				    	,h.baccepttime
				    	,h.bconfirmtime
				    	,h.badmittime
    					,CONCAT(u1.username1,u1.username2) acceptname
						,CONCAT(u2.username1,u2.username2) confirmname
						,CONCAT(u3.username1,u3.username2) admitname																	
		FROM	        t_depend_form  c																			
		INNER JOIN 	    t_form_head  h												
		ON	            h.id = c.idt_form_head																			
		LEFT  JOIN 		m_user  u1																	
		ON				u1.id = h.bacceptperson																			
		LEFT  JOIN 		m_user  u2																	
		ON				u2.id = h.bconfirmperson																		
		LEFT  JOIN 		m_user  u3																			
		ON				u3.id = h.badmitperson																	
		WHERE 			c.id='$id'   	
    	";
    	
    	
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    	
    }
    
    
    /**
     * 
     * 受领新规show
     * @param $id
     */
    function getAcceptShow($id)
    {
    	$sql = "
    	SELECT	c.*					
		FROM	t_depend_form  c					
		WHERE 	c.id='$id'					
		    	
    	";
    	$db = $this->db;
        $rs = $db->exceuteQuery($sql);
        return $rs; 
    }
    
	

}




?>