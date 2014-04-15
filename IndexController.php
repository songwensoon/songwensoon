<?php

/**
 * Description of StickyNotesController
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Admin/src/StickyNotes/Controller/IndexController.php:

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Admin\Helpers\Captcha;
use Admin\Message\Message;
class IndexController extends AbstractActionController {
	protected $_adminNavigationsTable;
	protected $_adminUsersTable;
    public function indexAction() {
		$config = $this->getServiceLocator()->get('config');
		$db = $config['db'];
		$data = $this->getAdminNavigationsTable()->getAdminMenu();
		$view = new ViewModel(array('menu'=>$data['menu'],'one_menu'=>$data['one_menu'],'two_menu'=>$data['two_menu'],'navigation'=>$data['navigation'],'navigation_url'=>$data['navigation_url'],'db_config'=>$db));
		$view->setTerminal(true);
		return $view;
	}
	public function loginAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$view = new ViewModel();
		$view->setTerminal(true);
		if ($request->isPost())
		{
			
			$post_data = $request->getPost();
			$session = new Container('captcha');
			$authcode = $post_data['authcode'];
			$username = $post_data['username'];
			$password = $post_data['password'];
			$s_authcode = $session->offsetGet('authcode');
			$message = new Message();
			if($s_authcode != md5($authcode))
			{
				
				$message->addError("验证码错误！",'index','login');
			}else{
				$result = $this->getAdminUsersTable()->AdminGetUserLogin($username,$password);
				if($result)
				{
					return $this->redirect()->toRoute('admin');
				}
			}
			$view->message = $message;
		}
		return $view;
	}


	public function getAdminNavigationsTable() {
		if (!$this->_adminNavigationsTable) {
			$sm = $this->getServiceLocator();
			$this->_adminNavigationsTable = $sm->get('Admin\Model\AdminNavigationsTable');
		}
		return $this->_adminNavigationsTable;
	}

	public function captchaAction(){
		$captcha = new Captcha();
		$captcha->authcode();
		exit;
	}
	public function getAdminUsersTable() {
		if (!$this->_adminUsersTable) {
			$sm = $this->getServiceLocator();
			$this->_adminUsersTable = $sm->get('AdminUser\Model\AdminUsersTable');
		}
		return $this->_adminUsersTable;
	}

}
