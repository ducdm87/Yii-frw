<?php

class UsersController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{users}}";
    var $tbl_group = "{{users_group}}";
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

//        $this->addIconToolbar("logout", $this->createUrl("/user/logout"),"cancel");
        $this->addIconToolbar("Edit", $this->createUrl("/user/edit"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/user/new"), "new");
        $this->addIconToolbarDelete();
        $this->addBarTitle("User <small>[list]</small>", "user");

        $task = Request::getVar('task', "");
        if ($task == "hidden" OR $task == 'publish' OR $task == "unpublish") {
            $cids = Request::getVar('cid');
            for ($i = 0; $i < count($cids); $i++) {
                $cid = $cids[$i];
                $item = $this->loadItem($cid, "id", $this->tablename);

                if ($item['status'] == 0)
                    $item['status'] = 1;
                else if ($item['status'] == 1)
                    $item['status'] = 2;
                else if ($item['status'] == 2)
                    $item['status'] = 0;

                $this->storeItem($item, $this->tablename);
            }
            YiiMessage::raseSuccess("Successfully saved changes status for users");
        }



        $model = new Users();
        $list_user = $model->getUsers();
        $arr_group = $model->getGroup();

//        $this->pageTitle = "Home page Display";        
        $this->render('users', array("list_user" => $list_user, 'arr_group' => $arr_group));
    }

    function actionGroups() {
        $item = array();
        $arr_group_list = $this->model_group->getGroups();
        
        if ($this->request->getQuery('id')) {
            // edit
            $item =  $this->getGroupId($this->request->getQuery('id')) ;
           
        } elseif ($this->request->getQuery('delete')) {
            //delete Role Group
            $this->deleteRoleGroup($this->request->getQuery('delete'));
        }

        $this->addIconToolbar("Edit", $this->createUrl("/user/editgroup"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/user/newgroup"), "new");
        $this->addIconToolbarDelete();
        $this->addBarTitle("Group <small>[list]</small>", "user");


        $arr_group = $this->model->getGroup();

        $this->render('groups', array('arr_group_list' => $arr_group_list, 'arr_group' => $arr_group, 'item' => $item));
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

}
