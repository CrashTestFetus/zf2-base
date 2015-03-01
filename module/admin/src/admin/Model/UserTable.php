<?php
namespace admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate; 
use Zend\Paginator\Adapter\DbSelect;
use admin\Model\User;

class UserTable{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    public function getAllPaginator($q, $columns = array(), $order_by=false, $order=false,  Predicate\Predicate $where = null) {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        $whereState = new Where();
        if ($q) {
            $predicateLike = array();
            $nest = $whereState->AND->NEST;
            if($where){
                foreach($columns as $col){
                    $nest->OR->addPredicate(new Predicate\Like($col, '%'.$q.'%'));
                }
                $nest->AND->addPredicate($where);
            }
            else{
                foreach($columns as $col){
                    $nest->OR->NEST->addPredicate(new Predicate\Like($col, '%'.$q.'%'));
                }
                //$nest->UNNEST;
            }
        }
        if($where && !$q){
            $whereState->AND->addPredicate($where);
        }

        //for facebook-style deletes
        /*
        $where = new Predicate\Predicate();
        $where->equalTo("deleted", 0);
        $whereState->OR->addPredicate($where);
        */
        if ($order_by) {
            if ($order) {
                $order_by .= ' '.$order;
            }
            $select->order($order_by);
        }
        $select->where($whereState);
        $adapter = new DbSelect($select, $sql);
        $paginator = new Paginator($adapter);
        return $paginator;
    }  
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    public function fetchLatest(){
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        $select->order('id DESC');
        $select->limit(5);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
        
    }

    public function getUserById($id){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser(User $user){
        $data = array(
            'email' => $user->email,
            'name'  => $user->name,
        );

        $id = (int) $user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUserById($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Album id does not exist');
            }
        }
    }

    public function deleteUser($id){
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}