<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ServicesController extends BackEndController {

    var $primary = 'id';
    var $tablename = "{{bv_services}}";
    private $service_model;

    function init() {
        parent::init();

        $this->service_model = new Services();
    }

    public function actionDisplay($id = false) {
        $item = array();
        
        $model = Services::getInstance();
        $total =$model->getCountTotal();
        
        if ($id) {
            $item = $this->service_model->getServiceById($id);
        }
        
        $lists = $this->service_model->getServices(10, 0);
        $this->render('services', array("lists" => $lists, 'item' => $item,'total'=>$total));
    }

    public function actionAdd() {
        if (isset($_POST) && $_POST) {
            $data = array(
                'id' => (Request::getVar('id', '')) ? Request::getVar('id', '') : false,
                'name' => Request::getVar('name', ''),
                'alias' => Request::getVar('alias', ''),
                'status' => Request::getVar('status', ''),
                'price' => Request::getVar('price', ''),
                'cdate' => date('Y:m:d H:i:s', time()),
                'mdate' => isset($_POST['id']) ? date('Y:m:d H:i:s', time()) : false
            );
            if (isset($data['id']) && $data['id']) {
                if ($this->service_model->updateRecord($data)) {
                    YiiMessage::raseSuccess("Update bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Update fail!.");
                }
            } else {// $_POST['id'] null add new 
                if ($this->service_model->addRecord($data)) {
                    YiiMessage::raseSuccess("Create bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Created fail!.");
                }
            }

            $this->redirect($this->createUrl("/services"));
        }
    }

    
    
    
    public function actionDelete($id = false) {

        if ($this->service_model->deleteRecord($id)) {

            YiiMessage::raseSuccess("Delete bean has success!.");
        } else {

            YiiMessage::raseWarning("Error! Delete fail!.");
        }
        $this->redirect($this->createUrl("/services"));
    }

}
