<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Group extends CFormModel {

    private $table = '{{users_group}}';
    private $command;
    private $connection;

    function __construct() {
        $this->command = Yii::app()->db->createCommand();
        $this->connection = Yii::app()->db;
    }

    public function getGroups() {
        $groups = $this->command->select('*')
                ->from($this->table)
                ->queryAll();
        if (isset($groups) && $groups) {

            return $groups;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $data
     * @return boolean
     */
    public function addGroup($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->insert($this->table, $data);
            $transaction->commit();
            return TRUE;
        } catch (Exception $exc) {
            Yii::log('Error! :', var_export($exc->getMessage()));
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 
     * @param type $data
     * @return boolean
     */
    public function updateGroup($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->update($this->table, $data, 'id=:id', array('id' => $data['id']));
            $transaction->commit();
            return TRUE;
        } catch (Exception $exc) {
            Yii::log('Error! :', var_export($exc->getMessage()));
            $transaction->rollback();
            return false;
        }
    }

    public function deleteGroup($id) {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->delete($this->table, 'id=:id', array('id' => $id));
            $transaction->commit();
            return TRUE;
        } catch (Exception $exc) {
            Yii::log('Error! :', var_export($exc->getMessage()));
            $transaction->rollback();
            return false;
        }
    }
    
    public function getGroupById($id){
        $result = $this->command->select('*')
                ->from($this->table)
                ->where('id=:id',array('id'=>$id))
                ->queryRow();

        return $result;
    }

}
