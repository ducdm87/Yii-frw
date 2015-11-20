<?php

class UsersController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{menus}}";
    var $tbl_menuitem = "{{menu_item}}";
    var $item = null;
    var $item2 = null;
    var $items = array();

    function init() {
        parent::init();
    }
    /*
     * For menu type
     */
    public function actionDisplay() {
        global $mainframe, $user;
        if (!$user->isSuperAdmin()) {
            YiiMessage::raseNotice("Your account not have permission to visit page");
            $this->redirect(Router::buildLink("cpanel"));
        }
         $this->addBarTitle("User <small>[list]</small>", "user"); 
        $this->addIconToolbar("Edit", Router::buildLink("users", array("view"=>"user", "layout"=>"edit")), "edit", 1, 1, "Please select a item from the list to edit");
            $this->addIconToolbar("New", Router::buildLink("users", array("view"=>"user", "layout"=>"new")), "new");
    //        $this->addIconToolbarDelete();
            $this->addIconToolbar("Delete", Router::buildLink("users", array("view"=>"user", "layout"=>"remove")), "trash", 1, 1, "Please select a item from the list to Remove");        
            $this->addBarTitle("User <small>[list]</small>", "user");

            $task = Request::getVar('task', "");
            if ($task == "hidden" OR $task == 'publish' OR $task == "unpublish") {
                $cids = Request::getVar('cid');
                for ($i = 0; $i < count($cids); $i++) {
                    $cid = $cids[$i];
                    if ($task == "publish")
                        $this->changeStatus ($cid, 1);                    
                    else $this->changeStatus ($cid, 0);
                }
                YiiMessage::raseSuccess("Successfully saved changes status for users");
            }
            global $user;
            
            $model = new Users();
            
            $groupID = $user->groupID;
            
            $groupID = Request::getVar('filter_group', 0);
            if($groupID == 0)
                $list_user = $model->getUsers(null, null, true);
            else $list_user = $model->getUsers($groupID);
            
            $lists = $model->getList();
            
            $arr_group = $model->getGroups();

            $this->render('list', array("list_user" => $list_user, 'arr_group' => $arr_group,"lists"=>$lists));
    } 
    
}
