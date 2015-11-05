<?php 
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
global $app; 
error_reporting(E_ALL);
$config_frontend = "frontend.php";
require_once dirname(__FILE__).'/protected/includes.php'; 

$yii = dirname(__FILE__).'/framework/yii.php';
$config = dirname(__FILE__)."/protected/config/$config_frontend";
 
// Remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
 
require_once($yii);
$app = Yii::createWebApplication($config);
require_once dirname(__FILE__).'/protected/router.php';

$params = Router::parseLink($_SERVER['REQUEST_URI']);
 global $pagetype;
$pagetype = 2;

if(isset($params['controller']) AND isset($params['action']) AND $pagetype == 1){
    $cur_temp = "trangbenhvien"; 
    setSysConfig("sys.template",$cur_temp); 
    setSysConfig("sys.template.path",ROOT_PATH . "/themes/$cur_temp/"); 
    setSysConfig("sys.template.url","/themes/$cur_temp/"); 

    // thu tu uu tien: theme/$template => protected/apps/frontend/$app/views => /protected/views/frontend
    if(isset($params['app'])){
        $app->setControllerPath(ROOT_PATH.'/protected/apps/frontend/'.$params['app'].'/controllers/');
        if(is_dir(ROOT_PATH."/themes/$cur_temp"))                  
          $app->setViewPath(ROOT_PATH."/themes/$cur_temp");
        else $app->setViewPath(ROOT_PATH.'/protected/apps/frontend/'.$params['app'].'/views/');
    }else{
        $app->setControllerPath(ROOT_PATH.'/protected/controllers/frontend');
        $app->setViewPath(ROOT_PATH.'/protected/views/frontend');
    }
    $rt = $params['controller'] . "/".$params['action'];
 
    yii::import('application.apps.frontend.'.$params['app'].'.models');
    
    foreach ($params as $key => $value) {
        $_GET[$key] = $value;
        $_REQUEST[$key] = $value;
        $_POST[$key] = $value;
    }
    $app->runController($rt);
}else{
    $app->runEnd('frontend');
}

//  setController  setViewPath  setLayoutPath
//$app->runEnd('frontend');

//Yii::createWebApplication($config)->runEnd('frontend');