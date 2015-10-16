<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Module extends CFormModel {

    private $table = "{{extensions}}";
    private $tbl_menu = '{{menus}}';
    private $primary = 'id';
    
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
            $instance = new Module();
        }
        return $instance;
    }
    
    public function getItems() {
        $obj_module = YiiModule::getInstance();
        $items = $obj_module->loadItems();
        return $items;
    }

    public function addExtention($data) {
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

    public function saveExtention() {
        $transaction = $this->connection->beginTransaction();
        try {
            $this->command->update($this->table, $data, 'id=:id', array('id' => $data['id']));
            $transaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::log('Eror!: ' + $e->getMessage());
            $transaction->rollback();
            return false;
        }
    }

    public function deleteExtention($id) {
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

    public function getExtensionById($cid) {
        global $mainframe;
        $obj_module = YiiModule::getInstance();
        $obj_row = $obj_module->loadItem($cid);
        
        $path = Yii::app()->basePath . '/extensions/modules/' . $obj_row->module;
        $module_xml_file = $path . "/". $obj_row->module.".xml";
         
        if(!file_exists($module_xml_file)){
            YiiMessage::raseWarning("Error! file xml module is not existing!.");
            $mainframe->redirect(Yii::app()->createUrl("/modules"));
        }
        $params = sysLoadXmlParam($module_xml_file, $obj_row->params);
        $obj_row->params = $params;
       
        return $obj_row;
    }  
    
     function getListEdit($mainitem){
        $moduleID = Request::getInt('cid', "");
       
        $list = array();
        $obj_menu = YiiMenu::getInstance();
        $obj_module = YiiModule::getInstance();
        
        $items = $obj_menu->loadMenus();
        $items_xref = $obj_module->loadXrefMenu($moduleID); 
         
        $attr = "";
        $meu_seletec = "selected";
        if($mainitem->menu == "none")
        {
            $attr = 'disabled="disabled"';
        }else if($mainitem->menu == "all")
        {
            $attr = 'disabled="disabled"';
        }
        
         
        $str_html = '<select id="selection-menu" class="inputbox" multiple="multiple" '.$attr.' size="15" name="selection-menu[]" style="min-width: 180px;">';
        foreach($items as $item){
            $str_html .= '<optgroup label="'.$item['title'].'">';
            $_items = $item["_items"];
            foreach($_items as $_item){
                $str = str_repeat("&nbsp; &nbsp; ", $_item['level']-1);
                
                if($mainitem->menu == "all"){
                    $str_html .= '<option value="'.$_item['id'].'" selected ="">'.$str.$_item['title'].'</option>';
                }else if($mainitem->menu == "none"){
                    $str_html .= '<option value="'.$_item['id'].'">'.$str.$_item['title'].'</option>';
                }else{
                    if(in_array($_item['id'], $items_xref))
                        $str_html .= '<option value="'.$_item['id'].'" selected ="">'.$str.$_item['title'].'</option>';
                    else $str_html .= '<option value="'.$_item['id'].'">'.$str.$_item['title'].'</option>';
                }
            }
            $str_html .= '</optgroup>';
        }
        
        $str_html .= "</select>";
        $list['selection-menu'] = $str_html;
        return $list;
    }
}

?> 