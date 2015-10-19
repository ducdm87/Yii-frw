<?php

class MenusController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{menus}}";
    var $tbl_menuitem = "{{menu_item}}";
    var $item = null;
    var $item2 = null;
    var $items = array();
    
    private $model;
    private $request;

    function init() {        
        yii::import('application.models.backend.menus.*');
        parent::init();
    }
    /*
     * For menu type
     */
    public function actionMenutypes() {
        $this->pageTitle = "Menu manager";        
        $model = MenuType::getInstance();  
        $obj_menu = YiiMenu::getInstance();
        $task = Request::getVar('task', "");
        if ($task == "hidden" OR $task == 'publish' OR $task == "unpublish") {
            $cids = Request::getVar('cid');            
            for ($i = 0; $i < count($cids); $i++) {
                $cid = $cids[$i];
                if ($task == "publish")
                    $this->changeStatusMenuType ($cid, 1);
                else if ($task == "hidden")
                    $this->changeStatusMenuType ($cid, 2);
                else $this->changeStatusMenuType ($cid, 0);
            }
            YiiMessage::raseSuccess("Successfully saved changes status for menu type");
        }
        
        $this->addIconToolbar("Creat", $this->createUrl("/menus/newmenutype"), "new");
        $this->addIconToolbar("Edit", $this->createUrl("/menus/editmenutype"), "edit", 1, 1, "Please select a item from the list to edit");        
        $this->addIconToolbar("Publish", $this->createUrl("/menus/publishmenutype"), "publish");
        $this->addIconToolbar("Unpublish", $this->createUrl("/menus/unpublishmenutype"), "unpublish");
        $this->addIconToolbar("Delete", $this->createUrl("/menus/removemenutype"), "trash", 1, 1, "Please select a item from the list to Remove");        
        $this->addBarTitle("Menu type <small>[manager]</small>", "user");   
        
        $items = $obj_menu->loadMenus("*",false);        
        $this->render('menutypes', array("items"=>$items));
    }
    
    public function actionNewmenutype() {   
        $this->actionEditmenutype();
    }
    
    public function actionEditmenutype() {   
        setSysConfig("sidebar.display", 0);
        $obj_menu = YiiMenu::getInstance();
        
        $this->addIconToolbar("Save", $this->createUrl("/menus/savemenutype"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/menus/applymenutype"), "apply");
        $items = array();
        
        $cid = Request::getVar("cid", 0);
        
        if (is_array($cid))
            $cid = $cid[0];

        if ($cid == 0) {
            $this->addIconToolbar("Cancel", $this->createUrl("/menus/cancelmenutype"), "cancel");
            $this->addBarTitle("Menu types <small>[New]</small>", "user");        
            $this->pageTitle = "New menu";
        }else{
            $this->addIconToolbar("Close", $this->createUrl("/menus/cancelmenutype"), "cancel");
            $this->addBarTitle("Menu type <small>[Edit]</small>", "user");        
            $this->pageTitle = "Edit menu";           
        }
        $obj_tblMenu = $obj_menu->loadMenu($cid, "*", false); 
       
        $this->render('editmenutype', array("obj_tblMenu"=>$obj_tblMenu));
    }

    function actionApplymenutype() {
        $cid = $this->storeMenuType();
        $this->redirect($this->createUrl('menus/editmenutype') . "?cid=" . $cid);
    }
    
    function actionSavemenutype() {
        $cid = $this->storeMenuType();
        $this->redirect($this->createUrl('menus/menutypes'));
    }
    
    function actionCancelmenutype()
    {
        $this->redirect($this->createUrl('menus/menutypes'));
    }
   
    
    function storeMenuType() {
        global $mainframe;
        $post = $_POST;
        $obj_menu = YiiMenu::getInstance();
        
        $id = Request::getInt("id", 0);
        $obj_tblMenu = $obj_menu->loadMenu($id, "*", false); 
        $obj_tblMenu->bind($post);
      
        if($obj_tblMenu->title == "" AND $obj_tblMenu->alias == "") return false;
        
        YiiMessage::raseSuccess("Successfully saved changes to menu: " . $this->item['title']);
        $obj_tblMenu->store();
        return $obj_tblMenu->id;
    }
    
    function actionRemovemenutype() {
        $cids = Request::getVar("cid", 0);
        $table_menu = YiiTables::getInstance(TBL_MENU);
        if(count($cids) >0){
            for($i=0;$i<count($cids);$i++){
                $cid = $cids[$i];
                //check item first
                $table_menu->remove($cid);
            }
        }
        YiiMessage::raseSuccess("Successfully remove Menutype(s)");
        $this->redirect($this->createUrl('menus/menutypes'));
    }
    
    function actionPublishmenutype()
    {
        $cids = Request::getVar("cid", 0);        
        if(count($cids) >0){
            for($i=0;$i<count($cids);$i++){
                $this->changeStatusMenuType($cids[$i], 1);
            }
        }
        YiiMessage::raseSuccess("Successfully publish Menutype(s)");
        $this->redirect($this->createUrl('menus/menutypes'));
    }
    
    function actionUnpublishmenutype()
    {
        $cids = Request::getVar("cid", 0);        
        if(count($cids) >0){
            for($i=0;$i<count($cids);$i++){                
                $this->changeStatusMenuType($cids[$i], 0);
            }
        }
        YiiMessage::raseSuccess("Successfully unpublish Menutype(s)");
        $this->redirect($this->createUrl('menus/menutypes'));
    }
    
    function changeStatusMenuType($cid, $value)
    {
        $obj_menu = YiiMenu::getInstance();        
        $obj_tblMenu = $obj_menu->loadMenu($cid, "*", false); 
        $obj_tblMenu->status = $value;
        $obj_tblMenu->store();
    }
    
    
    
    /*
     * For menu item
     */

    function actionMenuitems()
    {
        $this->pageTitle = "Menu item";
        $model = MenuItem::getInstance();  
        $menuID = Request::getInt('menu', "");
        if($menuID<=0){
            YiiMessage::raseWarning("Invalid menu id");
            $this->redirect($this->createUrl('menus/menutypes'));
        }
        
        $task = Request::getVar('task', "");
        if ($task == "hidden" OR $task == 'publish' OR $task == "unpublish") {
            $cids = Request::getVar('cid');
            for ($i = 0; $i < count($cids); $i++) {
                $cid = $cids[$i];
                $item = $this->loadItem($cid, "id");

                if ($item['status'] == 0)
                    $item['status'] = 1;
                else if ($item['status'] == 1)
                    $item['status'] = 2;
                else if ($item['status'] == 2)
                    $item['status'] = 0;

                $this->storeItem($item);
            }
            YiiMessage::raseSuccess("Successfully saved changes status for menu type");
        }
        
        $this->addIconToolbar("Creat", $this->createUrl("/menus/newmenuitem?menu=$menuID"), "new");
        $this->addIconToolbar("Edit", $this->createUrl("/menus/editmenuitem?menu=$menuID"), "edit", 1, 1, "Please select a item from the list to edit");        
        $this->addIconToolbar("Publish", $this->createUrl("/menus/publishmenuitem?menu=$menuID"), "publish");
        $this->addIconToolbar("Unpublish", $this->createUrl("/menus/unpublishmenuitem?menu=$menuID"), "unpublish");
        $this->addIconToolbar("Delete", $this->createUrl("/menus/removemenuitem?menu=$menuID"), "trash", 1, 1, "Please select a item from the list to Remove");        
        $this->addBarTitle("Menu items <small>[manager]</small>", "user");   
        
        $obj_menu = YiiMenu::getInstance();
        $items = $obj_menu->loadItems($menuID);
 
        $lists = $model->getList(); 
        
        $this->render('menuitems', array("items"=>$items, "lists"=>$lists));
    }
    
     public function actionNewmenuitem() {                
        $this->actionEditmenuitem();
    }
    
    public function actionEditmenuitem() {
        $menuID = Request::getInt('menu', "");
        if($menuID<=0){
            YiiMessage::raseWarning("Invalid menu id");
            $this->redirect($this->createUrl('menus/menutypes'));
        }
        
        $this->addIconToolbar("Save", $this->createUrl("/menus/savemenuitem?menu=$menuID"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/menus/applymenuitem?menu=$menuID"), "apply");
        $items = array();
        setSysConfig("sidebar.display", 0);
        $cid = Request::getVar("cid", 0);
        $menuID = Request::getInt('menu', "");
        if($menuID<=0){
            YiiMessage::raseWarning("Invalid menu id");
            $this->redirect($this->createUrl('menus/menutypes'));
        }
        
        if (is_array($cid))
            $cid = $cid[0];
        $obj_menu = YiiMenu::getInstance();
        if ($cid == 0) {
            $this->addBarTitle("Menu item <small>[New]</small>", "user");        
            $this->pageTitle = "New menu item";
            $this->addIconToolbar("Cancel", $this->createUrl("/menus/cancelmenuitem?menu=$menuID"), "cancel");        
        }else{
            $this->addBarTitle("Menu item <small>[Edit]</small>", "user");        
            $this->pageTitle = "Edit menu item";
            $this->addIconToolbar("Close", $this->createUrl("/menus/cancelmenuitem?menu=$menuID"), "cancel");
        }
      
        $obj_tblMenuItem = $obj_menu->loadItem($cid);
        
        $model = MenuItem::getInstance();
        $list = $model->getListEdit($obj_tblMenuItem);
         
        $params = array("item"=>$obj_tblMenuItem, "list"=>$list);
        $this->render('editmenuitem', $params);
    }
     
    
    function actionApplymenuitem() {
        list($menuID, $menuItemID) = $this->storeMenuItem();       
        $this->redirect($this->createUrl('menus/editmenuitem?menu='.$menuID.'&cid=' . $menuItemID));
    }
    
    function actionSavemenuitem() {
        list($menuID, $menuItemID) = $this->storeMenuItem();        
        $this->redirect($this->createUrl('menus/menuitems?menu='.$menuID));
    }
    
    function actionCancelmenuitem(){
        $menuID = Request::getInt('menu', "");
        if($menuID<=0){
            YiiMessage::raseWarning("Invalid menu id");
            $this->redirect($this->createUrl('menus/menutypes'));
        }else{           
            $this->redirect($this->createUrl('menus/menuitems?menu='.$menuID));
        }
    }
   
    
    function storeMenuItem() {
        global $mainframe, $db;
        $post = $_POST;
       
        $id = Request::getVar("id", 0);        
        $bool = true;
        
        $old_parent = 0;
        $change_ordering = false;
        if($id != 0){
            $this->item2 = $this->loadItem($id,"", $this->tbl_menuitem); 
            $old_parent = $this->item2['parentID'];
        }
       
        $this->item2 = $mainframe->bind($this->item2, $post);
        
        if($this->item2['title'] == "" AND $this->item2['alias'] == "") return false;
        
        $this->item2["id"] = $id;
        $parent_rgt = 0;
        if($this->item2["parentID"] == 0){
            $this->item2["level"] = 1;
            $query = "SELECT * FROM " . $this->tbl_menuitem . " WHERE parentID = 0 LIMIT 1 ";
            $query_command = $db->createCommand($query);
            $item_parent = $query_command->queryRow();
        }else{
            $item_parent = $this->loadItem($this->item2["parentID"],"", $this->tbl_menuitem);
        }
         
        $this->item2["level"] = $item_parent['level'] +1;
        $this->item2["parentID"] = $item_parent['id'];
        $parent_rgt = $item_parent['rgt'];

        if($id == 0 OR $old_parent != $this->item2["parentID"]){ // tao moi hoac thay doi parent
            $this->item2["lft"] = $parent_rgt;
            $this->item2["rgt"] = $this->item2["lft"] + 1;
            $item_parent['rgt'] = $parent_rgt + 2;
            $change_ordering = true;
        }else if($post['ordering'] != $id){ // xu ly thay doi trong khoi cua no
            $item2 = $this->loadItem($post['ordering'],"", $this->tbl_menuitem);
            
            $change_type = $this->item2["lft"]>$item2['lft']?2:-2;
            $min_lft = $this->item2["lft"]<$item2['lft']?$this->item2["lft"]:$item2['lft'];
            $max_rgt = $this->item2["rgt"]>$item2['rgt']?$this->item2["rgt"]:$item2['rgt'];
            
            $this->item2["lft"] = $item2['lft'];
            $this->item2["rgt"] = $item2['rgt'];
             
            $query = "UPDATE $this->tbl_menuitem "
                    . " SET `lft` = `lft` + $change_type, `rgt` = `rgt` + $change_type "
                    . " WHERE `lft` >=  $min_lft AND `lft` < $max_rgt ";
             
            $this->item2["lft"] = $item2['lft'];
            $this->item2["rgt"] = $item2['rgt'];
            $query_command = $db->createCommand($query);
            $query_command->execute();
            echo "\$query1: ".$query; echo '<hr />';
        }
        
        YiiMessage::raseSuccess("Successfully saved changes to menu item: " . $this->item2['title']);
        $this->item2["id"] = $this->storeItem($this->item2, $this->tbl_menuitem);
         
        if($change_ordering == true){
          //  $this->storeItem($item_parent, $this->tbl_menuitem);
                     
            $this->updateLftRgt($item_parent['id'], $item_parent['parentID']);
        }
        return array($this->item2["menuID"], $this->item2["id"]);
   
    }
    
    function updateLftRgt($id = 0, $root_parent = 0)
    {
        global $mainframe, $db;
        if($id == $root_parent){
            echo "<br />cha con bang nhau: $root_parent <br />";
            //return;
        }
        $item2 = $this->loadItem($id,"", $this->tbl_menuitem); 
        $itemParent = null;
        if($item2['parentID'] != 0)
            $itemParent = $this->loadItem($item2['parentID'],"", $this->tbl_menuitem); 
 
        $query = "UPDATE $this->tbl_menuitem SET `rgt` = `rgt` + 2 WHERE `id` =  $id ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        echo "\$query2-1: ".$query; echo '<hr />';
        if($itemParent != null) {
           // $query = "UPDATE $this->tbl_menuitem SET `rgt` = `rgt` + 2 WHERE id = ". $itemParent['id'];
           // $query_command = $db->createCommand($query);
           // $query_command->execute();
           // echo "\$query2-2: ".$query; echo '<hr />';
            
            $query = "UPDATE $this->tbl_menuitem SET `lft` = `lft` + 2, `rgt` = `rgt` + 2 "
                        . " WHERE `lft`> " . $item2['rgt'] . " AND `lft` < " . $itemParent['rgt'];
            $query_command = $db->createCommand($query);
            $query_command->execute();
            echo "\$query2-3: ".$query; echo '<hr />';            
            $this->updateLftRgt($item2['parentID'], $root_parent);
        }
    }
    
}
