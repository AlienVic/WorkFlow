<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');
require_once('PageSplitBusiness.php');

class productBusiness extends PageSplitBusiness
{
	//导入语句的头部
	private static $insertHead = "INSERT INTO m_product (issue_d,cmpl_inst_d,item_cod,m_no,goods_cod,m_o_q,AWCode,AWCompanyName) VALUES ";
	private $totalRows;
	private $isTotalRows = false;
	
    function __construct()
    {
        $this->className = "productBusiness";

        parent::__construct();
		
    }
    
    function  resetAutoIncre()
    {
    	$resql = "alter table `m_product` set auto_increment=1";
    	$rs = $this->db->exceuteUpdate($resql);
		return $rs;
    }
    
    
   


	function Search($currpage,$numbersperpage)
	{
		$sql = "SELECT 
				ID
				,issue_d
				,cmpl_inst_d
				,item_cod
				,m_no
				,goods_cod
				,m_o_q
				,AWCode
				,AWCompanyName
				FROM m_product
				";
		if (!$this->isTotalRows)
		{
			$sqlcount = "SELECT count(1) FROM m_product";
			$this->setTotalRow($this->buildTotalRow($sqlcount));
			$this->isTotalRows = true;
			
		}
		$result = $this->QueryForPageSplit($sql, $currpage, $numbersperpage);
		return $result;
	}
	//$data作为一个多维数组
	function Insert($data)
	{
		$insertsql = self::$insertHead;
		
		foreach($data as $value)
		{
			$insertsql .= $this->createValueStr($value);
			$insertsql .= ",";
		}
		
		$insertsql = substr($insertsql,0,strlen($insertsql)-1);
		

		$rs = $this->db->exceuteUpdate($insertsql);
		return $rs;
	}
	//单独的一维数据
	function createValueStr($data)
	{
		$valuestr = "(";
		
		foreach($data as $value)
		{
			$valuestr .= "'".$value."'";
			$valuestr .= ",";
		}
		$valuestr = substr($valuestr,0,strlen($valuestr)-1);
		$valuestr .= ")";
		
		
		return $valuestr;
	}
	
	
	
	
	
	function Delete()
	{
		$rs = parent::Delete(m_product);
		//$delsql = "DELETE *
		//		   FROM
		//		   m_product
		//		";
		//$rs = $this->db->exceuteQuery($delsql);
		return $rs;
	}
	
	
}

?>