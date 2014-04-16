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
use Zend\Mvc\Controller\Plugin\Redirect;
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

	public function getAdminUserShell($url){
			$session = new Container('adminuser');
			$auid = $session->offsetGet('auid');
			$ashell = $session->offsetGet('ashell');
			$redirect = new Redirect();
			if($uid && $ashell){
				$row=$this->adminGetUserShell($auid,$ashell);
				if(!$row){echo "无权操作！";die;}
				//登陆不需要验证
				if(($url!='/admin/index/login')&&($url!='/admin/index/captcha')){
					$nav=$this->get_shell($row->m_id,$url);
					if(!$nav){echo "无权操作！";die;}
				}
				
			}else{
				$redirect->toRoute('admin/index/login');
				return false;
			}
	}
	public function adminGetUserShell($uid,$shell){
		$row = $this->select(array('uid' => (int) $uid))->limit(1)->current();
        if (!$row)
            return false;
		$username = $row->username;
		$password = $row->password;

		$shell = $row ? $shell == md5($username.$password.$this->md):FALSE;
		return $shell ? $row : NULL;
	}
	public function getShell($mid,$web,$type=""){
		$resultSet = $this->adapter->query('SELECT * FROM `zendyun_admin_user` as a,`zendyun_admin_user_group` as b WHERE a.`m_id`=b.`id` and b.`id` = ?', array($mid));
		if(!empty($resultSet)){
			foreach ($resultSet as $row) {
				$group_power = $row->group_power;
			}
		}
		$power=unserialize($group_power);

		$navresultSet = $this->adapter->query('SELECT * FROM `zendyun_admin_navigation` WHERE `url` = ?', array($web));
		if(!empty($navresultSet)){
			foreach ($navresultSet as $navrow) {
				$navid = $navrow->id;
			}
		}
		return @in_array($navid,$power)?true:false;
	}

    public function removeAdminNavigation($uid) {
        return $this->delete(array('uid' => (int) $uid));
    }
}
