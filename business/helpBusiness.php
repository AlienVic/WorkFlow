<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class helpBusiness extends BaseBusiness
{
  function __construct()
    {
        $this->className = "helpBusiness";

        parent::__construct();
		
    }

	 function Inserthelp($data)
	{
		
		$rs = parent::Insert('m_help', $data);
		
		return $rs;
	}
  function Insertfile($data)
	{
		
		$insertsql = "INSERT INTO "."t_files"."  ";
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
	
function Search($id=null)
	{
		$sql = "SELECT
				h.id, 
				h.title,
				h.comment,
				t.oldfilename,
				h.idt_files
				FROM m_help h
				inner join t_files t
				on h.idt_files=t.id
				WHERE t.delflag = 0 
				";
		if (!empty($id))
		{
			$sql = $sql." AND h.ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
	function searchkey($key)
	{
		


        $sql = "SELECT 
        h.id,
        h.idt_files,
        h.title,
        h.comment,
        t.oldfilename
         FROM m_help h 
         inner join t_files t
		on h.idt_files=t.id
		WHERE concat(h.title,h.comment) like '%$key%' 
        ";
        $db = $this->db;
        $result = $db->exceuteQuery($sql);
        

        return $result;
	}
function Updatefile($data)
	{
		parent::Update('t_files', $data,"u.id='$data[ID]'");
	}
function Update($data)
{
		parent::Update('m_help', $data,"u.id='$data[id]'");
	}
	
	
	
}
