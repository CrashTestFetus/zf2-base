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

class UserController extends AbstractActionController
{
    public function indexAction(){
        $q = '';
        $this->flashMessenger()->addSuccessMessage('User erfolgreich bearbeitet');
        return array('q' => $q);
    }
    public function editAction(){
    	return array();
    }
    public function addAction(){
        return array();
    }
    public function deleteAction(){
        return array();
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
