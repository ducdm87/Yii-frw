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
    
    private $table = "{{extensions}}";
    
    function __construct() {
         $this->table = TBL_EXTENSIONS;
    }
    
    static function & getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new YiiModule();
        }

        return $instance;
    } 
    
    function loadItems($field = "*", $menuID, $condition = "", $oderby = " lft ASC "){
        if($condition != "" AND $condition != null)
        {
            $condition = "menuID = $menuID AND $condition ";
        }else $condition = "menuID = $menuID ";
        $table_menu_item = YiiTables::getInstance(TBL_MENU_ITEM);
        $items = $table_menu_item->loads($field, $condition,$oderby, null);
        return $items;
    }
    
    function loadItem($field = "*", $getSub = true){
        $table_menu = YiiTables::getInstance(TBL_MENU);
        $items = $table_menu->loads($field);
        
        if($getSub == true){
            foreach($items as &$item){
                $item['_items'] = $this->loadItems("*",$item['id']);
            }
        }
       return $items;
    }
    
    function loadXrefMenu($moduleID)
    {
        $obj_menuitem = YiiTables::getInstance(TBL_MODULE_MENUITEM_REF);
        return $obj_menuitem->loadColumn("menuID", "moduleID = $moduleID", null,null);
    }
    
}
