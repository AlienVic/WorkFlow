<?php

require_once('BaseBusiness.php');
require_once('DateUtil.php');
require_once('SupportException.php');
require_once('SessionUtil.php');

class LoginBusiness extends BaseBusiness
{

    /**
     * construct メソッドが定義する。<br>
     */
    function __construct()
    {
        $this->className = "LoginBusiness";

        parent::__construct();
		
    }

	/**
     * ユーザログインチェック。
     *
     * @param string $userId　ユーザID
     * @param string $pw　パスワード
     */
    function validateLogin($email, $pw)
    {
    	$staffName = StringUtil::addStrips($staffName);

		// ログインユーザ情報を取得する
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
        ,com.`name`  companyname
        ,com.appreviation
        ,com.type  comtype
        ,u.idm_depart
        ,dep.departname
        ,u.usertype
        ,u.userright
		,u.proxyid
		,u.proxyflg
        FROM m_user    u
        INNER JOIN      m_company  com              ON    u.idm_company = com.ID
        INNER JOIN      m_depart      dep               ON    u.idm_depart = dep.ID

        WHERE concat(concat(u.email1,'@'),u.email2)='$email' AND u.`password`='$pw' AND u.delflag='0'";
        $db = $this->db;
        $result = $db->exceuteQuery($sql);

        if($result[0]['ID'] == NULL || count($result) == 0) {
			// ログインユーザとパスワードのDBあるがチェック
			$error = new SupportException();
        	$error->addError('E4001', sprintf(MessageUtil::getMessage('E4001')));
            throw $error;
        } else {
        	$_SESSION['login_ok'] = '1';
        }
        $result = $result[0];

		
        return $result;
    }
    



}
