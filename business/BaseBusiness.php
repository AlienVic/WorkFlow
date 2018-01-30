<?php
require_once('LoggerManager.php');
require_once('DBUtil.php');
require_once('ValidateUtil.php');
require_once('SupportException.php');
require_once('StringUtil.php');
require_once('AppendStringUtil.php');

class BaseBusiness
{
	var $logger;
	var $className = "BaseBusiness";
	protected $db;
	protected $sql;

	/**
	 * construct メソッドが定義する。<br>
	 *
	 */
	function __construct() {

		$this->logger = LoggerManager::getLogger($this->className);

		$this->db = new DBUtil();


	}
	
	/**
	 * 
	 * 通用插入方法
	 * @param string $tablename:需要插入数据的表名
	 * @param array $data:需要插入的数据，注意格式，$data是一个数组，key=表中对应的字段名
	 * 譬如：$data['name']='lzg'
	 * $data['code']='001'
	 * $data['type']='1'
	 * 上述数据中code 、name和type都是表m_company中的字段
	 */
	
	
	function Insert($tablename,$data)
	{
		
		$insertsql = "INSERT INTO ".$tablename."  ";
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

		$rs = $this->db->exceuteUpdate($insertsql);
		return $rs;
	}

	
	/**
	 * 
	 * 通用更新方法,主表别名u
	 * @param string $tablename:需要插入数据的表名
	 * @param array $data：需要更新的数据，注意格式，$data是一个数组，key=表中对应的字段名
	 * @param string $where:更新条件
	 * 譬如：$data['name']='lzg'
	 * $data['code']='001'
	 * $data['type']='1'
	 * 上述数据中code 、name和type都是表m_company中的字段 
	 */
	function Update($tablename,$data,$where=null)
	{
		
		$updatesql = "UPDATE  ".$tablename." u  SET  ";
		$setsql ="";
		


		//循环生成$keysql 和 $valuesql

		foreach($data as $key => $value)
		{
			$setsql = $setsql."u.".$key."="."'".$value."',";
		}
		//去掉最后一个逗号
		$setsql = substr($setsql,0,strlen($setsql)-1);

		$updatesql = $updatesql.$setsql;
		if (!empty($where))
		{
			$updatesql = $updatesql." WHERE ".$where;
		}
		$rs = $this->db->exceuteUpdate($updatesql);
		return $rs;
	}

	/**
	 * 
	 * 通用查询方法，主表别名m
	 * @param string $tablename:查询主表名称
	 * @param string $fieldslist:取出的字段列表
	 * @param string $joinsql:内连接 等连接子句
	 * @param string $where:条件
	 * @param string $groupsql:分组条件
	 * @param string $ordersql：排序子句
	 */
	function Search($tablename,$fieldslist,$joinsql=null,
		$where=null,$groupsql=null,$ordersql=null)
	{
		
		$selectsql = "SELECT  ".$fieldslist."     ";
		$selectsql .= " FROM ".$tablename." m ";
		if (!empty($joinsql))
		{
			$selectsql .= $joinsql." ";
		}	
		if (!empty($where))
		{
			$selectsql .= "WHERE ".$where." ";
		}
		if (!empty($groupsql))
		{
			$selectsql .= "GROUP BY ".$groupsql." ";
		}	
		if (!empty($ordersql))
		{
			$selectsql .= "ORDER BY ".$ordersql." ";
		}	
		$rs = $this->db->exceuteQuery($selectsql);
		return $rs;
	}
	

	/**
	 * 
	 * 通用删除方法,主表使用别名d
	 * @param string $tablename:需要删除数据的表名
	 * @param string $where:条件
	 * 
	 */
	function Delete($tablename,$where=null)
	{
		
		$delsql = "DELETE d FROM ".$tablename." d ";
		
		if (!empty($where))
		{
			$delsql .= "WHERE ".$where." ";
		}
		
		$rs = $this->db->exceuteUpdate($delsql);
		return $rs;
	}
	

	/**
	 * 
	 * 通用逻辑删除方法,主表使用别名d
	 * @param string $tablename:需要删除数据的表名
	 * @param string $where:条件
	 * 
	 */
	function DeleteLogical($tablename,$where=null)
	{
		
		$delsql = "UPDATE  ".$tablename." d ";
		$delsql .= " SET d.delflag='1'";
		
		if (!empty($where))
		{
			$delsql .= "WHERE ".$where." ";
		}
		
		$rs = $this->db->exceuteUpdate($delsql);
		return $rs;
	}
	/**
	 * destruct メソッドが定義する。<br>֐�
	 *
	 */
	function __destruct()
	{
		$this->logger = null;
		$this->className = null;
	}
	
 	function getManaNo($name)
    {
     	$sql = "SELECT nextval_str('$name',4)";
     	$db = $this->db;
     	$rs = $db->exceuteQuery($sql);
     	return $rs[0][0];
    }
    //依赖书
    function getCurrUserUrls($formtype,$comtype)
    {
    	$sql = null;
    	if($comtype == "1")
    	{
    		$sql = "  SELECT * FROM m_urls WHERE formtype='$formtype' AND useright='2'";
    	}
    	else 
    	{
    		$sql = "  SELECT * FROM m_urls WHERE formtype='$formtype'";
    	}
    	
    	$db = $this->db;
     	$rs = $db->exceuteQuery($sql);
     	return $rs;
    }
    
    //指示书
	function getCurrUserUrls_instruct($formtype,$comtype)
    {
    	$sql = null;
    	if($comtype != "1")
    	{
    		$sql = "  SELECT * FROM m_urls WHERE formtype='$formtype' AND (useright='2' OR useright='1')";
    	}
    	else 
    	{
    		$sql = "  SELECT * FROM m_urls WHERE formtype='$formtype' AND (useright='2' OR useright='0')";
    	}
    	
    	$db = $this->db;
     	$rs = $db->exceuteQuery($sql);
     	return $rs;
    }
}
?>