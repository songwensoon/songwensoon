<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminNavigation
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Admin/src/Admin/Model/Entity/AdminNavigation.php

namespace AdminUser\Model\Entity;

class AdminUser{

    protected $_uid;
    protected $_m_id;
    protected $_username;
	protected $_password;
	protected $_name;
	protected $_domain;
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getUid() {
        return $this->_uid;
    }

    public function setUid($uid) {
        $this->_uid = $uid;
        return $this;
    }

    public function getM_Id() {
        return $this->_m_id;
    }

    public function setM_Id($m_id) {
        $this->_m_id = $m_id;
        return $this;
    }
	
	public function getUsername() {
        return $this->_username;
    }

    public function setUsername($username) {
        $this->_username = $username;
        return $this;
    }

    public function getPassword() {
        return $this->_password;
    }

    public function setPassword($password) {
        $this->_password = $password;
        return $this;
    }

	public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

	public function getDomain() {
        return $this->_domain;
    }

    public function setDomain($domain) {
        $this->_domain = $domain;
        return $this;
    }

}

?>
