<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 



class YiiUser{  
    private $items = array();
    private $item = array();
    private $active = 0;
    
    private $table = "{{users}}";
    
    function __construct() {
         $this->table = TBL_USERS;
    }
    
    static function & getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new YiiUser();
        }

        return $instance;
    }
    
    // lay tat ca user
    function getUsers($condition = null, $fields = "*")
    {
        if(count($this->items)>0)
            return $this->items;
         
        $query = "SELECT * FROM ". $this->table." WHERE status = 1 ORDER BY `lft` ASC ";
        $query_command = Yii::app()->db->createCommand($query);
        $items = $query_command->queryAll();
        
        $this->items = $arr_new;
        return $this->items;
    }
    
    function getUser($cid, $field = "*")
    {

    }
    
    function getGroups($condition = null, $fields = "*", $build_tree){ }
    
    function getGroup($cid, $field = "*"){ }
    
    function login($username = "", $password = "", $remember = false){}
    
    function logout($cid){}
    
    function isLogin($cid = null){}
    
    function isLogout($cid = null){}
    
    function isAdmin($cid = null){}
    
    function isLeader($cid = null){ }
    
    function checkPermistion($arr_url){ }
}
