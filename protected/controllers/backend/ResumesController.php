<?php

class ResumesController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{rsm_resume}}";
    var $tbl_template = "{{rsm_template}}";
    var $tbl_template_detail = "{{rsm_template_html}}";
    var $tbl_field = "{{rsm_field}}";
    var $tbl_field_sub = "{{rsm_field_sub}}";
    var $tbl_field_sub_select = "{{rsm_field_sub_select}}";
    var $tbl_field_user = "{{rsm_field_user}}";
    var $tbl_field_value = "{{rsm_filed_value}}";

    function init() {
        parent::init();
    }

    // for resume
    public function actionDisplay() {
        $this->addBarTitle("Resumes <small>[list]</small>", "user");
        $model = Resume::getInstance();
//        var_dump($model); die;
        $items = $model->getResumes();
        $lists = $model->getRList();        
        $this->render('items', array("items" => $items, "lists"=>$lists));
    }

    function actionPreviewresume() {
        $model = Resume::getInstance();
        $cid = Request::getVar('cid', 0);
        if (is_array($cid))
            $cid = $cid[0];

        $resume = $this->loadItem($cid);

        $item = $model->getResumeDetail($cid, $resume['template_id']);

        $html = $model->buildHtml($item);
        echo $html;
        die;
    }

    function actionReMoveresume() {
        $model = Resume::getInstance();
        $cid = Request::getVar('cid', 0);
        if (is_array($cid))
            $cid = $cid[0];
        $item = $model->removeResume($cid);
        die;
    }
    function actionRestoreresume() {
        $model = Resume::getInstance();
        $cid = Request::getVar('cid', 0);
        if (is_array($cid))
            $cid = $cid[0];
        $item = $model->restoreResume($cid);
        die;
    }
    function actionDeleteresume() {
        $model = Resume::getInstance();
        $cid = Request::getVar('cid', 0);
        if (is_array($cid))
            $cid = $cid[0];
        $item = $model->deleteResume($cid);
        die;
    }

    // for setting 
    public function actionSetting() {
        $this->addIconToolbar("logout", $this->createUrl("/user/logout"), "cancel");
        $this->addIconToolbar("Edit", $this->createUrl("/user/edit"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/user/new"), "new");
        $this->addIconToolbarDelete();
        $this->addBarTitle("Resumes <small>[Setting]</small>", "user");

        $model = new Users();
        $list_user = $model->getUsers();

        $this->render('setting', array("list_user" => $list_user));
    }

    // for fields 
    public function actionFields() {
        $this->addIconToolbar("Edit", $this->createUrl("/resumes/editfield"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/resumes/editfield"), "new");
        $this->addIconToolbar("Delete", $this->createUrl("/resumes/deletefield"), "delete", 1, 1, "Please select a item from the list to delete");
        $this->addBarTitle("Fields <small>[list]</small>", "user");

        $model = Resume::getInstance();
        $action = Request::getVar('action', "");
        if ($action == 'order_fields') {
            $cids = Request::getVar('cids', array());
            $this->saveOrder($cids, "{{rsm_field}}");
            die;
        }
        
        $task = Request::getVar('task', "");
        if($task == "hidden" OR $task == 'publish' OR $task == "unpublish" )
        {
            $cids = Request::getVar('cid');
            for($i=0;$i<count($cids); $i++)
            {
                $cid = $cids[$i];
                $item = $this->loadItem($cid, "id", $this->tbl_field);

                if($item['status'] == 0) $item['status'] = 1;
                else if($item['status'] == 1) $item['status'] = 2;
                else if($item['status'] == 2) $item['status'] = 0;
                
                $this->storeItem($item, $this->tbl_field);
            }
            YiiMessage::raseSuccess("Successfully saved changes status for fields");
        }
        
        $field_data = $model->getFields();

        $this->render('fields', array("field_data" => $field_data));
    }

    public function actionEditfield() {

        $this->addIconToolbar("Save", $this->createUrl("/resumes/savefield"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/resumes/applyfield"), "apply");
        $this->addIconToolbar("Close", $this->createUrl("/resumes/fields"), "cancel");
        $this->addBarTitle("Fields <small>[Edit]</small>", "user");

        $model = Resume::getInstance();

        $cid = Request::getVar('cid', 0);
        $action = Request::getVar('action', "");
        if (is_array($cid))
            $cid = $cid[0];

        $item = array();
        if ($cid != 0) {
            $item = $model->getField($cid);
        }
        if ($item == false) {
            $item['name'] = "";
            $item['status'] = 1;
            $item['ordering'] = 1;
            $item['max_record'] = 1;
            $item['field_sub'] = array();
        }
        $item['id'] = $cid;
        $this->render('editfield', array("item" => $item));
    }

    function actionApplyfield() {
        global $mainframe;
        $model = Resume::getInstance();
        if ($model->saveField()) {
            $f_id = Request::getVar('cid', '');
            $f_name = Request::getVar('name', '');
            $link_edit = $this->createUrl("/resumes/editfield") . "?cid[]=" . $f_id;
            YiiMessage::raseSuccess("Successfully saved changes field to : " . $f_name);
            $mainframe->redirect($link_edit);
        } else {
            YiiMessage::raseWarning("Something error");
            $mainframe->redirect($this->createUrl("/resumes/fields"));
        }
    }

    function actionSavefield() {
        global $mainframe;
        $model = Resume::getInstance();
        if ($model->saveField()) {
            $f_name = Request::getVar('name', '');
            YiiMessage::raseSuccess("Successfully saved changes field to : " . $f_name);
            $mainframe->redirect($this->createUrl("resumes/fields"));
        } else {
            YiiMessage::raseWarning("Something error");
            $mainframe->redirect($this->createUrl("/resumes/fields"));
        }
    }

    function actionDeletefield() {
        global $mainframe;
        $cids = Request::getVar('cid', array());
        $model = Resume::getInstance();
        $link_tem = $this->createUrl("/resumes/fields");
        foreach ($cids as $cid) {
            $arr_template = "";
            if (!$model->deleteField($cid, $arr_template)) {
                $arr_template = array_unique($arr_template);
                $str = implode(',', $arr_template);
                YiiMessage::raseWarning("Error remove field(s). Something field is using by template !!! (" . $str . ") ");
                $mainframe->redirect($link_tem);
            }
        }
        YiiMessage::raseSuccess("Successfully remove field(s)");
        $mainframe->redirect($link_tem);
    }

    function actionRemovesubfield() {
        $model = Resume::getInstance();
        $model->removeSubfield();

        $obj_result = new stdClass();
        $obj_result->result = true;
        $obj_json = json_encode($obj_result);
        echo $obj_json;
        die;
    }

    // for template
    public function actionTemplates() {
        $this->addIconToolbar("Edit", $this->createUrl("/resumes/edittemplate"), "edit", 1, 1, "Please select a item from the list to edit");
        $this->addIconToolbar("New", $this->createUrl("/resumes/edittemplate"), "new");
        $this->addIconToolbar("Delete", $this->createUrl("/resumes/deletetemplate"), "delete", 1, 1, "Please select a item from the list to delete");
//        $this->addIconToolbarDelete();
        $this->addBarTitle("Resumes <small>[templates]</small>", "user");

        $model = Resume::getInstance();

        $action = Request::getVar('action', "");
        
        if ($action == 'order_template') {
            $this->saveOrder(Request::getVar('cids', array()), "{{rsm_template}}");
            die;
        }
        
        $task = Request::getVar('task', "");
        if($task == "hidden" OR $task == 'publish' OR $task == "unpublish" )
        {
            $cids = Request::getVar('cid');
            for($i=0;$i<count($cids); $i++)
            {
                $cid = $cids[$i];
                $item = $model->getTemplate($cid);
                if($item['status'] == 0) $item['status'] = 1;
                else if($item['status'] == 1) $item['status'] = 2;
                else if($item['status'] == 2) $item['status'] = 0;
                
                $this->storeItem($item, $this->tbl_template);
            }
            YiiMessage::raseSuccess("Successfully saved changes status for templates");
        }

        $temp_data = $model->getTemplates();

        $this->render('templates', array("temp_data" => $temp_data));
    }

    function actionEdittemplate() {
        global $mainframe,$hideMenu;
        
        $this->addIconToolbar("Save", $this->createUrl("/resumes/savetemplate"), "save");
        $this->addIconToolbar("Apply", $this->createUrl("/resumes/applytemplate"), "apply");
        $this->addIconToolbar("Close", $this->createUrl("/resumes/templates"), "cancel");
        $this->addBarTitle("Template <small>[Edit]</small>", "user");

        $model = Resume::getInstance();
//        var_dump($model); die;
        $cid = Request::getVar('cid', 0);

        if (is_array($cid))
            $cid = $cid[0];
        $item = array();
        if ($cid != 0) {
            $item = $model->getTemplate($cid);
            $item_detail = $model->getTemplateEdit($cid);
        }
        if ($item == false) {
            $item = array();
            $item['name'] = "";
            $item['thumbs'] = "";
            $item['status'] = 1;
            $item['ordering'] = 1;
            $item_detail = array();
        }
        $item['id'] = $cid;
        $field_data = $model->getFields();
        $hideMenu = 1;
        $this->render('edittemplate', array("item" => $item, "item_detail" => $item_detail, "field_data" => $field_data));
    }

    function actionPreviewtemplate() {
        $model = Resume::getInstance();
        $cid = Request::getVar('cid', 0);

        if (is_array($cid))
            $cid = $cid[0];
        $html_template = $model->templateBuildHtml($cid);
        echo $html_template;
        die;
    }

    function actionDeletetemplate() {
        global $mainframe;
        $cids = Request::getVar('cid', array());
        $model = Resume::getInstance();
        $link_tem = $this->createUrl("/resumes/templates");
        foreach ($cids as $cid) {
            if (!$model->deleteTemplate($cid)) {
                YiiMessage::raseWarning("Error remove template(s). Something template is using by user !!! ");
                $mainframe->redirect($link_tem);
            }
        }
        YiiMessage::raseSuccess("Successfully remove template(s)");
        $mainframe->redirect($link_tem);
    }

    function actionApplytemplate() {
        global $mainframe;
        $model = Resume::getInstance();
        $t_id = $model->saveTemplate();
        $t_name = Request::getVar('name', '');
        $link_edit = $this->createUrl("/resumes/edittemplate") . "?cid[]=" . $t_id;
        YiiMessage::raseSuccess("Successfully saved changes template to : " . $t_name);
        $mainframe->redirect($link_edit);
    }

    function actionSavetemplate() {
        global $mainframe;
        $model = Resume::getInstance();
        $model->saveTemplate();
        $t_name = Request::getVar('name', '');
        YiiMessage::raseSuccess("Successfully saved changes field to : " . $t_name);
        $mainframe->redirect($this->createUrl("resumes/templates"));
    }

    function actionTrash() {
        $this->addBarTitle("Trash", "trash");

        $model = Resume::getInstance();
        Request::setVar('status',-1);
        $items_resume = $model->getResumes();        
        $this->render('trash', array("items_resume" => $items_resume));
    }

}
