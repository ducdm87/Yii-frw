<?php

/**
 * Description of system
 *
 * @author Administrator
 */
class YError {

    //put your code here
    private static $message = "";

    static function raseNotice($message) {
        YError::$message = $message;
        Yii::app()->session['message'] = $message;
        Yii::app()->session['rasestatuscode'] = "notice";
    }

    static function raseWarning($message) {
        YError::$message = $message;
        Yii::app()->session['message'] = $message;
        Yii::app()->session['rasestatuscode'] = "warning";
    }

    static function showMessage() {

//         echo "<pre>" . print_r(Yii::app()->session, true) . "</pre> <hr />";
//         die("buging");

        $message = Yii::app()->session['message'];
        if (!empty($message) and $message != "") {
            echo '<div id="system-message">';
            echo '<div class="' . Yii::app()->session['rasestatuscode'] . '">';
            echo $message;
            echo '</div>';
            echo '</div>';
            Yii::app()->session['message'] = null;
            Yii::app()->session['rasestatuscode'] = null;
        }
    }

}
