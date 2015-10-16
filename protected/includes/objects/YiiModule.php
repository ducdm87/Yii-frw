<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  

class YiiModule{
    private $items = array();
    private $item = array();
    private $active = 0;
    var $_db = null;
    
    private $table = "{{modules}}";
    
    function __construct($db = null) {
        $this->_db = $db;        
        if($this->_db == null) $this->_db = Yii::app()->db;
        
         $this->table = TBL_MODULES;
    }
    
    static function & getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new YiiModule();
        }

        return $instance;
    } 
    
    function loadItems($field = null, $condition = ""){
        if($field == null){
           $field = "a.id, a.title, a.alias, a.cdate, a.mdate, a.ordering, a.position, a.menu, a.module, a.description, a.status + 2*(b.status - 1) as status, a.params";
        }
        
        $command = $this->_db->createCommand()->select($field)
                ->from(TBL_MODULES . " as a")
                ->leftjoin(TBL_EXTENSIONS . " as b", " a.module = b.folder");
        if($condition != null) $command->where($condition);
        $items = $command->queryAll();     
        return $items;
    }
    
    function loadItem($id, $field = "*"){
        $table_menu = YiiTables::getInstance(TBL_MODULES);
        $table_menu->load($id);
       return $table_menu;
    }
    
    function loadXrefMenu($moduleID)
    {
        $obj_menuitem = YiiTables::getInstance(TBL_MODULE_MENUITEM_REF);
        return $obj_menuitem->loadColumn("menuID", "moduleID = $moduleID", null,null);
    }
    
}
