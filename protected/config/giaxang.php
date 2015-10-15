<?php

$itemsmenu = array();
$itemsmenu[] = array("Trang chủ", null,null,null,"http://vietbao.vn",0);
$itemsmenu[] = array("Giá xăng dầu", "giaxang","display",null,WEB_URL,1);
$itemsmenu[] = array("Tin tức", "news","display",null,null,0);
$itemsmenu[] = array("Biểu đồ giá", "giaxang","chart",null,null,0);
$itemsmenu[] = array("Bản đồ cây xăng", "giaxang","maps",null,null,0);
$itemsmenu[] = array("Minh bạch xăng dầu", "news","category","minh-bach-xang-dau","/minh-bach-xang-dau/",0);
$itemsmenu[] = array("Cây xăng gian lận", "news","category","cay-xang-gian-lan","/cay-xang-gian-lan/",0);
fnSetMenuItems($itemsmenu, $type = "mainmenu");

$settings = array(
    'defaultController' => 'homepage',
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                // home page
                '' => array('giaxang/display'),
                'bieu-do/' => array('giaxang/chart'),
                'ban-do-cay-xang/<location_alias:[\w-\d]+>' => array('giaxang/maps','urlSuffix'=>'.html'),
                'ban-do-cay-xang/' => array('giaxang/maps'),
                'minh-bach-xang-dau/' => array('news/category',".html",'defaultParams'=>array("alias"=>"minh-bach-xang-dau")),
                'cay-xang-gian-lan/' => array('news/category',".html",'defaultParams'=>array("alias"=>"cay-xang-gian-lan")),
                'minh-bach-xang-dau/trang-<page:[0-9]+>' => array('news/category',".html",'defaultParams'=>array("alias"=>"minh-bach-xang-dau")),
                'cay-xang-gian-lan/trang-<page:[0-9]+>' => array('news/category',".html",'defaultParams'=>array("alias"=>"cay-xang-gian-lan")),
                'minh-bach-xang-dau/<cid:[0-9]+>-<alias:.*>' => array('news/detail','urlSuffix'=>'.html','defaultParams'=>array("cat_alias"=>"minh-bach-xang-dau")),
                'cay-xang-gian-lan/<cid:[0-9]+>-<alias:.*>' => array('news/detail','urlSuffix'=>'.html','defaultParams'=>array("cat_alias"=>"cay-xang-gian-lan")),
                
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
    ),
);
return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), $settings
);
?>