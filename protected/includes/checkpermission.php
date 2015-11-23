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

        $string_url = CheckPerMission::get_fullurl();
	$arr_url = CheckPerMission::get_arr_url();
        
        var_dump($string_url);
        var_dump($arr_url);
       echo '<hr />';

        if (CheckPerMission::step1())
            return true;
        if (CheckPerMission::step2())
            return false;
        if (CheckPerMission::step3())
            return true;
        die;
        if($mainframe->isBackEnd())
            return false;
        else return true; 
    }

    static function step1() {
        
    }

    static function step2() {
        
    }

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

}
