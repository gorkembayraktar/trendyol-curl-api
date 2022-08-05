<?php


class TrendyolUser{

    private $user;

    public function __construct($user){
        $this->user = $user;
    }

    public function getEmail(){
        return $this->user['email'];
    }
    
    public function getUserId(){
        return $this->user['userId'];
    }
}