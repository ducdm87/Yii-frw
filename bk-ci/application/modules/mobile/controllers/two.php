<?php

//mobile/two/index?tinh=1&time_turn=30&type=0
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'mobile' . EXT;

class two extends mobile {

    function __construct() {
        parent::__construct();

//        if (isset($_GET['checkcap'])) {
//            if (!isset($_GET['captcha'])) {
//                if (!isset($_SESSION['captcha' . $this->data['c_module']]))
//                    $_SESSION['captcha' . $this->data['c_module']] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 5);
//                echo '<div style="display:none">' . json_encode(array('captcha' => $_SESSION['captcha' . $this->data['c_module']])) . '</div>';
//                die;
//            }else {
//                $captcha = $_GET['captcha'];
//                if (!isset($_SESSION['captcha' . $this->data['c_module']]) || $_SESSION['captcha' . $this->data['c_module']] != $captcha) {
//                    echo 'Nhập sai captcha!';
//                    die;
//                }
//            }
//        }
    }

    public function index() {
        $lid = (isset($_GET['tinh']) ? $_GET['tinh'] : 1);
        $time_turn = (isset($_GET['time_turn']) ? $_GET['time_turn'] : 30);
        $type = (isset($_GET['type']) ? $_GET['type'] : 0);

        if ($lid == 0)
            return;

        $this->data['items'] = $this->xs_result_model->getItemsTwo($time_turn, $lid, $type);

        $this->data['lid'] = $lid;
        $this->data['time_turn'] = $time_turn;
        $this->data['type'] = $type;
        $this->data['tmpl'] = 'statistics/two';
        $this->load->view('layout/content', $this->data);
    }

}