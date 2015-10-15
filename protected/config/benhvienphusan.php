<?php

$itemsmenu = array();
$itemsmenu[] = array("Trang chủ", null,null,null,"http://vietbao.vn",0);
$itemsmenu[] = array("Giá xăng dầu", "benhvienphusan","display",null,WEB_URL,1);
$itemsmenu[] = array("Tin tức", "news","display",null,null,0);
$itemsmenu[] = array("Biểu đồ giá", "benhvienphusan","chart",null,null,0);
$itemsmenu[] = array("Bản đồ cây xăng", "benhvienphusan","maps",null,null,0);
$itemsmenu[] = array("Minh bạch xăng dầu", "news","category","minh-bach-xang-dau","/minh-bach-xang-dau/",0);
$itemsmenu[] = array("Cây xăng gian lận", "news","category","cay-xang-gian-lan","/cay-xang-gian-lan/",0);
fnSetMenuItems($itemsmenu, $type = "mainmenu");

$settings = array(
    'defaultController' => 'homepage',
    'theme'=>'benhvien',
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                // home page
                '' => array('benhvienphusan/display'),
                'bieu-do/' => array('benhvienphusan/chart'),
                'ban-do-cay-xang/<location_alias:[\w-\d]+>' => array('giaxang/maps','urlSuffix'=>'.html','defaultParams'=>array("task"=>"giaxang")),
                'ban-do-cay-xang/' => array('giaxang/maps','defaultParams'=>array("task"=>"giaxang")),
//               
                'tin-tuc/' => array('news/display'),
                'tin-tuc/<alias:[\d\w-]+>' => array('news/category'),
                'tin-tuc/<alias:[\d\w-]+>/trang-<page:[0-9]+>' => array('news/category'),
                'tin-tuc/<cat_alias:[\d\w-]+>/<cid:[0-9]+>-<alias:.*>' => array('news/detail','urlSuffix'=>'.html'),
            ),
        ),
        
        'user' => array(
            'loginUrl' => array('user/login'),
        ),
        'session' => array(
            'class' => 'CHttpSession',
            'sessionName' => md5("front-end-yii:193jjo2ue"),
        ),
    ),
    'import' => array(
        'application.models.*',
        'application.models.frontend.*',        
        'application.includes.*',        
        'application.includes.libs.*',
        'application.components.widget.*',        
    ),
    'params' => array(
        // time out minute
        'timeout' => 60, 
         'adminEmail' => 'ducdm@binhhoang.com',
        'siteoffline' => 0,
        'offlineMessage' => "This site is down for maintenance. Please check back again soon.",
//        'defaultApp' => 'news',
    ),
);
return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), $settings
);
?>