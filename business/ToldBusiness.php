<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class ToldBusiness extends BaseBusiness
{
	var $lastid;
	var $uploadpath="./uploadfiles/";
	
    function __construct()
    {
        $this->className = "ToldBusiness";

        parent::__construct();
		
    }
	

	function Noticeinsert($data)
	{
		
		$rs = parent::Insert('t_notice', $data);
		
		return $rs;
	}
	
	function search($id=null)
	{
		$sql = "SELECT 
				n.ID
				,n.category
				,n.creattime
				,n.creater
				,n.title
				,n.content
				,n.editor
				,n.edittime
				,n.idt_files
				,n.referrange
				,n.period_from
				,n.period_to
				FROM t_notice n
				WHERE delflag = 0
				";
		if (!empty($id))
		{
			$sql = $sql." AND ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function search_user($id=null)
	{
		$sql = "SELECT 
				u.ID
				,u.username1
				,u.username2
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
	function getcompanyname($id)
	{
		$sql = "SELECT name 
				FROM m_company
				WHERE u.delflag='0'
				";
		if (!empty($id))
		{
			$sql = $sql." AND  u.ID='".$id."'";
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
	function Update($data)
	{
		parent::Update('t_notice', $data,"u.id='$data[id]'");
	}
	function DeleteLogical($id)
	{
		$delsql = "UPDATE
				   t_notice
				   SET delflag = 1
				   WHERE
				   ID = '".$id."'";
		$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	function search_file($ID){
		$sql = "SELECT 
				f.ID,
				f.oldfilename,
				f.newfilename
				FROM t_files f
				INNER JOIN t_notice t
				ON t.idt_files = f.ID
				WHERE t.ID = '$ID'";
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function delete($id)
	{
		$delsql = "DELETE
				   FROM
				   t_notice
				   WHERE 
				   ID = '".$id."'";
		$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	
}