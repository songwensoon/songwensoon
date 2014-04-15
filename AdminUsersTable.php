<?php

/**
 * Description of StickyNotesTable
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */

namespace AdminUser\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Session\Container;
use Admin\Message\Message;
class AdminUsersTable extends AbstractTableGateway {

    protected $table = 'zendyun_admin_user';
	private $md = '321cba';
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getAdminUser($username) {
        $resultSet = $this->select(function (Select $select) use ($username){
					$select->where(array('username'=>$username));
                    $select->limit(1);
                });
		$entity = '';
		if(!empty($resultSet)){
			foreach ($resultSet as $row) {
				$entity = new Entity\AdminUser();
				$entity->setUid($row->uid)
						->setM_Id($row->m_id)
						->setUsername($row->username)
						->setPassword($row->password)
						->setName($row->name)
						->setDomain($row->domain);
			}
		}
		return $entity;
    }

	public function AdminGetUserLogin($username,$password) {
		$username = str_replace(" ", "", $username);
		$adminobject = $this->getAdminUser($username);
		$message = new Message();
		if(!empty($adminobject))
		{
			$o_password = $adminobject->getPassword();
			$o_username = $adminobject->getUsername();
			if(md5($password) == $o_password)
			{
				$uid = $adminobject->getUid();
				$session = new Container('adminuser');
				$session->offsetSet('auid',$uid);
				$session->offsetSet('username',$username);
				$session->offsetSet('ashell',md5($o_username . $o_password . $this->md));
				$message->addSuccess("登陆成功！",'index','login');
				return true;
			}else
			{
				$message->addError("密码错误！",'index','login');
				return false;
			}
		}else
		{
			$message->addError("用户不存在！",'index','login');
			return false;
		}
		
	}

    public function removeAdminNavigation($uid) {
        return $this->delete(array('uid' => (int) $uid));
    }
}
