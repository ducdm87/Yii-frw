<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Router{
    static function parseLink($path)
    {
        $YiiMenu = YiiMenu::getInstance();
        $db = Yii::app()->db;
        $params = array();        
        $all_menu = $YiiMenu->getItems();
        
        $app = Request::getVar('app',null);
        $menuID = Request::getVar('menuID',null);
        if($app != null OR $menuID != null ){
            $params = $_REQUEST;
        }else{
            $path = trim($path, "/");
//            $path = str_replace(".html", "", $path);
            $arr_path = array($path);
            $paths = explode("/", $path);
            $length = count($paths);

            for($i=$length - 1; $i >0; $i--){
                unset($paths[$i]);
                $arr_path[] = implode("/",$paths);
            }
            
            $menuID = $YiiMenu->getActive();
            if(count($arr_path) >0){
                foreach($arr_path as $path){
                    foreach ($all_menu as $key => $menu) {
                        if($path == $menu['path']){
                            $menuID = $key;
                            break 2;
                        }
                    }
                }
            }
           
            $item = $YiiMenu->getItem($menuID);
            if($item['params'] != null)
                foreach ($item['params'] as $key => $value) {
                    $params[$key] = $value;
                }
        }
         
        $params['menuID'] = $menuID;
        setSysConfig("sys.menuID", $menuID);
        return $params;
    }
    
    static function buildLink($param)
    {
        $segman = array();
        return implode("/", $segman)."?". implode("&", $param);
    }
}
 