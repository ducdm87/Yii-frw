<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends CFormModel {

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
            $instance = new Post();
        }
        return $instance;
    }

    public function getPosts($limit = 10, $offset = 0, $where = '') {

        if ($limit > 0) {
            $this->command->limit($limit, $offset);
        }
        if ($where && is_array($where)) {
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $param = explode('.', $key);
                    if (is_array($param))
                        $param = $param[1];
                    $this->command->where(array("OR", "$key" . "=:" . "$param"), array("$param" => $value));
                }
            }
        }

        $results = $this->command->select('p.*,c.title as name,c.id as cid,c.alias as calias')
                ->from("$this->table  p")
                ->join("$this->table_categories  c", 'p.catid=c.id')
                ->queryAll();
        return $results;
    }

    public function getPostByCategories($cid, $limit = 10, $offset = 0, $where = array()) {

        if ($limit > 0) {
            $this->command->limit($limit, $offset);
        }

        if ($where && is_array($where)) {
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $param = explode('.', $key);
                    if (is_array($param))
                        $param = $param[1];
                    $this->command->where(array("OR", "$key" . "=" . "$param"), array("$param" => $value));
                }
            }
        }

        $results = $this->command->select('p.*,c.title as name,c.id as cid,c.alias as calias')
                ->from("$this->table  p")
                ->leftJoin("$this->table_categories  c", 'p.catid=c.id')
                ->where("c.id=$cid")
                ->queryAll();

        return $results;
    }

    public function getPostById($id) {
        
        $query = "SELECT * FROM " . $this->table . " WHERE id =" . $id . "";
        $conmmand = Yii::app()->db->createCommand($query);
        $result = $conmmand->queryRow();
        return $result;
    }

}
