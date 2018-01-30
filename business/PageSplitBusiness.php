<?php
require_once('BaseBusiness.php');

class PageSplitBusiness extends  BaseBusiness{
	private  $totalPage = 0;
	private  $totalRow = 0;
	private  $currentPage = 1;
	/**
	 * @return the $totalPage
	 */
	public function getTotalPage() {
		return $this->totalPage;
	}

	/**
	 * @return the $totalRow
	 */
	public function getTotalRow() {
		return $this->totalRow;
	}

	/**
	 * @return the $currentPage
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}

	/**
	 * @param field_type $totalPage
	 */
	public function setTotalPage($totalPage) {
		$this->totalPage = $totalPage;
	}

	/**
	 * @param field_type $totalRow
	 */
	public function setTotalRow($totalRow) {
		$this->totalRow = $totalRow;
	}

	/**
	 * @param field_type $currentPage
	 */
	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	/**
	 * 根据sql取得总记录数
	 * @param sql
	 * @return
	 */
	public  function buildTotalRow($sql)
	{
		
		$result = $this->db->exceuteQuery($sql);
		
		return $result[0][0];
	}
	
	/**
	 * 根据总行数和每页显示记录数取得总页数
	 * @param trow
	 * @param numPerPage
	 * @return
	 */
	public function buildTotalPage($trow,$numPerPage)
	{
		$rt = 0;
		$rt = intval($trow/$numPerPage);
		if($trow%$numPerPage != 0)
		{
			$rt = $rt + 1;
		}
		return $rt;
	}
	/**
	 * 取得起始行数
	 * @param crtpage
	 * @param numPerPage
	 * @return
	 */
	public function buildStart($crtpage,$numPerPage)
	{
		return ($crtpage - 1)*$numPerPage + 1;
	}
	
	protected function QueryForPageSplit($sql,$crtpage,$numPerPage)
	{
		
		$this->logger->info("開始");
		//$rsss = $this->db->exceuteQuery("select count(1) from m_zip_master ORDER BY id desc ");
		//$this->totalRow = $this->db->exceuteQuery($sql);
		
		
		
		$this->totalPage = $this->buildTotalPage($this->totalRow, $numPerPage);
		
		
		
		$this->currentPage = $crtpage;
		$beginrecord = $this->buildStart($crtpage, $numPerPage);
		
		
		
		$sqllimt = " SELECT tabl.* FROM ";
		$sqllimt .= " (";
		
		$sqllimt .= $sql;
		
		$sqllimt .= " ) tabl";
		
		
		$sqllimt .= " LIMIT  $beginrecord,$numPerPage";
		
		
		//print_r($sqllimt);
		//exit;
		
		$result = $this->db->exceuteQuery($sqllimt);
		
		
		
		
		
		$this->logger->info("終了");
		return ($result);
		
	}

}
?>