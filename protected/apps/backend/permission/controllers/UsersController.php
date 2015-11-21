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
        $this->addBarTitle("Grant user <small>[list]</small>", "user");

        global $user;

        $model = new Users();
        $groupID = $user->groupID;

        $groupID = Request::getVar('filter_group', 0);
        if ($groupID == 0)
            $list_user = $model->getUsers(null, null, true);
        else
            $list_user = $model->getUsers($groupID);

        $lists = $model->getList();
        $arr_group = $model->getGroups();

        $this->render('list', array("list_user" => $list_user, 'arr_group' => $arr_group, "lists" => $lists));
    }
    
    function actionGrant(){
        global $mainframe, $user;
        if (!$user->isSuperAdmin()) {
            YiiMessage::raseNotice("Your account not have permission to visit page");
            $this->redirect(Router::buildLink("cpanel"));
        }
        
        global $user;
        $cid = Request::getVar('cid',0);
        $obj_user = YiiUser::getInstance();
        $obj_user = $obj_user->getUser($cid);
        
        $this->addBarTitle("Grant user <small>[$obj_user->username]</small>", "user");
 
        $this->addIconToolbar("Save", Router::buildLink("permission",array('view'=>'users','layout'=>'save')), "save");
        $this->addIconToolbar("Apply", Router::buildLink("permission",array('view'=>'users','layout'=>'apply')), "apply");        
        $this->addIconToolbar("Close", Router::buildLink("permission",array('view'=>'users','layout'=>'cancel')), "cancel");
        $this->pageTitle = "Edit grant";
        

        $model_resource = Resource::getInstance();
        $model = Users::getInstance();
        
        $items = $model_resource->getItems();
        $all_granted = $model->getGranted();
         
        $this->render('grant', array("items"=>$items,"all_granted"=>$all_granted));
    }
    
    function actionCancel()
    {
        $this->redirect(Router::buildLink("permission",array('view'=>'users')));
    }

}
