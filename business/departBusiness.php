<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class departBusiness extends BaseBusiness
{
    function __construct()
    {
        $this->className = "departBusiness";

        parent::__construct();
		
    }

	 function Insert($data)
	{
		
		$insertsql = "INSERT INTO "."m_depart"."  ";
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
		
		
		$keysql .=",delflag)";
		$valuesql .= ",0)";
		$insertsql = $insertsql.$keysql." "." VALUES ";
		$insertsql = $insertsql.$valuesql;

		$rs = $this->db->exceuteUpdate($insertsql);
		return $rs;
	}
	function Searchname($id=null)
	{
		$sql = "SELECT 
				n.name
				,n.ID
				FROM m_company n
				WHERE delflag = 0
				";
		if (!empty($id))
		{
			$sql = $sql." AND n.ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function Search($id=null)
	{
		$sql = "SELECT 
				n.ID,
				n.idm_comapany,
				n.departname,
				n.parentid,
				n.editor,
				n.creattime,
				n.edittime,
				n.creater,
				n.comment,
				m.name
		
				FROM m_depart n
				inner join m_company m
				on n.idm_comapany=m.ID
				WHERE m.delflag = 0 
				 AND n.delflag=0";
		if (!empty($id))
		{
			$sql = $sql." AND n.ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function Update($data)
	{
		parent::Update('m_depart', $data,"u.id='$data[id]'");
	}
	
	function DeleteLogical($id)
	{
		$delsql = "UPDATE
				   m_depart
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
				   m_depart
				   WHERE 
				   ID = '".$id."'";
		$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	
}
