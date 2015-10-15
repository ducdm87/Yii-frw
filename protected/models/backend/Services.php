<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Services extends CFormModel {

    private $table = '{{bv_services}}';
    private $command;
    private $connection;

    function __construct() {
        $this->command = Yii::app()->db->createCommand();
        $this->connection = Yii::app()->db;
    }

    static function getInstance(){
        static $instance;

        if (!is_object($instance)) {
            $instance = new Services();
        }
        return $instance;
    }
    
    
    /**
     * 
     * @param type $data
     * @return boolean
     */
    public function addRecord($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->insert($this->table, $data);
            $transaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            $transaction->rollback();
            return false;
        }
    }

    public function updateRecord($data) {
        
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->update($this->table,$data,'id=:id',array('id'=>$data['id']));
            $transaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 
     * @param type $id
     */
    public function deleteRecord($id) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->delete($this->table, 'id=:id', array('id' => $id));
            $transaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 
     * @param type $limit
     * @param type $offset
     * @param type $where
     * @param type $or_where
     * @param type $order
     * @param type $by
     * @param type $query 
     */
    public function getServices($limit = 5, $offset = 0, $where = array(), $query = false, $or_where = false, $order = false, $by = false) {

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
        $results = $this->command->select('*')
                ->from($this->table)
                ->queryAll();

        return $results;
    }

    public function getServiceById($id) {
        $result = $this->command->select('*')
                ->from($this->table)
                ->where('id=:id',array('id'=>$id))
                ->queryRow();

        return $result;
    }

    
    public function getCountTotal(){
         $results = $this->command->select('*')
                ->from($this->table)
                ->queryAll();
         
        return count($results);
        
    }
    
    
}
