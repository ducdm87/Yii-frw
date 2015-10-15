<?php

$settings = array(
    'defaultController' => 'homepage',
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                                '' => 'resume/display',
                'user/active/<activecode:[^\/]+>' => array('user/active'),
                'user/reactive' => array('user/reactive'),
                'user/forgot-password' => array('user/forgotpass'),
                'resume/addnew' => array('resume/addnew'),                
                'resume/edit-layout/<cid:[0-9]+>-[\w-]+\.html' => array('resume/editlayout', '.html'),
                'resume/view-layout/<cid:[0-9]+>-[\w-]+\.html' => array('resume/viewlayout', '.html'),
                'resume/api/getall.rsm' => array('resumeapi/getall', '.rsm'),
                'resume/api/down-file.rsm' => array('resumeapi/downfile', '.rsm'),
                'static/about.html' => array('static/about', '.html'),
                'static/contact.html' => array('static/contact', '.html'),
                'static/resource.html' => array('static/resource', '.html'),
                'my-resumes' => array('resume/myresumes'),
            ),
        ),
        'user' => array(
            'loginUrl' => array('user/login'),
        ),        
                'dbuser' => array(
            'connectionString' => 'mysql:host=localhost;dbname=resume',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'job_',
            'class'  => 'CDbConnection' ,
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
         'adminEmail' => 'hoang.daoxuan@binhhoang.com',
        'siteoffline' => 0,
        'offlineMessage' => 'This site is down for maintenance. Please check back again soon.',
    ),
);
return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), $settings
);
?>