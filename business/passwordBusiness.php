<?php

require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');


class passwordBusiness extends BaseBusiness
{

    /**
     * construct メソッドが定義する。<br>
     */
    function __construct()
    {
        $this->className = "passwordBusiness";

        parent::__construct();
		
    }


function getPwd($uid)
	{
		


        $sql = "SELECT  ID
        ,username1
        ,username2
        ,pronunciation1
        ,pronunciation2
        ,email1
        ,email2
        ,password  FROM  `m_user` WHERE ID  = '$uid'";
        $db = $this->db;
        $result = $db->exceuteQuery($sql);
        

        return $result[0];
	}
	

function editPwdByID($password,$ID)
	{
		


        $sql = "UPDATE `m_user` SET password = '$password'  WHERE  ID = '$ID'";
         $db = $this->db;
        $result = $db->exceuteUpdate($sql);
       

        if ($result) {
            return true;
        }
        return false;
	}
	
	


}

?>