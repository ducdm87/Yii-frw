<?php

class Resume {

    var $tablename = "{{rsm_resume}}";
    var $tbl_template = "{{rsm_template}}";
    var $tbl_template_detail = "{{rsm_template_html}}";
    var $tbl_field = "{{rsm_field}}";
    var $tbl_field_sub = "{{rsm_field_sub}}";
    var $tbl_field_sub_select = "{{rsm_field_sub_select}}";
    var $tbl_field_user = "{{rsm_field_user}}";
    var $tbl_field_value = "{{rsm_filed_value}}";
    var $default_groupID = 19;
    var $str_error = "";
    var $str_return = "";
    var $return_data = "";
    var $arr_resumes = array();
    var $field_data = array();

    function __construct() {
        $this->default_groupID = DEFAULT_GROUPID;
    }

    static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new Resume();
        }
        return $instance;
    }

    // abc
    function getResumes() {
        global $mainframe, $db;

        $limit = intval(Request::getVar('limit', 10));
        $limitstart = intval(Request::getVar('limitstart', 0));
        $filter_order = Request::getVar('filter_order', "cdate");
        $filter_order_Dir = Request::getVar('filter_order_Dir', "DESC");


        $where = $this->buildWhereResume();
        $query = "SELECT rs.*,tem.name template_name FROM " . $this->tablename . " rs left join " . $this->tbl_template . " tem ON rs.template_id = tem.id"
                . "  $where ORDER BY $filter_order $filter_order_Dir LIMIT $limitstart,$limit";
        $query_command = $db->createCommand($query);
        $this->arr_resumes = $query_command->queryAll();
        return $this->arr_resumes;
    }

    function buildWhereResume() {
        $status = Request::getVar('status', 2);
        $w = array();
        if ($status == 2) {
            $w[] = " rs.status >= 0 ";
        } else {
            $w[] = " rs.status = $status ";
        }
        $where = " ";
        if (count($w))
            $where = " WHERE " . implode('AND ', $w) . " ";
        return $where;
    }

    /*
     * lay ra cac tham so cho trang danh sach resume
     */

    function getRList() {
        global $mainframe, $db;
        $lists = array();
        $limit = intval(Request::getVar('limit', 10));
        $limitstart = intval(Request::getVar('limitstart', 0));
        $filter_order = Request::getVar('filter_order', "cdate");
        $filter_order_Dir = Request::getVar('filter_order_Dir', "DESC");

        $lists['limit'] = $limit;
        $lists['limitstart'] = $limitstart;
        $lists['filter_order'] = $filter_order;
        $lists['filter_order_Dir'] = $filter_order_Dir;

        $where = $this->buildWhereResume();
        $query = "SELECT count(*) FROM " . $this->tablename . " rs $where";
        $query_command = $db->createCommand($query);
        $lists['total'] = $query_command->queryScalar();

        return $lists;
    }

    function getResume($cid) {
        if (count($this->arr_resumes) == 0)
            $this->getResumes();
        for ($i = 0; $i < count($this->arr_resumes); $i++) {
            if ($this->arr_resumes[$i]['id'] == $cid)
                return$this->arr_resumes[$i];
        }
        return null;
    }

    // abc
    function getResumeData($resume_id) {
        global $mainframe, $db;
        $query = "SELECT a.id field_user_id, a.status , a.name field_name, a.field_id, a.field_custome,a.lastmodify , b.id field_value_id, b.field_sub_id, b.content, b.group_id, b.ordering "
                . " FROM  " . $this->tbl_field_user . " a left join " . $this->tbl_field_value . " b on a.id = b.field_user_id "
                . " WHERE a.resume_id = $resume_id "
                . " order by a.ordering,b.ordering";
        $query_command = $db->createCommand($query);

        $items = $query_command->queryAll();
        $arr_new = array();
        foreach ($items as $item) {
            $k1 = $item["field_user_id"];
            if (!isset($arr_new[$k1]))
                $arr_new[$k1] = array();
            $arr_new[$k1]["field_id"] = $item["field_id"];
            $arr_new[$k1]["field_user_id"] = $k1;
            $arr_new[$k1]["field_name"] = $item["field_name"];
            $arr_new[$k1]["field_custome"] = $item["field_custome"];
            $arr_new[$k1]["lastmodify"] = $item["lastmodify"];
            $arr_new[$k1]["status"] = $item["status"];

            if (!isset($arr_new[$k1]["field_value"]))
                $arr_new[$k1]["field_value"] = array();
            $k2 = $item['group_id'];
            if ($item['group_id'] == 0)
                $k2 = $item['field_value_id'];

            if (!isset($arr_new[$k1]["field_value"][$k2]))
                $arr_new[$k1]["field_value"][$k2] = array();
            $k3 = $item['field_value_id'];
            if (!isset($arr_new[$k1]["field_value"][$k2][$k3]))
                $arr_new[$k1]["field_value"][$k2][$k3] = array();

            $arr_new[$k1]["field_value"][$k2][$k3]['field_value_id'] = $k3;
            $arr_new[$k1]["field_value"][$k2][$k3]['field_sub_id'] = $item["field_sub_id"];
            $arr_new[$k1]["field_value"][$k2][$k3]['group_id'] = $item["group_id"];
            $arr_new[$k1]["field_value"][$k2][$k3]['content'] = $item["content"];
            $arr_new[$k1]["field_value"][$k2][$k3]['ordering'] = $item["ordering"];
        }
        $items = $arr_new;

        return $items;
    }

    function getResumeDetail($resume_id, $template_id) {
        // lay data tu rsm_template va rsm_template_html
        $template = $this->getTemplateEdit($template_id);
        // lay data tu rsm_field, rsm_field_sub va rsm_field_sub_select
        $field_data = $this->getFields();
        // lay data tu rsm_field_user va rsm_filed_value
        $resume_data = $this->getResumeData($resume_id);

        // xu li du lieu
        $arr_data = array();
        $field_default = 0;
        $arr_field_user_id = array();
        foreach ($resume_data as $field_user_id => $item) {
            $field_id = $item['field_id'];
            $arr_field_user_id[] = $field_user_id;
            $field = $field_data[$field_id];

            foreach ($item['field_value'] as $k => $its) {
                foreach ($its as $k2 => $it) {
                    if (!isset($field['field_sub'][$it['field_sub_id']]))
                        continue;
                    $fs = $field['field_sub'][$it['field_sub_id']];
                    $item['field_value'][$k][$k2]['name'] = $fs['name'];
                    $item['field_value'][$k][$k2]['data_type'] = $fs['data_type'];
                    $item['field_value'][$k][$k2]['size'] = $fs['size'];
                    $item['field_value'][$k][$k2]['isname'] = $fs['isname'];
                    if ($fs['data_type'] == 4) {

//                        $item['sub_select'][$k] = $fs['field_sub_select'];
                        $item['sub_select'][$it['field_sub_id']] = $fs['field_sub_select'];
                    }
                }
            }
            $item['html'] = $template[$field_id]['content'];
            $item['html'] = str_replace("\r\n", " ", $item['html']);

            $item['max_record'] = $template[$field_id]['max_record'];
            $item['default'] = $template[$field_id]['default'];
            $arr_data[$field_user_id] = $item;
            if ($template[$field_id]['default'] == 1) {
                $field_default = $field_user_id;
            }
        }
        foreach ($arr_data as $field_user_id => $box) {
            $arr_data[$field_user_id]['length_field_value'] = count($box['field_value']);
            $arr_data[$field_user_id]['list_field_value_id'] = array_keys($box['field_value']);
        }
        $arr_data['default'] = $field_default;
        $arr_data['length'] = count($arr_data) - 1;
        $arr_data['list_field_id'] = $arr_field_user_id;
        return $arr_data;
    }

    //abc
    function removeResume($resume_id) {
        global $mainframe, $db;

        $query = "UPDATE " . $this->tablename . " SET status = -1 WHERE id =$resume_id ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        return true;
    }

    //abc
    function restoreResume($resume_id) {
        global $mainframe, $db;

        $query = "UPDATE " . $this->tablename . " SET status = 1 WHERE id =$resume_id ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        return true;
    }

    //abc
    function deleteResume($resume_id) {
        global $mainframe, $db;

        $query = "DELETE FROM " . $this->tablename . " WHERE id =:cid ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':cid', $resume_id);
        $query_command->execute();

        $query = "DELETE FROM " . $this->tbl_field_user . " WHERE resume_id =:cid ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':cid', $resume_id);
        $query_command->execute();

        $query = "DELETE FROM " . $this->tbl_field_value . " WHERE resume_id =:cid ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':cid', $resume_id);
        $query_command->execute();

        return true;
    }

    // abc
    function getTemplates($limit = 15) {
        global $mainframe, $db;
        $query = "SELECT * FROM " . $this->tbl_template . " WHERE 1 = 1 ORDER BY ordering LIMIT 0,$limit ";
        $query_command = $db->createCommand($query);
        $items = $query_command->queryAll();
        return$items;
    }

    // abc
    function getTemplate($template_id = 1) {
        global $mainframe, $db;
        $query = "SELECT * "
                . "FROM " . $this->tbl_template .
                "  WHERE id = $template_id ";
        $query_command = $db->createCommand($query);

        $item = $query_command->queryRow();
        return $item;
    }

    // abc
    function getTemplateEdit($template_id = 1) {
        global $mainframe, $db;
        $query = "SELECT a.id, a.name, a.thumbs,b.rsm_field_id, b.content, b.max_record, b.default, b.status "
                . "FROM " . $this->tbl_template . " a LEFT JOIN " . $this->tbl_template_detail . " b ON a.id = b.template_id " .
                "  WHERE a.id = $template_id";
        $query_command = $db->createCommand($query);

        $items = $query_command->queryAll();
        $arr_new = array();
        foreach ($items as $k => $item) {
            $arr_new[$item['rsm_field_id']] = $item;
        }
        $items = $arr_new;
        return $items;
    }

    // abc
    function getFields() {
        if (count($this->field_data))
            return $this->field_data;
        global $mainframe, $db;
        $query = "SELECT a.id, a.name field_name, a.status, a.ordering , a.max_record "
                . ", b.id field_sub_id, b.name field_sub_name,b.isname, b.data_type, b.size, b.required, b.valid_data, b.default_value, b.space_before "
                . " , c.id field_sub_select_id, c.name as field_sub_select_name  "
                . " FROM  " . $this->tbl_field . " a left join " . $this->tbl_field_sub . " b on a.id = b.filed_id left join " . $this->tbl_field_sub_select . " c on b.id = c.filed_sub_id "
                . " order by a.ordering,b.ordering,c.ordering ";
        $query_command = $db->createCommand($query);

        $items = $query_command->queryAll();

        $arr_new = array();
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            if (!isset($arr_new[$item["id"]]))
                $arr_new[$item["id"]] = array();
            $arr_new[$item["id"]]['name'] = $item["field_name"];
            $arr_new[$item["id"]]['ordering'] = $item["ordering"];
            $arr_new[$item["id"]]['status'] = $item["status"];
            $arr_new[$item["id"]]['max_record'] = $item["max_record"];
            $arr_new[$item["id"]]['using'] = 0;

            if (!isset($arr_new[$item["id"]]['field_sub']))
                $arr_new[$item["id"]]['field_sub'] = array();
            if (!isset($arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]))
                $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']] = array();
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['name'] = $item["field_sub_name"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['data_type'] = $item["data_type"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['size'] = $item["size"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['required'] = $item["required"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['valid_data'] = $item["valid_data"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['default_value'] = $item["default_value"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['space_before'] = $item["space_before"];
            $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['isname'] = $item["isname"];

            if ($item["data_type"] == 4 OR $item["data_type"] == 7) {
                if (!isset($arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'])) {
                    $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'] = array();
                }
                $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'][$item['field_sub_select_id']] = $item['field_sub_select_name'];
            }
        }
        $this->field_data = $arr_new;
        return $this->field_data;
    }

    // abc
    function getField($field_id) {
        $this->field_data = $this->getFields();
        if (!isset($this->field_data[$field_id]))
            return null;
        return $this->field_data[$field_id];
    }

    // abc
    function saveField() {
        global $mainframe, $db;

        $f_id = Request::getVar('cid', 0);
        $f_name = Request::getVar('name', '');
        $f_status = Request::getVar('status', 1);
        $f_ordering = Request::getVar('ordering', 1);
        $f_max_record = Request::getVar('max_record', 10);
        $obj_data_sub_client = Request::getVar('obj_data_sub_client', 1);
        $obj_data_sub_order_client = Request::getVar('obj_data_sub_order_client', 1);

        if ($f_name == "")
            return false;
        if ($f_id != 0) {
            $query = "UPDATE " . $this->tbl_field . " SET name=:name,status=:status, ordering=:ordering,max_record=:max_record   WHERE id = " . $f_id;
        } else {
            $query = "INSERT INTO " . $this->tbl_field . " SET name=:name,status=:status, ordering=:ordering,max_record=:max_record ";
        }
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':name', $f_name);
        $query_command->bindParam(':status', $f_status);
        $query_command->bindParam(':ordering', $f_ordering);
        $query_command->bindParam(':max_record', $f_max_record);
        $query_command->execute();
        if ($f_id == 0) {
            $f_id = $db->lastInsertID;
        }
//        echo "<pre>" . print_r(json_decode($obj_data_sub_client), true) . "</pre>"; die;

        $arr_subfield = json_decode($obj_data_sub_client, true);
        $arr_order = json_decode($obj_data_sub_order_client);

        $order = 1;
        if (count($arr_order))
            foreach ($arr_order as $k => $val) {
                if (!isset($arr_subfield[$val]))
                    return FALSE;
                $subfield = $arr_subfield[$val];
                $sf_id = intval($val);
                if ($sf_id == 0) {
                    $query = "INSERT INTO " . $this->tbl_field_sub
                            . " SET name=:name, data_type=:data_type,  size=:size,required=:required, valid_data=:valid_data, default_value=:default_value, space_before=:space_before, filed_id=$f_id, status = 1, ordering=$order ";
                } else {
                    $query = "UPDATE " . $this->tbl_field_sub . " SET name=:name, data_type=:data_type, size=:size,required=:required, valid_data=:valid_data, default_value=:default_value , space_before=:space_before, ordering=$order WHERE id = " . $sf_id;
                }
                $order++;
                $query_command = $db->createCommand($query);
                $query_command->bindParam(':name', $subfield['name']);
                $query_command->bindParam(':data_type', $subfield['data_type']);
                $query_command->bindParam(':size', $subfield['size']);
                $query_command->bindParam(':required', $subfield['required']);
                $query_command->bindParam(':valid_data', $subfield['valid_data']);
                $query_command->bindParam(':default_value', $subfield['default_value']);
                $query_command->bindParam(':space_before', $subfield['space_before']);

                $query_command->execute();

                if ($sf_id == 0) {
                    $sf_id = $db->lastInsertID;
                }

                if ($subfield['data_type'] == 4 || $subfield['data_type'] == 7) {
                    echo 'update subselect';
                    $sfs_order = 1;
                    foreach ($subfield['field_sub_select'] as $key => $select) {
                        $sfs_id = intval($key);
                        if ($sfs_id == 0) {
                            // insert
                            $query = "INSERT INTO " . $this->tbl_field_sub_select
                                    . " SET filed_sub_id=:filed_sub_id, name=:name,  ordering=$sfs_order";
                        } else {
                            // update
                            $query = "UPDATE " . $this->tbl_field_sub_select . " SET filed_sub_id=:filed_sub_id, name=:name,  ordering=$sfs_order WHERE id = " . $sfs_id;
                        }
                        $sfs_order++;
                        $query_command = $db->createCommand($query);
                        $query_command->bindParam(':filed_sub_id', $sf_id);
                        $query_command->bindParam(':name', $select); 
                        $query_command->execute();
                    }
                }
            }
        return true;
    }

    //abc
    function saveTemplate() {
        global $mainframe, $db;
        $path_thumb = ROOT_PATH . '/templates/resume/thumbs/';
        $link_thumb = '/templates/resume/thumbs';
        $t_id = Request::getVar('cid', 0);
        $t_name = Request::getVar('name', '');
        
        $alias = $mainframe->convertalias($t_name);        
        
        $t_status = Request::getVar('status', '');
        $t_ordering = Request::getVar('ordering', '');
        $t_thumbs = "";
        if ($_FILES['thumbs']['name'] != "") {            
//            $name = $_FILES["thumbs"]["name"];
            $name = $alias.'.png';
            move_uploaded_file($_FILES["thumbs"]["tmp_name"], $path_thumb . '/' . $name);
            $t_thumbs = $link_thumb . '/' . $name;
        }
        $isNew = 1;
        if ($t_id != 0) {
            if ($t_thumbs != "") {
                $query = "UPDATE " . $this->tbl_template . " SET name=:name,status=:status, ordering=:ordering, thumbs=:thumbs  WHERE id = " . $t_id;
            } else {
                $query = "UPDATE " . $this->tbl_template . " SET name=:name,status=:status, ordering=:ordering  WHERE id = " . $t_id;
            }

            $isNew = 0;
        } else {
            if ($t_thumbs != "") {
                $query = "INSERT INTO " . $this->tbl_template . " SET name=:name,status=:status, ordering=:ordering, thumbs=:thumbs ";
            } else {
                $query = "INSERT INTO " . $this->tbl_template . " SET name=:name,status=:status, ordering=:ordering ";
            }

            $isNew = 1;
        }
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':name', $t_name);
        $query_command->bindParam(':status', $t_status);
        $query_command->bindParam(':ordering', $t_ordering);
        if ($t_thumbs != "") {
            $query_command->bindParam(':thumbs', $t_thumbs);
        }
        $query_command->execute();
        if ($isNew == 1) {
            $t_id = $db->lastInsertID;
        }

        $temdetail = Request::getVar('temdetail', array());
        $ordering = 1;
        $field_default = Request::getVar('field_default', 0);
        foreach ($temdetail['max_record'] as $field_id => $max_record) {
            $html = $temdetail['html'][$field_id];
            $stt = $temdetail['status'][$field_id];
            $df = 0;
            if ($field_default == $field_id)
                $df = 1;
            if ($isNew == 1) {
                $query = "INSERT INTO " . $this->tbl_template_detail .
                        " SET template_id=:template_id, rsm_field_id=:rsm_field_id, content=:content, max_record=:max_record "
                        . ", ordering=:ordering, `default`=:default, status=:status ";
            } else {
                $query = "UPDATE " . $this->tbl_template_detail .
                        " SET content=:content, max_record=:max_record, ordering=:ordering, `default`=:default, status=:status "
                        . " WHERE template_id=:template_id AND rsm_field_id=:rsm_field_id ";
            }
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':template_id', $t_id);
            $query_command->bindParam(':rsm_field_id', $field_id);
            $query_command->bindParam(':content', $html);
            $query_command->bindParam(':max_record', $max_record);
            $query_command->bindParam(':ordering', $ordering);
            $query_command->bindParam(':default', $df);
            $query_command->bindParam(':status', $stt);
            $query_command->execute();

            $ordering++;
        }

        return $t_id;
    }

    // abc
    function removeSubfield() {
        global $mainframe, $db;

        $sf_id = Request::getVar('subfield_id', '');
        $sf_id = intval($sf_id);        
        if ($sf_id != 0) {
            // delete this field
            $query = "DELETE FROM " . $this->tbl_field_sub . " WHERE id =$sf_id ";
            $query_command = $db->createCommand($query);
            $query_command->execute();

            // delete if this field has type is select, ...
            $query = "DELETE FROM " . $this->tbl_field_sub_select . " WHERE filed_sub_id =$sf_id ";
            $query_command = $db->createCommand($query);
            $query_command->execute();
            
            // delete in resume is using this field
            $query = "DELETE FROM " . $this->tbl_field_value . " WHERE field_sub_id =$sf_id ";
            $query_command = $db->createCommand($query);
            $query_command->execute();
        }
        return true;
    }

    //abc
    function templateBuildHtml($cid) {
        $field_data = $this->getFields();
        $template = $this->getTemplateEdit($cid);
        ob_start();
        foreach ($field_data as $field_id => $box) {
            if (isset($template[$field_id]) AND $template[$field_id]['status'] != 1)
                continue;
            $content = isset($template[$field_id]) ? $template[$field_id]['content'] : "";
            $content = preg_replace('/{{open_title}}(.*?){{close_title}}/ism', "$1", $content);
            $content = preg_replace('/{{open_detail_\d+_\d+}}(.*?){{close_detail_\d+_\d+}}/ism', "$1", $content);
            $content = preg_replace('/{{open_block}}(.*?){{close_block}}/ism', "$1", $content);
            ?>
            <div class="box">
                <div class="data-box"><?php echo $content; ?></div>
            </div>

            <?php
        }
        $str_return = ob_get_contents();
        ob_end_clean();

        $str_return = '<div class="scrol" >' . $str_return . '</div>';
        return $str_return;
    }

    //abc
    function deleteTemplate($cid) {
        global $mainframe, $db;
        $query = "SELECT COUNT(*) FROM " . $this->tablename . " WHERE template_id = $cid ";
        $query_command = $db->createCommand($query);
        $result = $query_command->queryScalar();
        if ($result > 0)
            return false;
        $query = "DELETE FROM " . $this->tbl_template_detail . " WHERE template_id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();

        $query = "DELETE FROM " . $this->tbl_template . " WHERE id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        return true;
    }

    //abc
    function deleteField($cid, & $arr_template) {
        global $mainframe, $db;
//        $query = "SELECT template_id FROM " . $this->tbl_template_detail . " WHERE rsm_field_id = $cid ";
//        $query_command = $db->createCommand($query);
//        $result = $query_command->queryColumn();
//        if (count($result) > 0) {
//            $arr_template = $result;
//            return false;
//        }

        $query = "DELETE FROM " . $this->tbl_template_detail . " WHERE rsm_field_id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
         
        $query = "SELECT id FROM " . $this->tbl_field_sub . " WHERE filed_id = $cid ";
        $query_command = $db->createCommand($query);
        $list_sub_field = $query_command->queryColumn();
        if ($list_sub_field) {
            $list_sub_field = implode(',', $list_sub_field);
            $query = "DELETE FROM " . $this->tbl_field_sub_select . " WHERE filed_sub_id in ($list_sub_field)";
            $query_command = $db->createCommand($query);
            $query_command->execute();
            
            // delete in resume is using this field
            $query = "DELETE FROM " . $this->tbl_field_value . " WHERE field_sub_id in ($list_sub_field)";
            $query_command = $db->createCommand($query);
            $query_command->execute();
        }
        
        $query = "DELETE FROM " . $this->tbl_field_user . " WHERE field_id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        

        $query = "DELETE FROM " . $this->tbl_field_sub . " WHERE filed_id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();

        $query = "DELETE FROM " . $this->tbl_field . " WHERE id = $cid ";
        $query_command = $db->createCommand($query);
        $query_command->execute();
        return true;
    }

    function buildHtml($arr_data) {
        ob_start();
//        $reg_title = '/^(<div class="tomtat">\s*<div\s*class="title">)(.*?)(<\/div>\s*<\/div>)/ism';
        $reg_title = '/{{open_title}}(.*?){{close_title}}/ism';
        $reg_value = '/(<div class="details">)(.*?)(<\/div>)$/ism';
        foreach ($arr_data as $field_user_id => $box) {
            if (intval($field_user_id) == 0 OR intval($box['status']) != 1)
                continue;
            $content = $box['html'];
            $title = $box['field_name'];
            $content = preg_replace($reg_title, '$1' . $title . '$3', $content);
            if (preg_match('/{{open_block}}(.*?){{close_block}}/ism', $content, $matches)) {
                $block_content = $matches[1];
                $result_content = "";
                foreach ($box['field_value'] as $field_group_id => $item_field_value) {
                    $new_content = $block_content;
                    foreach ($item_field_value as $field_value_id => $field_value) {
                        $key = $box['field_id'] . '_' . $field_value['field_sub_id'];
                        $reg = '/{{open_detail_' . $key . '}}(.*?){{close_detail_' . $key . '}}/ism';
                        if (intval($field_value['data_type']) == 8) {                            
                            if ($field_value['content'] == "" || $field_value['content'] == "/images/resumes/no_avatar.png") {
                                $reg = '/<img[^>]*{{open_detail_' . $key . '}}(.*?){{close_detail_' . $key . '}}[^>]*>/ism';
                                $new_content = preg_replace($reg, "", $new_content);
                                $field_value['content'] = "/images/resumes/no_avatar.png";
                            }else if(strpos($field_value['content'], 'cropping') === false)
                            {
                                $field_value['content'] = str_replace('/images/resumes', '/cropping/120-160/images/resumes', $field_value['content']);                               
                                if(strpos($field_value['content'], "http://")<0)                                        
                                {
                                    $field_value['content'] = WEB_URL.$field_value['content'];
                                }
                            }
                        }
                        $new_content = preg_replace($reg, $field_value['content'], $new_content);
                    }

                    $result_content .= $new_content;
                }

                $reg = '/{{open_detail_\d+_\d+}}(.*?){{close_detail_\d+_\d+}}/ism';
                $result_content = preg_replace($reg, '', $result_content);

                $content = preg_replace('/{{open_block}}(.*?){{close_block}}/ism', $result_content, $content);
            }
            ?>
            <div class="box" rel="<?php echo $field_user_id; ?>">
                <div class="data-box"><?php echo $content; ?></div>
            </div>            
            <?php
        }
        $str_return = ob_get_contents();
        ob_end_clean();
        $str_return = '<div class="scrol" >' . $str_return . '</div>';
        return $str_return;
    }

}
