<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CheckPerMission {
    /* s1: kiem tra user duoc su dung tai nguyen khong
      neu co cho phep thi return true
      neu khong thay noi gi thi sang s2
      s2: kiem tra user co bi cam su dung tai nguyen khong
      neu co bi cam thi return false
      neu khong thay noi gi thi sang s3
      s3:	kiem tra group xem co duoc su dung tai nguyen khong
      neu co cho phep thi return true
      neu khong thay noi gi thi sang s4
     */

    static function checking() {
        global $user, $mainframe;
        if ($user->isSuperAdmin())
            return true;
        $backEnd = $mainframe->isBackEnd();

        $string_url = CheckPerMission::get_fullurl();
        $arr_url = CheckPerMission::get_arr_url();

        $arr_resource = CheckPerMission::getResources($backEnd, $arr_url);
        $query_user = "SELECT * FROM ". TBL_RSM_RESOURCE_XREF . " WHERE object_type = 1 AND objectID = $user->id";
        $query_group = "SELECT * FROM ". TBL_RSM_RESOURCE_XREF . " WHERE object_type = 2 AND objectID = $user->groupID";

        $arr_granted_user = Yii::app()->db->createCommand($query_user)->queryAll();
        $arr_granted_group = Yii::app()->db->createCommand($query_group)->queryAll();
        
        if(count($arr_granted_user)){
            $_arr_granted_user = array();
        }
        
        if (CheckPerMission::step1($arr_resource))
            return true;
        if (CheckPerMission::step2())
            return false;
        if (CheckPerMission::step3())
            return true;
        die;
        if ($mainframe->isBackEnd())
            return false;
        else
            return true;
    }

    
    static function getResources($backEnd = false, $arr_url) {
        $app = Request::getVar('app');

        if ($backEnd) {
            $query_resource = ' (`affected` IN("B", "BF"))';
        } else {
            $query_resource = ' (`affected` IN("F", "BF"))';
        }

        $query = "SELECT * FROM " . TBL_RSM_RESOURCES . " WHERE type = 1 AND app = '$app' AND $query_resource";
        $rows = Yii::app()->db->createCommand($query)->queryAll();

        if (count($rows)) {
            $arr_resources = array();
            $arr_resources_id = array();
            foreach ($rows as $row) {
                $row = (object) $row;
                $str_in = 'app=' . $app;

                if ($row->view != '') {
                    $str_in .= '&view=' . $row->view;
                }
                if ($row->params != "")
                    $str_in .= '&' . $row->params;
                $arr_url2 = $arr_url;
                if (CheckPerMission::Validate_Url($str_in, $arr_url2)) {
                    $arr_resources[$row->id] = $row;
                    $arr_resources_id[] = $row->id;
                }
            }
            //find the heighest level of resource by removing their parent resources
            $arr_resources_be_removed = array();
            foreach ($arr_resources as $key => $value) {
                if (in_array($value->parentID, $arr_resources_id)) {
                    $arr_resources_be_removed[] = $value->parentID;
                }
            }
            //removing
            $new_arr_resources = array();
            foreach ($arr_resources as $key => $value) {
                if (!in_array($key, $arr_resources_be_removed)) {
                    $new_arr_resources[$key] = $value;
                }
            }
            return $new_arr_resources;
        }
    }

    // check allow user
    static function step1(){
        
    }
    
    // check deny user
    static function step2() {
        
    }

    // check allow group
    static function step3() {
        
    }

    static function step4() {
        
    }

    static function get_arr_url() {
        $arr_url = $_REQUEST;
        foreach ($arr_url as &$v) {
            if (!is_array($v)) {
                if (preg_match('/:/', $v) > 0) {
                    $arr_url_temp = explode(':', $v);
                    $v = $arr_url_temp[0];
                }
            } else {
                foreach ($v as &$str_v) {
                    if (!is_array($str_v)) {
                        if (preg_match('/:/', $str_v) > 0) {
                            $arr_url_temp = explode(':', $str_v);
                        }
                    }
                }
            }
        }
        return $arr_url;
    }

    static function get_fullurl() {
        $webune_s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $WebuneProtocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $webune_s;
        $WebunePort = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        return $WebuneProtocol . "://" . $_SERVER['SERVER_NAME'] . $WebunePort . $_SERVER['REQUEST_URI'];
    }

    static function Validate_Url($str_in, $arr_request) {

        //removing something      
        $str_in = preg_replace('/\s+|;|{|}/', '', $str_in);
        $str_in = str_replace('&', '&&', $str_in);
        $str_in = str_replace('|', '||', $str_in);
        $arr_cmp = preg_split('/&&|\(|\)|\|\|/', $str_in);

        $arr_replace = array();
        for ($i = 0; $i < count($arr_cmp); $i++) {
            if ($arr_cmp[$i]) {
                if (!preg_match('/^([^><=!]+)((>=)|(<=)|(!=)|(<)|(>)|(=))([a-z0-9A-Z-_]*)$/', $arr_cmp[$i], $match)) {
                    continue;
                }

                $comparation = $match[2];
                if ($comparation == '=') {
                    $comparation = '==';
                }

                //$arr_key_value = preg_split('/(>=)|(<=)|(!=)|(<)|(>)|(=)/', $arr_cmp[$i]);
                $key = $match[1];
                $value = $match[9];

                $r_key = 'r_key_' . sprintf('%03d', $i);

                //remove [] from key
                $key = str_replace('[]', '', $key);

                if (!isset($arr_request[$key])) {
                    $arr_request[$key] = null;
                }

                if (!is_array($arr_request[$key])) {
                    if ($comparation == '==' || $comparation == '!=') {
                        $r_replace = "'" . md5($arr_request[$key]) . "'" . $comparation . "'" . md5($value) . "'";
                    } else {
                        $r_replace = "'" . floatval($arr_request[$key]) . "'" . $comparation . "'" . floatval($value) . "'";
                    }
                } else {
                    //is array
                    $arr_tmp = array();
                    for ($j = 0; $j < count($arr_request[$key]); $j++) {
                        if ($comparation == '==' || $comparation == '!=') {
                            $str_tmp = "'" . md5($arr_request[$key][$j]) . "'" . $comparation . "'" . md5($value) . "'";
                        } else {
                            $str_tmp = "'" . floatval($arr_request[$key][$j]) . "'" . $comparation . "'" . floatval($value) . "'";
                            //$str_tmp = "'".floatval($arr_cmp[$i])."'".$comparation."'".floatval(($key . '[]=' . $arr_request[$key][$j])."'";
                        }
                        array_push($arr_tmp, $str_tmp);
                    }

                    if ($comparation == '==') {
                        $r_replace = "(" . implode('||', $arr_tmp) . ")";
                    } else {
                        $r_replace = "(" . implode('&&', $arr_tmp) . ")";
                    }
                }

                $arr_replace[$r_key] = $r_replace;

                $str_in = preg_replace('/' . preg_quote($arr_cmp[$i]) . '/', $r_key, $str_in, 1);
            }
        }
        if (!count($arr_replace)) {
            return null;
        }

        foreach ($arr_replace as $key => $value) {
            $str_in = str_replace($key, $value, $str_in);
        }

        $str_eval = "return ($str_in);";
        return @eval($str_eval);
    }

}
