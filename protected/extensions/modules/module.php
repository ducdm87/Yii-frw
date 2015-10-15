<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $path_module;
$path_module = dirname(__FILE__);


function fnLoadModules($position = "top", $type = "Benvien")
{
    global $datamodule;
    if(!isset($datamodule)) $datamodule = array();
    if(isset($datamodule[$position])) return $datamodule[$position];
    $datamodule[$position] = array();
    $menuID = Request::getVar('menuID',1);
    $query = "SELECT E.* "
                    . " FROM {{extensions}} E LEFT JOIN {{module_menuitem_ref}} M ON E.id = M.moduleID "
                    . " WHERE  E.type = 'module' AND M.menuID = $menuID AND E.position = '$position' AND E.status = 1 ";
    $query_command = Yii::app()->db->createCommand($query);
    $items = $query_command->queryAll();   
    if(count($items)){
        foreach($items as $item){
            $str_module = fnLoadModule($item);
            if($str_module!=""){
                $fn = "modYii_$type";
                if(function_exists($fn))
                    $datamodule[$position][] = $fn($str_module, $item);
                else $datamodule[$position][] = $str_module;
            }
        }
    }
    return implode ("", $datamodule[$position]);
}


function fnLoadModule($module)
{
    global $path_module;
    
    $moduleName = $module['folder'];
     
    if(file_exists($path_module."/$moduleName/$moduleName.php")){
        ob_start();
        require_once $path_module."/$moduleName/$moduleName.php";
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }
    return "";
}