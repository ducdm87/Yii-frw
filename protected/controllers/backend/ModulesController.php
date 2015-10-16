<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModulesController extends BackEndController {
 
    var $tablename = "{{modules}}";
    var $tbl_menu = '{{menus}}';
    var $primary = 'id';
    var $item = null;
    
    private $model;

    function init() {
        parent::init();

        $this->model = Module::getInstance();
    }

    public function actionDisplay() {
//        $obj_ext = YiiModule::getInstance();
//        $extension = $obj_ext->loads();
        
        $extension = $this->model->getExtentions();
        $this->render('default', array("extentions" => $extension));
    }

    private function readFileExtention($dir) {
        $lines = array();
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $subfiles = @scandir($dir . '/' . $file);
                    if ($subfiles) {
                        foreach ($subfiles as $entry) {
                            $file_info = pathinfo($entry, PATHINFO_EXTENSION);
                            if ($file_info == "xml") {
                                
                            }
                        }
                    } else {
                        $file_info = pathinfo($file, PATHINFO_EXTENSION);
                        if ($file_info == "xml") {
                            $lines[] = $this->xmlParse(str_replace('.xml', '', $file));
                        }
                    }
                }
            }
        }
        return $lines;
    }

    public function actionCreate($id = false) {
        $item = array();
        setSysConfig("sidebar.display", 0);
        //check boolean id
        if ($id) {
            $item = $this->model->getExtensionById($id);
        }

        $lists = $this->model->getListEdit();
        
        $this->render('add', array("item" => $item, "lists"=>$lists));
    }

    public function actiondoPost() {
        global $mainframe;
        $btn = Request::getVar("btn-action", 0);  
        if($btn == "close" || $btn == "cancel"){
            $this->redirect($this->createUrl("/modules"));
            exit;
        }
        
        $id = Request::getVar("id", 0);
      
        $table_ext = YiiTables::getInstance(TBL_EXTENSIONS);
        $table_ext->load($id);
        $table_ext->bind($_POST);
        
        $table_ext->params = json_encode($_POST['params']);
        $table_ext->store();
        $menu_selected = Request::getVar('selection-menu-select', 'select');
        
        $all_menu = array();
        if($menu_selected == 'all'){
            $obj_menu = YiiMenu::getInstance();
            $items = $obj_menu->loadItems(); 
            foreach($items as $item)
                 $all_menu[] = $item['id'];
        }else if($menu_selected == 'select'){
            $all_menu = $_POST['selection-menu'];
        }
 
        if(count($all_menu) >0 ){
            foreach($all_menu as $menuid){
                $query = "REPLACE INTO ".TBL_MODULE_MENUITEM_REF." SET moduleID = $table_ext->id, menuID = $menuid ";
                Yii::app()->db->createCommand($query)->query();
            }            
        }else{
            $query = "DELETE FROM ".TBL_MODULE_MENUITEM_REF." WHERE moduleID = $table_ext->id ";            
            Yii::app()->db->createCommand($query)->query();
        }
       
 
        YiiMessage::raseSuccess("Successfully save Module(s)");
        if($btn == "save")
            $this->redirect($this->createUrl("/modules"));
        else $this->redirect($this->createUrl("/modules/create?id=". $table_ext->id));
    }

    /**
     * Upload file ajax extention
     */
    public function actionAjaxUploadExtention() {
        if ($dir = $this->isUpload()) {
            $this->readFileExtention($dir);

            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }

    private function recursive_dir($dir) {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file)
                continue;
            if (is_dir("$dir/$file"))
                $this->recursive_dir("$dir/$file");
            else
                unlink("$dir/$file");
        }
        rmdir($dir);
    }

    private function isUpload() {

        if ($_FILES["userfile"]["name"]) {
            $filename = $_FILES["userfile"]["name"];
            $source = $_FILES["userfile"]["tmp_name"];
            $type = $_FILES["userfile"]["type"];

            $name = explode(".", $filename);
            $accepted_types = array('application/zip', 'application/x-zip-compressed',
                'multipart/x-zip', 'application/x-compressed');
            foreach ($accepted_types as $mime_type) {
                if ($mime_type == $type) {
                    $okay = true;
                    break;
                }
            }

            $continue = strtolower($name[1]) == 'zip' ? true : false;
            if (!$continue) {
                return "Please upload a valid .zip file.";
            }

            /* PHP current path */
            $path = Yii::app()->basePath . '/extensions/modules/';
            $filenoext = basename($filename, '.zip');
            $filenoext = basename($filenoext, '.ZIP');

            $myDir = $path . $filenoext; // target directory
            $myFile = $path . $filename; // target zip file
            if (is_dir($myDir))
                $this->recursive_dir($myDir);
            mkdir($myDir, 0777);

            if (move_uploaded_file($source, $myFile)) {
                $zip = new ZipArchive();
                $x = $zip->open($myFile);
                if ($x === true) {
                    $zip->extractTo($myDir);
                    $zip->close();
                    unlink($myFile);
                }
                return $myDir;
            } else {
                return false;
            }
        }
    }

    /**
     * Create folder
     */
    private function createFolder($info) {
        $type = '';

        if ($info['type'] == 'module') {
            $type = 'modules';
        } else if ($info['type'] == 'plugin') {
            $type = 'plugins';
        } else {
            $type = 'modules';
        }

        $path = Yii::app()->basePath . '/extensions/' . $type . '/' . $info['title'];
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $php_file = $info['title'] . '.php';
        $xml_file = $info['title'] . '.xml';
        $rename = 'RENAME.MD';
        if (!file_exists($path . '/' . $php_file)) {
            $fp = fopen($path . '/' . $php_file, 'wb');
            $fp = fclose($fp);
        }
        if (!file_exists($path . '/' . $xml_file)) {
            $fp = fopen($path . '/' . $xml_file, 'wb');
            $this->writeXml($info);
//            @fwrite($fp, $xml);
            $fp = fclose($fp);
        }
        if (!file_exists($path . '/' . $rename)) {
            $fp = fopen($path . '/' . $rename, 'wb');
            $txt = 'This is the string data or code I want to place in the newly created file';
            @fwrite($fp, $txt);
            $fp = fclose($fp);
        }
        if (!file_exists($path . '/tpl')) {
            mkdir($path . '/tpl', 0777, true);
        }
    }

    public function actionupdateStatus() {
        if (isset($_POST) && $_POST) {
            foreach ($_POST['ids'] as $key => $id) {

                if ($this->model->saveExtention(array('id' => $id, 'status' => 0))) {
                    YiiMessage::raseSuccess("Update bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Update fail!.");
                }
                if (isset($_POST['status']) && $_POST['status']) {
                    foreach ($_POST['status'] as $status) {
                        if ($id == $status) {
                            if ($this->model->saveExtention(array('id' => $id, 'status' => 1))) {
                                YiiMessage::raseSuccess("Update bean has success!.");
                            } else {
                                YiiMessage::raseWarning("Error! Update fail!.");
                            }
                        }
                    }
                }
            }
        }
        $this->redirect($this->createUrl("/modules"));
    }

    public function actionDelete($id = false) {

        if ($data = $this->model->getExtensionById($id)) {
            $this->deleteDirectory($data);
        }
        if ($this->model->deleteExtention($id)) {
            YiiMessage::raseSuccess("Delete bean has success!.");
        } else {
            YiiMessage::raseWarning("Error! Delete fail!.");
        }
        $this->redirect($this->createUrl("/modules"));
    }

    private function deleteDirectory($data) {
        if (isset($data['type']) && $data['type']) {
            $path = Yii::app()->basePath . '/extensions/' . $data['type'] . 's/' . $data['title'];
            @mkdir($path, 0777);
        } else {
            $path = $data;
        }
        if (is_array($path)) {
            return;
        }

        if (is_dir($path)) {
            $objects = scandir($path);
            foreach ($objects as $object) {

                if ($object != "." && $object != "..") {
                    if (filetype($path . "/" . $object) == "dir") {
                        if (is_dir($object)) {
                            @rmdir($object);
                        } else {
                            $this->deleteDirectory($path . "/" . $object);
                        }
                    } else {
                        @unlink($path . "/" . $object);
                    }
                }
            }
            @reset($objects);
            @rmdir($path);
        }
    }

    private function writeXml($folder) {

        $type = '';

        if ($folder['type'] == 'module') {
            $type = 'modules';
        } else if ($folder['type'] == 'plugin') {
            $type = 'plugins';
        } else {
            $type = 'modules';
        }
        $path = Yii::app()->basePath . '/extensions/' . $type . '/' . $folder['title'] . '/' . $folder['title'] . '.xml';
        @mkdir($path, 0777);
        $writer = new XMLWriter();
        $writer->openUri($path);
        $writer->startDocument("1.0");
        /* start */
        $writer->startElement("root");
        $writer->startElement('name');
        $writer->text($folder['title']);
        $writer->endElement();

        $writer->startElement('author');
        $writer->text($folder['author']);
        $writer->endElement();

        $writer->startElement('version');
        $writer->text('1.0');
        $writer->endElement();

        $writer->startElement('creationDate');
        $writer->text(date('F j', time()));
        $writer->endElement();

        /* start root parent */
        $writer->startElement('params');
        /* root */
        $writer->startElement('param');
        $writer->writeAttribute('title', '');
        $writer->writeAttribute('path', '');
        /* sub root */
        $writer->startElement('field');
        $writer->writeAttribute('title', '');
        $writer->writeAttribute('path', '');
        /* end sub root */
        $writer->endElement();
        /* end roor */
        $writer->endElement();

        $writer->endElement();
        /* end root parent */
        $writer->endElement();
        /* end start */
        $writer->endDocument();
        $writer->flush();
    }

    private function xmlParse($folder) {
        $path = Yii::app()->basePath . "/extensions/modules/$folder/$folder.xml";
        $xml = simplexml_load_file(htmlentities($path));

        $params = array();

        if ($xml == false) {
            echo "Failed loading XML: ";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
        } else {
            $data = array();
            $data['title'] = ($xml->name) ? $xml->name : false;
            $data['author'] = ($xml->author) ? $xml->author : false;
            $data['creationDate'] = ($xml->creationDate) ? $xml->creationDate : false;
            $data['description'] = ($xml->description) ? $xml->description : false;
            $data['position'] = 1;
            $data['alias'] = '';
            $data['client'] = ($xml->attributes()->client) ? $xml->attributes()->client : false;
            $data['type'] = ($xml->attributes()->type) ? $xml->attributes()->type : false;
            $data['folder'] = ($xml->name) ? $xml->name : false;
            $data['showtitle'] = ($xml->name) ? $xml->name : false;
            $data['status'] = 0;

            foreach ($xml->params as $param) {
                if ($param->param->attributes()) {
                    foreach ($param->param->attributes() as $key => $attr) {

                        $params['param']['attr'][$key] = $attr;
                    }
                }
                if ($param->param->field) {
                    foreach ($param->param->field as $key => $field) {
                        $params['param'][$key][] = $field;
                    }
                }
            }
        }

        $data['params'] = json_encode($params);
        if ($this->model->addExtention($data)) {
            return true;
        } else {
            return false;
        }
    }

}
