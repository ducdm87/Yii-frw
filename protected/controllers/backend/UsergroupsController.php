<?php

class UsergroupsController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{users_group}}";
    private $model_group;
    private $model;
    private $request;

    function init() {
        parent::init();

        $this->model = new Users();
        $this->model_group = new Group();
        $this->request = Yii::app()->getRequest();
    }

    public function actionDisplay() {

    $item = array();
        $arr_group_list = $this->model_group->getGroups();
        
        if ($this->request->getQuery('id')) {
            // edit
            $item =  $this->getGroupId($this->request->getQuery('id')) ;
           
        } elseif ($this->request->getQuery('delete')) {
            //delete Role Group
            $this->deleteRoleGroup($this->request->getQuery('delete'));
        }

        $this->addIconToolbar("Edit", $this->createUrl("/usergroups/edit"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/usergroups/new"), "new");
        $this->addIconToolbarDelete();
        $this->addBarTitle("Group <small>[list]</small>", "user");


        $arr_group = $this->model->getGroup();

        $this->render('list', array('arr_group_list' => $arr_group_list, 'arr_group' => $arr_group, 'item' => $item));
    }

     
    private function deleteRoleGroup($id = false) {
        if ($id) {
            if ($this->model_group->deleteGroup($id)) {
                YiiMessage::raseSuccess("Delete bean has success!.");
            } else {
                YiiMessage::raseWarning("Error! Delete fail!.");
            }
            $this->redirect($this->createUrl("/users/groups"));
        }
    }

    private function getGroupId($id) {
        $model = new Group();
        return $model->getGroupById($id);
    }
    
    public function actionAddGroup() {
        if (isset($_POST) && $_POST) {
            $data = array(
                'id' => (Request::getVar('id', '')) ? Request::getVar('id', '') : false,
                'name' => Request::getVar('name', ''),
                'lft' => Request::getVar('position', ''),
                'isActive' => 1,
                'value' => Request::getVar('name', ''),
                'parent_id' => Request::getVar('parent', '')
            );
            if (isset($data['id']) && $data['id']) {
                if ($this->model_group->updateGroup($data)) {
                    YiiMessage::raseSuccess("Update bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Update fail!.");
                }
            } else {// $_POST['id'] null add new 
                if ($this->model_group->addGroup($data)) {
                    YiiMessage::raseSuccess("Create bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Created fail!.");
                }
            }

            $this->redirect($this->createUrl("/users/groups"));
        }
    }
    
    public function actionNew() {   
        $this->actionEdit();
    }
    
    public function actionEdit() {   
        setSysConfig("sidebar.display", 0);
        $obj_menu = YiiMenu::getInstance();
        
        $this->addIconToolbar("Save", $this->createUrl("/usergroups/save"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/usergroups/apply"), "apply");
        $items = array();
        
        $cid = Request::getVar("cid", 0);
        
        if (is_array($cid))
            $cid = $cid[0];

        if ($cid == 0) {
            $this->addIconToolbar("Cancel", $this->createUrl("/usergroups/cancel"), "cancel");
            $this->addBarTitle("User group <small>[New]</small>", "user");        
            $this->pageTitle = "New group";
        }else{
            $this->addIconToolbar("Close", $this->createUrl("/usergroups/cancel"), "cancel");
            $this->addBarTitle("User group <small>[Edit]</small>", "user");        
            $this->pageTitle = "Edit group";           
        }
        $obj_tblMenu = $obj_menu->loadMenu($cid, "*", false); 
       
        $this->render('edit', array("obj_tblMenu"=>$obj_tblMenu));
    }
    
    

}
