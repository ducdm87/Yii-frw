<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ExtentionsController extends BackEndController {

    private $model;

    function init() {
        parent::init();

        $this->model = new Extention();
    }

    public function actionDisplay() {
        $extention = $this->model->getExtentions();

        $this->render('default', array("extentions" => $extention));
    }

    private function xmlParse($data) {
        $p = xml_parser_create();
        xml_parse_into_struct($p, $data, $vals, $index);
        xml_parser_free($p);
        return $vals;
    }

    private function readFileExtentions() {
        $lines = array();
        $path = Yii::app()->basePath . '/extentions/modules/';
        if (isset($path) && $path) {
            if (is_dir($path)) {
                $files = scandir($path);
                foreach ($files as $file) {
                    if ($file != 'module.php' && $file != "." && $file != "..") {
                        $subfiles = @scandir($path . '/' . $file);
                        if ($subfiles) {
                            foreach ($subfiles as $entry) {
                                $file_info = pathinfo($entry, PATHINFO_EXTENSION);
                                if ($file_info == "xml") {
                                    $file_handle = fopen($path . $file . '/' . $entry, 'r');
                                    while (!feof($file_handle)) {
                                        $lines[] = $this->xmlParse(fgets($file_handle));
                                    }
                                    fclose($file_handle);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $lines;
    }

    public function actionCreate() {
        $this->render('add', array("items" => '$items', "lists" => '$lists'));
    }

    public function actiondoPost() {

        if (isset($_POST) && $_POST) {
            $data = array(
                'id' => isset($_POST['id']) ? $_POST['id'] : false,
                'title' => Request::getVar('name', ''),
                'alias' => Request::getVar('alias', ''),
                'ordering' => Request::getVar('order', ''),
                'type' => Request::getVar('type', ''),
                'folder' => Request::getVar('folder', ''),
                'showtitle' => Request::getVar('showtitle', ''),
                'status' => Request::getVar('status', ''),
                'params' => Request::getVar('param', ''),
                'cdate' => isset($_POST['id']) ? date('Y:m:d H:i:s', time()) : false,
                'mdate' => date('Y:m:d H:i:s'),
                'position' => Request::getVar('position', '')
            );

            if (isset($data['id']) && $data['id']) {
                if ($this->model->updateExtention($data)) {

                    YiiMessage::raseSuccess("Update bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Update fail!.");
                }
            } else {
                if ($this->model->addExtention($data)) {
                    // Create folder
                    $this->createFolder($data);
                    YiiMessage::raseSuccess("Create bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Created fail!.");
                }
            }
            $this->redirect($this->createUrl("/extentions"));
        }
    }

    /**
     * Upload file ajax extention
     */
    public function actionAjaxUploadExtention() {
        if ($this->isUpload()) {
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
            $path = Yii::app()->basePath . '/extentions/modules/';
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
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Create folder
     */
    private function createFolder($info) {

        $path = Yii::app()->basePath . '/extentions/modules/' . $info['folder'];
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $php_file = $info['folder'] . '.php';
        $xml_file = $info['folder'] . '.xml';
        $rename = 'RENAME.MD';
        if (!file_exists($path . '/' . $php_file)) {
            $fp = fopen($path . '/' . $php_file, 'wb');
            $fp = fclose($fp);
        }
        if (!file_exists($path . '/' . $xml_file)) {
            $fp = fopen($path . '/' . $xml_file, 'wb');
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

                if ($this->model->updateExtention(array('id' => $id, 'status' => 0))) {
                    YiiMessage::raseSuccess("Update bean has success!.");
                } else {
                    YiiMessage::raseWarning("Error! Update fail!.");
                }
                if (isset($_POST['status']) && $_POST['status']) {
                    foreach ($_POST['status'] as $status) {
                        if ($id == $status) {
                            if ($this->model->updateExtention(array('id' => $id, 'status' => 1))) {
                                YiiMessage::raseSuccess("Update bean has success!.");
                            } else {
                                YiiMessage::raseWarning("Error! Update fail!.");
                            }
                        }
                    }
                }
            }
        }
        $this->redirect($this->createUrl("/extentions"));
    }

    private function createFile() {
        
    }

    private function writeXml() {
        $xml = new DOMDocument();
        $xml_album = $xml->createElement("Album");
        $xml_track = $xml->createElement("Track");
        $xml_album->appendChild($xml_track);
        $xml->appendChild($xml_album);
    }

}
