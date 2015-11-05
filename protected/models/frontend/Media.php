<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Media extends CFormModel {

    private $table = "{{videos}}";
    private $table_episode = "{{episode}}";
    private $table_categories = "{{categories}}";
    private $table_like = "{{like}}";
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
            $instance = new Media();
        }
        return $instance;
    }

    public function getMedias($limit = 10, $offset = 0, $where = array(), $query = array(), $oder = 'm.viewed', $by = 'DESC', $random = false) {

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
        
        if ($query) {
            $this->command->where(array('like', 'm.title', "%$query%"));
        }
        if ($oder) {
            $this->command->order("$oder $by");
        }
        if ($random) {
            $this->command->order(array('RAND()'));
        }
        $results = $this->command->select('m.*,c.title as name,c.alias as calias, c.id as cid,ep.*,lk.*')
                ->from("$this->table  m")
                ->join("$this->table_categories  c", 'm.catID=c.id')
                ->join("$this->table_episode  ep", 'm.id=ep.film_id')
                ->leftjoin("$this->table_like lk", "m.id=lk.fid")
                ->queryAll();
        return $results;
    }

    public function getMediaById($id) {

        $results = $this->command->select('f.*,ep.*,lk.*')
                ->from("$this->table  f")
                ->leftJoin("$this->table_episode  ep", 'ep.film_id=f.id')
                ->leftJoin("$this->table_like  lk", 'lk.fid=f.id')
                ->where("f.id=$id")
                ->queryRow();

        return $results;
    }

    public function setView($id) {
        $old_view = $this->get_readview($id);         
        $new_view = isset($old_view['viewed']) ? $old_view['viewed'] + 1 : 1;
        $data = array('viewed' => $new_view);
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->update($this->table, $data, 'id=:id', array('id' => $id));
            $transaction->commit();
            return $new_view;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            return $transaction->rollBack();
        }
    }

    private function get_readview($id) {
        $query = Yii::app()->db->select('viewed')
                ->from($this->table)
                ->where('id=:id', array('id' => $id))
                ->queryRow();
        return $query;
    }

    public function check_userLike($uid, $fid) {
        $command = Yii::app()->db->createCommand()
                ->select('*')
                ->from($this->table_like);
        $command->where(
                array('AND', 'uid = :uid', 'fid =:fid',), array(':uid' => $uid, ':fid' => $fid)
        );
        $result = $command->queryRow();
        return $result;
    }

    public function updateUserLike($data) {
        
    }

    public function addUserLike($data) {
        $transaction = $this->connection->beginTransaction();
        try {
            $oldLike = $this->get_oldLike($data['fid']);
            $data['value'] = isset($oldLike['value']) ? $oldLike['value'] + 1 : 1;
            if (isset($oldLike['like_id']) && $oldLike['like_id']) {
                $data['like_id'] = $oldLike['like_id'];
                $this->command->update($this->table_like, $data, 'like_id=:like_id', array('like_id' => $data['like_id']));
            } else {
                $this->command->insert($this->table_like, $data);
            }
            return $transaction->commit();
        } catch (Exception $exc) {
            Yii::log('Error! :', var_export($exc->getMessage()));
            return $transaction->rollback();
        }
    }

    private function get_oldLike($fid) {
        $query = $this->command->select('like_id,value')
                ->from($this->table_like)
                ->where('fid=:fid', array('fid' => $fid))
                ->queryRow();
        return $query;
    } 
    
    
    function getItems($catID = null, $feature = 0, $limit = 10, $start = 0)
    {
//        filter_order, filter_order_Dir, limit, limitstart
        $filter_order = Request::getVar('filter_order','viewed');
        $filter_order_Dir = Request::getVar('filter_order_Dir','DESC');
        $command = Yii::app()->db->createCommand();
        
        $command->limit($limit, $start);
        $command->order("$filter_order $filter_order_Dir");
        $where = array();
        $where[] = "c.id = $catID";
        $where[] = "a.feature = 1";
        $command->where(implode(" AND ", $where));
        
        $items = $command->select('a.*,c.title as name,c.alias as calias, c.id as cid,ep.*,lk.*')
                ->from("$this->table  a")
                ->join("$this->table_categories  c", 'a.catID=c.id')
                ->join("$this->table_episode  ep", 'a.id=ep.film_id')
                ->leftjoin("$this->table_like lk", "a.id=lk.fid")
                ->queryAll();
   
        
        if(count($items))
            foreach($items as & $item){
                $item['slug'] = $item['id']."-".$item['alias'];
                $item['link'] = Yii::app()->createUrl("videos/detail", array("id"=> $item['slug']));
            }
        return $items;
    }
    
    function getCategoryByAlias($catAlias){
         $command = $this->command->select('*')
                ->from("$this->table_categories")
                ->where("alias=:alias");
         $command->bindValue(":alias", $catAlias);
         $item = $command->queryRow();
                 
         
        return $item;
    }
    
    function getItem($id){
        $command = Yii::app()->db->createCommand();
        $where = array();
        $where[] = " a.status = 1 ";
        $where[] = " a.id = $id ";
        $where[] = " b.status = 1 ";
        $command->where(implode(" AND ", $where));
        
        $item = $command->select('a.*,b.title cat_title, b.alias cat_alias')
                ->from("$this->table  a")                
                ->leftjoin("$this->table_categories  b", 'a.catID=b.id')
                ->queryRow();
        $item['slug'] = $item['id']."-".$item['alias'];
        $item['catslug'] = $item['catID']."-".$item['cat_alias'];
        $item['link'] = Yii::app()->createUrl("videos/detail", array("id"=>$item['id'],"alias"=>$item['alias']));
        $item['link_view'] = Yii::app()->createUrl("videos/setview", array("id"=> $item['id']));
//        $item['link2'] = Yii::app()->createUrl("videos/detail", array("alias"=>$item['alias']));
        $item['catlink'] = Yii::app()->createUrl("videos/category", array("alias"=>$item['cat_alias']));
        return $item;
    }
    
    function getItemByAlias($alias){
        $command = Yii::app()->db->createCommand();
        $where = array();
        $where[] = " a.status = 1 ";
        $where[] = " a.alias = '$alias' ";
        $where[] = " b.status = 1 ";
        $command->where(implode(" AND ", $where));
        
        $item = $command->select('a.*,b.title cat_title, b.alias cat_alias')
                ->from("$this->table  a")                
                ->leftjoin("$this->table_categories  b", 'a.catID=b.id')
                ->queryRow();
        $item['slug'] = $item['id']."-".$item['alias'];
        $item['catslug'] = $item['catID']."-".$item['cat_alias'];
        $item['link'] = Yii::app()->createUrl("videos/detail", array("id"=>$item['id'],"alias"=>$item['alias']));       
        $item['catlink'] = Yii::app()->createUrl("videos/category", array("alias"=>$item['cat_alias']));
        return $item;
    }

}
