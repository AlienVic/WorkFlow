<?php

require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class kaisyaBusiness extends BaseBusiness
{

    /**
     * construct メソッドが定義する。<br>
     */
    function __construct()
    {
        $this->className = "kaisyaBusiness";

        parent::__construct();
		
    }

    function Insert($data)
	{
		
		$insertsql = "INSERT INTO "."m_company"."  ";
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
	function InsertSe($data)
	{
		$insertsql = "INSERT INTO "."sequence"."  ";
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
		
		$keysql .=" )";
		$valuesql .= " )";

		$insertsql = $insertsql.$keysql." "." VALUES ";
		$insertsql = $insertsql.$valuesql;

		$rs = $this->db->exceuteUpdate($insertsql);
		return $rs;
	}
	
	function Search($id=null)
	{
		$sql = "SELECT 
				ID
				,name
				,code
				,type
				,appreviation
				,comment
				FROM m_company 
				WHERE delflag = 0";
		if (!empty($id))
		{
			$sql = $sql." AND ID='".$id."'";
		}
		$rs = $this->db->exceuteQuery($sql);
		return $rs;
	}
 function  deleteCompanyData($id)
   {
   	//	$this->logger->info("開始");
        $sql = " UPDATE   `m_company` SET delflag='1'  WHERE id='$id'

        ";

        $db = $this->db;
        $result = $db->exceuteUpdate($sql);
     //   $this->logger->info("終了");
        if ($result)
        {
        	return true;
        }
        return false;
   }
function Update($data)
	{
		parent::Update('m_company', $data,"u.id='$data[ID]'");
	}
	
	


}

?>