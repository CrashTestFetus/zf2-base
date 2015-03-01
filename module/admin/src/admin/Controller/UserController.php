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
use admin\Model\User;

class UserController extends AbstractActionController
{
    protected $userTable;

    public function indexAction(){
        $itemsPerPage = 20;
        $page = $this->params('page',1);
        $order_by = $this->params('order_by', false);
        $order = $this->params('order', 'asc');
        $q = $this->params()->fromQuery('q', $this->params('q', ''));
        $request= $this->getRequest();
        if($request->isPost()){
            $postdata = $request->getPost();
            if(isset($postdata->q)){
                $q = $postdata->q;
            }
        }
        if (!$order_by) {
            // Standardsortierung
            $order_by = 'id';
            $order = 'desc';
        }
        $paginatorFields = array('id' => 'ID',  'name' => 'Name', 'email' => 'Email');
        $paginator = $this->getUserTable()->getAllPaginator($q,array_keys($paginatorFields) ,$order_by, $order);

        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($itemsPerPage)
            ->setPageRange(7); 
        
        //$this->flashMessenger()->addSuccessMessage('User erfolgreich bearbeitet');
        return array('paginator' => $paginator,'paginatorFields' => $paginatorFields,'q' => $q, 'order' => $order, 'order_by' => $order_by);
    }

    public function editAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $postdata = $request->getPost();
            $user = new User();
            $user->exchangeArray($postdata);
            $this->getUserTable()->saveUser($user);

            //set feedback
            $this->flashMessenger()->addSuccessMessage('User successfully edited');

            //redirect
            return $this->redirect()->toRoute('admin/user', array('action' => 'index'));
        }
        $user_id = $this->params('user_id',NULL);
        if($user_id){
            $user = $this->getUserTable()->getUserById($user_id);
            return array('user'=>$user);    
        }
        else{
            $this->flashMessenger()->addErrorMessage('Error: no userid');
            return $this->redirect()->toRoute('admin/user', array('action' => 'index'));
        }
        
    }

    public function addAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $postdata = $request->getPost();
            $user = new User();
            $user->exchangeArray($postdata);
            $this->getUserTable()->saveUser($user);

            //set feedback
            $this->flashMessenger()->addSuccessMessage('User successfully added');

            //redirect
            return $this->redirect()->toRoute('admin/user', array('action' => 'index'));
        }
        return array();
    }

    public function deleteAction(){
        $user_id = $this->params('user_id',NULL);
        if($user_id){
            $this->getUserTable()->deleteUser($user_id);
            $this->flashMessenger()->addSuccessMessage('User sucessfully deleted');    
        }else{
            $this->flashMessenger()->addErrorMessage('Error: no userid');    
        }
        
        return $this->redirect()->toRoute('admin/user', array('action' => 'index'));
    }


    //Tables
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
