<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace admin\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $userTable;
    public function indexAction()
    {
    	$latestUser = $this->getUserTable()->fetchLatest();
        return array('latestUser' => $latestUser);
    }
    public function loginAction(){
    	return array();
    }

    //tables
    public function getUserTable(){
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('admin\Model\UserTable');
        }
        return $this->userTable;
    }

    //Layout auf admin/layout setzen
    public function setEventManager(EventManagerInterface $events){
	    parent::setEventManager($events);
	    $controller = $this;
	    $events->attach('dispatch', function ($e) use ($controller) {
	        $controller->layout('layout/admin');
	    }, 100);
	}
}
