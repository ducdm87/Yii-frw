<?php

$settings = array(
    'defaultController' => 'cpanel',
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
          //  'showScriptName'=>false,
            'rules' => array(
                'backend/ ' => 'cpanel/display',
                'backend' => 'cpanel/display',
                
               // 'backend/<controller>' => '<backend>',
               // 'backend/<controller>/<action>' => '<backend>',
              //  'backend/<controller>/<subcontroller>/<action>' => '<backend>/<action>',                
                   
                'backend/<controller>' => '<controller>',
                'backend/<controller>/<action>' => '<controller>/<action>',
               // 'backend/<controller>/<subcontroller>/<action>' => '<controller>',
                 
               // 'backend/<action:\w+>\-<controller:\w+>' => '<controller>/<action>',
              //  'backend/<folder>.<controller>/<action>' => '<controller>/<action>',               
                
            ),
        ),
        'user' => array(
            'loginUrl' => array('user/login'),
        ),
        'session' => array(
            'class' => 'CHttpSession',
            'sessionName' => md5("back-end-yii:bdasbdabdba"),
        ),
    ),
    'import' => array(
        'application.models.*',
        'application.models.backend.*',        
        'application.includes.*',
    ),
    'params' => array(
        'timeout' => '60',       
    ),
);
return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), $settings
);
