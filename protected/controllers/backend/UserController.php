<?php

class UserController extends BackEndController {

    public $item = array();
    public $tablename = "{{users}}";
    public $table_group = "{{users_group}}";
    public $primary = "id";

    function init() {
//echo $this->createUrl('cpanel/display');die;
        $this->item["id"] = 0;
        $this->item["username"] = "";
        $this->item["password"] = "";
        $this->item["email"] = "";
        $this->item["groupID"] = "";
        $this->item["mobile"] = "";
        $this->item["home_phone"] = "";
        $this->item["first_name"] = "";
        $this->item["first_name"] = "";
        $this->item["last_name"] = "";
        $this->item["address"] = "";
        $this->item["city"] = "";
        $this->item["province_state"] = "";
        $this->item["zip_code"] = "";
        $this->item["country"] = "";
        $this->item["status"] = 1;
        $this->item["cdate"] = "";
        $this->item["mdate"] = "";
        $this->item["lastvisit"] = "";
        $this->item["params"] = "";
        parent::init();
    }

    public function actionDisplay() {
        $this->pageTitle = "Home page Display";
        $this->render('default', array("item" => "xin chao"));
    }

    public function actionLogin() {
        $LoginForm = Request::getVar("LoginForm");
        if (Request::getVar("LoginForm") and ($LoginForm['username'] == "" || $LoginForm['password'] == "")) {
            YError::raseNotice("Type your username and password");
            $this->redirect(array('user/login'));
            return;
        }

        $model = new UserForm();
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            $session_id = session_id();
            // validate user input and redirect to the previous page if valid                    
            if ($model->validate() && $model->login()) {
                $this->afterLogin($session_id, session_id());
                $this->redirect($this->createUrl('cpanel/display'));
//                    $this->redirect("/backend/");
            } else {
                YError::raseNotice("Invalid your usename or password");
            }
        }
        $this->pageTitle = "Page login";
        $this->render('login');
    }

    public function actionLogout() {
        Yii::app()->session['userbackend'] = null;
//        Yii::app()->user->logout();
        $this->redirect($this->createUrl('login'));
    }

    function actionCancel() {
        $this->redirect($this->createUrl('users/'));
    }

    function actionNew() {
        $this->addIconToolbar("Save", $this->createUrl("/user/save"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/user/apply"), "apply");
        $this->addIconToolbar("Cancel", $this->createUrl("/user/cancel"), "cancel");
        $this->addBarTitle("User <small>[New]</small>", "user");
        $model = new Users();
        $arr_group = $model->getGroup();
        $this->render('edit', array("item" => $this->item,'arr_group'=>$arr_group));
    }

    function actionEdit() {
        $model = new Users();
        $cid = Request::getVar("cid", 0);

        if (is_array($cid))
            $cid = $cid[0];

        if ($cid == 0) {
            $this->actionNew();
            return;
        }

        $this->addIconToolbar("Save", $this->createUrl("/user/save"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/user/apply"), "apply");
        $this->addIconToolbar("Close", $this->createUrl("/user/cancel"), "cancel");
        $this->addBarTitle("User <small>[Edit]</small>", "user");

        $this->item = $this->loadItem($cid);
        $arr_group = $model->getGroup();
//      $id =   $this->storeItem();
        $this->render('edit', array("item" => $this->item,'arr_group'=>$arr_group));
    }

    function actionApply() {
        $this->storeUser();
        $this->redirect($this->createUrl('user/edit') . "?cid=" . $this->item["id"]);
    }

    function actionSave() {
        $this->storeUser();
        $this->redirect($this->createUrl('users/'));
    }

    function storeUser() {
        global $mainframe;
        $post = $_POST;

        if (!isset($_POST['username'])) {
            YError::raseWarning("Cannot save the user information");
            $this->redirect($this->createUrl('users/'));
        }

        $id = Request::getVar("id", 0);
        $bool = true;
        $this->item = $this->loadItem($id);          
        if($this->item == false){
            $this->item = array();
            $this->item["id"]   =  0;
            $this->item["first_name"]   =   "";
            $this->item["last_name"]   =   "";
            $this->item["username"]   =   "";
            $this->item["email"]   =   "";
            $this->item["status"]   =   "";
            $this->item["groupID"]   =   "";
        }
        
        $this->item['password'] = "";
        
        $this->item = $mainframe->bind($this->item, $post);

        $this->item["id"] = $id;
        $this->item["username"] = Request::getVar("username", 0);
        $item = $this->loadItem($this->item['username'], "username");

      
        if (trim($_POST['username']) == "") {
            YError::raseNotice("You must provide an username.");
            $bool = false;
        } else if ($_POST["password"] != $_POST["repassword"] AND $_POST["password"] != "") {
            YError::raseNotice("Passwords Do Not Match.");
            $bool = false;
        } else if (trim($_POST['email']) == "") {
            YError::raseNotice("You must provide an e-mail address.");
            $bool = false;
        } else if ($item and $item["id"] != $this->item["id"]) {
            YError::raseNotice("This username is already in use.");
            $bool = false;
        } 
        
        $this->item['groupID'] = Request::getVar('gid', 0);
        if ($bool != false) {
            if (isset($this->item["password"]) and $this->item["password"] != "")
                $this->item["password"] = md5($this->item["password"]);
            else
                unset($this->item["password"]);
            
            YError::raseWarning("Successfully saved changes to User: " . $this->item['username']);
            $this->item["id"] = $this->storeItem();
        }
    }

}
