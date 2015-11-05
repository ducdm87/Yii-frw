<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Category extends CFormModel {

    private $table = "{{categories}}";
    private $command;
    private $connection;

    function __construct() {
        parent::__construct();

        $this->command = Yii::app()->db->createCommand();
        $this->connection = Yii::app()->db;
    }

    static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new Category();
        }
        return $instance;
    }

    // danh sách chuyên mục nổi bật
    public function getItems($feature = true, $condition = "", $limit = 5, $start = 0) {        
        $conds = array();
        if($condition != "")
            $conds[] = $condition;
        if($feature == true){
            if($condition == "")
            $conds[] = " feature = 1";
        }
        $condition = implode(" AND ", $conds);
         
        $obj_category = YiiCategory::getInstance();
        $items = $obj_category->loadItems("*", $condition, $oderby = " lft DESC ", $limit, $start);
        if(count($items)){
            foreach($items as &$item){
                $item['link'] = Yii::app()->createUrl("videos/category", array("alias"=>$item['alias']));                
            }
        }
        return $items;
         
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id =" . $id . "";
        $conmmand = Yii::app()->db->createCommand($query);
        $result = $conmmand->queryRow();
        return $result;
    }

    public function getCategoryByAlias($alias) {
        $query = "SELECT * FROM " . $this->table . " WHERE alias =" . $alias . "";
        $conmmand = Yii::app()->db->createCommand($query);
        $result = $conmmand->queryRow();
        return $result;
    }

   

}
