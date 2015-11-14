<?php
  
$settings = array(
    'defaultController' => 'home',
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                                '/' => array('home/display'),
                '' => array('home/'),                             

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
                'timeout' => '60', 
         'adminEmail' => 'ducdm@binhhoang.com',
        'siteoffline' => 0,
        'offlineMessage' => 'This site is down for maintenance. Please check back again soon.',
    ),
);
return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), $settings
);
?>