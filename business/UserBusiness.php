<?php

require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class UserBusiness extends BaseBusiness
{

    /**
     * construct メソッドが定義する。<br>
     */
    function __construct()
    {
        $this->className = "UserBusiness";

        parent::__construct();
		
    }

	function Insert($data)
	{
		
		$rs = parent::Insert('m_user', $data);
		/*$insertsql = "INSERT INTO m_company ";
		$keysql ="(";
		$valuesql = "(";


		//循环生成$keysql 和 $valuesql

		foreach($data as $key => $value)
		{
			$keysql = $keysql.$key.",";
			$valuesql = $valuesql."'".$value."',";
		}
		//去掉最后一个逗号
		$keysql = substr($keysql,0,strlen($keysql)-1);
		$valuesql = substr($valuesql,0,strlen($valuesql)-1);

		$keysql .=")";
		$valuesql .= ")";

		$insertsql = $insertsql.$keysql." "." VALUES ";
		$insertsql = $insertsql.$valuesql;

		$rs = $this->db->exceuteUpdate($insertsql);*/
		return $rs;
	}
	function Search($id=null)
	{
		$sql = "SELECT 
				u.ID
				,u.username1
				,u.username2
				,u.pronunciation1
				,u.pronunciation2
				,u.email1
				,u.email2
				,u.password
				,u.idm_company
				,u.idm_depart
				,u.usertype
				,u.userright
				,u.proxyid
				,u.proxyflg
				,com.name companyname
				,dep.departname departname
				FROM m_user u
				INNER JOIN m_company com ON u.idm_company=com.id
				INNER JOIN m_depart dep ON u.idm_depart = dep.id
				WHERE u.delflag='0'
				
				
				
				
				
				";
		if (!empty($id))
		{
			$sql = $sql." AND  u.ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function Update($data)
	{
		parent::Update('m_user', $data,"u.id='$data[id]'");
	}
	function DeleteLogical($id)
	{
		parent::DeleteLogical('m_user',"d.ID='".$id."'");
	}
	/**
	 * 
	 * 指定代理
	 * @param unknown_type $id：被代理者id
	 * @param unknown_type $agentid:代理id
	 */
	function AssginAgent($id,$agentid)
	{
		$this->sql = "
		UPDATE m_user SET 
		proxyid = '$agentid'
		,proxyflg = '1'
		WHERE ID='$id'
		";
		$rs = $this->db->exceuteUpdate($this->sql);
		return $rs;
	}
	/**
	 * 
	 * 取消代理
	 * @param unknown_type $id：被代理者id
	 * @param unknown_type $agentid:代理id
	 */
	function CancelAgent($id)
	{
		$this->sql = "
		UPDATE m_user SET 
		proxyid = ''
		,proxyflg = '0'
		WHERE ID='$id'
		";
		$rs = $this->db->exceuteUpdate($this->sql);
		return $rs;
	}
	
	function getCompanyList()
	{
		$this->sql = "
		SELECT * FROM m_company WHERE delflag='0'
		";
		$rs = $this->db->exceuteQuery($this->sql);
		return $rs;
	}
	function getDepartListBycomId($comid)
	{
		$this->sql = "
		SELECT * FROM m_depart WHERE delflag='0'  AND idm_comapany='$comid'
		";
		$rs = $this->db->exceuteQuery($this->sql);
		return $rs;
	}
	
	
	
	//获得与登录者相同的可被代理的user列表
	function getProUsers()
	{
		$this->sql = "SELECT u.ID,CONCAT(u.username1,u.username2) name FROM m_user u WHERE u.idm_company='".$_SESSION['userinfo']['idm_company']."'";
		$rs = $this->db->exceuteQuery($this->sql);
		return $rs;
	}
	

	


}

?>