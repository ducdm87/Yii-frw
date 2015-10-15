<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Users extends CFormModel {

    public $tablename = "{{users}}";
    public $table_group = "{{users_group}}";

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function getUsers() {
        $query = "SELECT * FROM " . $this->tablename . " LIMIT 0,10";
        $conmmand = Yii::app()->db->createCommand($query);
        $result = $conmmand->queryAll();
        return $result;
    }

    function getGroup() {
        $arr_group = array();
        $arr_group[29] = array(29, 'Public Front-end', 1, 0);
        $arr_group[18] = array(18, 'Registered', 2, 0);
        $arr_group[19] = array(19, 'Author', 3, 0);
        $arr_group[20] = array(20, 'Editor', 4, 0);
        $arr_group[21] = array(21, 'Publisher', 5, 0);
        $arr_group[30] = array(30, 'Public Back-end', 1, 1);
        $arr_group[23] = array(23, 'Manager', 2, 1);
        $arr_group[24] = array(24, 'Administrator', 3, 1);
        $arr_group[25] = array(25, 'Super Administrator', 4, 1);
        return $arr_group;
    }

}
