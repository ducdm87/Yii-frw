<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Article extends CFormModel {

    private $table = "{{articles}}";
    private $table_categories = "{{categories}}";
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
            $instance = new Article ();
        }
        return $instance;
    }
    
    function getItems($limit = 10, $start = 0, $where = array()){
        $obj_table = YiiArticle::getInstance();
        $items = $obj_table->getItems(null, $conditions = "", $orderBy ="A.id desc", $limit, $start);
        return $items;
    }
    
    public function getItem($cid){
        $obj_table = YiiArticle::getInstance();
        $result = $obj_table->loadItem($cid);        
        return $result;
    }
    
    public function getListEdit($mainItem)
    {
        $list = array();

        $obj_module = YiiCategory::getInstance();
        $items = $obj_module->loadItems('id value, title text');
        $list['category'] = buildHtml::select($items, $mainItem->catID, "catID","","size=7");
         
        $items = array();
        $items[] = array("value"=>0, "text"=>"Unpublish");
        $items[] = array("value"=>1, "text"=>"Publish");
        $items[] = array("value"=>-1, "text"=>"Hidden");
        $list['status'] = buildHtml::select($items, $mainItem->status, "status");
        
        $items = array();
        $items[] = array("value"=>0, "text"=>"Disable");
        $items[] = array("value"=>1, "text"=>"Enable");        
        $list['feature'] = buildHtml::select($items, $mainItem->feature, "feature");        
        return $list;
    }
    

    public function addPost($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->insert($this->table, $data);
            return $transaction->commit();
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            return $transaction->rollBack();
        }
    }

    public function updatePost($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->update($this->table, $data, 'id=:id', array('id' => $data['id']));
            return $transaction->commit();
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            return $transaction->rollBack();
        }
    }

    public function getPosts($limit = 10, $offset = 0, $where = array()) {

        if ($limit > 0) {
            $this->command->limit($limit, $offset);
        }

        if ($where && is_array($where)) {
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $this->command->where($key, $value);
                } else {
                    foreach ($value as $k => $v) {
                        $this->command->where($k, $v);
                    }
                }
            }
        }

        $results = $this->command->select('p.*,c.title as name')
                ->from("$this->table  p")
                ->leftJoin("$this->table_categories  c", 'p.catid=c.id')
                ->queryAll();

        return $results;
    }

    public function getPostById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id =" . $id . "";
        $conmmand = Yii::app()->db->createCommand($query);
        $result = $conmmand->queryRow();
        return $result;
    }

    public function deleteRecord($id) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->delete($this->table, 'id=:id', array('id' => $id));

            return $transaction->commit();
            ;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());

            return $transaction->rollback();
            ;
        }
    }

}
