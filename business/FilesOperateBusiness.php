<?php
require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');

class FilesOperateBusiness extends BaseBusiness{

	function insertQaFile($data_type,$data_id,$param){
		$this->logger->info("開始");
		$this->sql = "INSERT INTO t_upload_files (seq_no,data_type,data_id,showname,linkname,size,comment,comment_tail) VALUES($param[0],$data_type,$data_id,'$param[1]','$param[2]',$param[3],'$param[4]','$param[5]');";
		$msg = $this->db->exceuteUpdate($this->sql);
		$this->logger->info("終了");
		return $msg;
	}

	function updateFileAllInfo($param){
		$this->logger->info("開始");
		$this->sql = "UPDATE t_files SET seq_no = $param[0], showname = '$param[1]', linkname = '$param[2]', size = $param[3], comment = '$param[4]', comment_tail = '$param[5]' WHERE idt_upload_files = $param[6]";
		$msg = $this->db->exceuteUpdate($this->sql);
		$this->logger->info("終了");
		return $msg;
	}
	
	function updateFileInfo($param){
		$this->logger->info("開始");
		$this->sql = "UPDATE t_files SET seq_no = $param[0], comment = '$param[1]', comment_tail = '$param[2]' WHERE idt_upload_files = $param[3]";
		$this->db->exceuteUpdate($this->sql);
		$this->logger->info("終了");
	}
	
	function deleteFile($id){
		$this->logger->info("開始");
		$this->sql = "DELETE FROM t_files WHERE idt_files = '$id'";
		$msg = $this->db->exceuteUpdate($this->sql);
		$this->logger->info("終了");
		return $msg;
	}

	function delete_file($path){
		if(file_exists("$path")){
			unlink( "$path" );
		}
	}	
	
	function uploadFiles($files,$path,$data_type,$data_id){
		$this->logger->info("開始");
		foreach ($files as $file){
			$msg = 0;
			if($file["error"] == 0){
				$fileType = $this->getFileType($file["name"]);
				$realName = $this->makeRealFileName($fileType);
				$filepath = $path."/".$realName;
				$param = array(0,$file["name"],$realName,$file["size"],"","");
				$this->insertQaFile($data_type, $data_id, $param);
				$this->logger->info("文件開始");
				@copy($file["tmp_name"],$filepath);
				$this->logger->info("文件終了");
			}
		}
		$this->logger->info("終了");
	}
	
	function makeRealFileName($file_type){
		$now_time = date("Y-m-d H:i:s");
		$mm = split(" ",microtime());
		$realname = date("YmdHis").$mm[0].".".$file_type;
		return $realname;
	}
	
	function getFileInfo($id){
		$this->logger->info("開始");
		$this->sql = "SELECT * FROM t_files WHERE ID = $id";
		$rs = $this->db->exceuteQuery($this->sql);
		$this->logger->info("終了");
		return $rs[0];
	}
	
	function getFileInDBlist($id,$type,$condition = null){
		$this->logger->info("開始");
		$this->sql = "SELECT * FROM t_files WHERE data_id = $id AND data_type = $type";
		if($condition != null){
			$this->sql .= $condition;
		}
		$rs = $this->db->exceuteQuery($this->sql);
		$this->logger->info("終了");
		return $rs;
	}
	
	function getFileType($filename){
		$file_type = explode("." ,$filename); 
		$var = count($file_type)-1;
		return strtolower($file_type[$var]);
	}
	
	static function downloadFile($path,$showname,$size){
		if (file_exists($path)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.iconv("utf-8","shift-jis",$showname));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . $size);
			ob_clean();
			flush();
			readfile($path);
			exit;
		}
	}
}
?>