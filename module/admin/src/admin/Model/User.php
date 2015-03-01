<?php
namespace admin\Model;

class User{
    public $id;
    public $email;
    public $name;

    public function exchangeArray($data){
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->name  = (!empty($data['name'])) ? $data['name'] : null;
    }
}
?>