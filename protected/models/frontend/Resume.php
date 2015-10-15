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
    var $link_image_upload = "/images/resumes/";
    var $path_image_upload = 'images/resumes/';
    var $allow_max_filesize = 2;
    var $debug = 0;

    function __construct() {
        $this->default_groupID = DEFAULT_GROUPID;
        $this->link_image_upload = WEB_URL . '/images/resumes/';
        $this->path_image_upload = ROOT_PATH . '/images/resumes/';
        if(isset($_REQUEST['debug'])){
            $this->debug = $_REQUEST['debug'];
        }
    }

    static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new Resume();
        }
        return $instance;
    }

    function getTemplates($limit = 15) {
        global $mainframe, $db;
        $query = "SELECT * FROM " . $this->tbl_template . " WHERE status = 1 ORDER BY ordering LIMIT 0,$limit ";
        $query_command = $db->createCommand($query);
        $items = $query_command->queryAll();
        return$items;
    }

    function getResumes() {
        global $mainframe, $db;
        if (count($this->arr_resumes) > 0)
            return $this->arr_resumes;        
        if($this->debug == 0)
        {
            $user_id = $mainframe->getUserID();
            $client_id =  (ENABLE_SSO == 1 and isset($_COOKIE['apiKey']))?$_COOKIE['apiKey']:session_id();
            $where = " a.user_id =:check_permision ";
            $value = $user_id;
            if ($user_id == 0) {
                $where = " a.client_id =:check_permision ";
                $value = $client_id;
            }
            $value = trim($value);
            $query = "SELECT a.* FROM " . $this->tablename . " a, ".$this->tbl_template." b "
                        . "WHERE a.template_id = b.id AND $where AND a.status = 1 AND b.status = 1 ORDER BY a.cdate DESC";
            
            
            
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':check_permision', $value);
        }else{
            $query = "SELECT * FROM " . $this->tablename . " WHERE status = 1 ORDER BY cdate DESC";
            $query_command = $db->createCommand($query);
        }        
        $this->arr_resumes = $query_command->queryAll();
        return $this->arr_resumes;
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
    function getResumeView($cid)
    {
        global $mainframe, $db;
        $cid = trim($cid);
        $query = "SELECT * FROM " . $this->tablename . " WHERE id=:cid and status = 1 limit 0,1";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':cid', $cid);
        return $query_command->queryRow();
    }

    function getTemplate($template_id = 1) {
        global $mainframe, $db;
        $template_id = intval($template_id);
        if($template_id <=0) return false;

        $query = "SELECT a.id, a.name, a.thumbs,b.rsm_field_id, b.content, b.max_record, b.default, b.status "
                . "FROM " . $this->tbl_template . " a LEFT JOIN " . $this->tbl_template_detail . " b ON a.id = b.template_id " .
                "  WHERE a.status = 1  AND a.id = $template_id and b.status >0 ORDER BY b.ordering";
        $query_command = $db->createCommand($query);

        $items = $query_command->queryAll();
        $arr_new = array();
        foreach ($items as $k => $item) {
            $arr_new[$item['rsm_field_id']] = $item;
        }
        $items = $arr_new;
        return $items;
    }

    function getFields() {
        global $mainframe, $db;
        $query = "SELECT a.id, a.name field_name, a.status,a.ordering a_ordering "
                . ", b.id field_sub_id, b.name field_sub_name,b.isname, b.data_type, b.size, b.required, b.valid_data, b.default_value, b.space_before, b.ordering b_ordering"
                . ", c.id field_sub_select_id, c.name as field_sub_select_name ,c.ordering c_ordering "
                . " FROM  " . $this->tbl_field . " a left join " . $this->tbl_field_sub . " b on a.id = b.filed_id left join " . $this->tbl_field_sub_select . " c on b.id = c.filed_sub_id "
                . " ORDER BY a.ordering ASC,b.ordering ASC,c.ordering ASC";
        $query_command = $db->createCommand($query);

        $items = $query_command->queryAll();

        $arr_new = array();
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            if (!isset($arr_new[$item["id"]]))
                $arr_new[$item["id"]] = array();
            $arr_new[$item["id"]]['name'] = $item["field_name"];
            $arr_new[$item["id"]]['status'] = $item["status"];
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

            if ($item["data_type"] == 4) {
                if (!isset($arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'])) {
                    $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'] = array();
                }
                $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'][$item['field_sub_select_id']] = $item['field_sub_select_name'];
            } else if ($item["data_type"] == 7) {
                if (!isset($arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'])) {
                    $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'] = array();
                }
                $arr_new[$item["id"]]['field_sub'][$item['field_sub_id']]['field_sub_select'][] = $item['field_sub_select_name'];
            }
        }
        return $arr_new;
    }

    function getResumeData($resume_id) {
        global $mainframe, $db;
        $query = "SELECT a.id field_user_id, a.status , a.name field_name, a.field_id, a.field_custome,a.lastmodify , b.id field_value_id, b.field_sub_id, b.content, b.params, b.group_id, b.ordering "
                . " FROM  " . $this->tbl_field_user . " a left join " . $this->tbl_field_value . " b on a.id = b.field_user_id "
                . " WHERE a.resume_id = $resume_id "
                . " order by a.ordering, b.ordering, b.id ASC";
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
            $arr_new[$k1]["field_value"][$k2][$k3]['params'] = json_decode($item["params"]);
            $arr_new[$k1]["field_value"][$k2][$k3]['ordering'] = $item["ordering"];
        }
        $items = $arr_new;

        return $items;
    }

    function getDataEdit($resume_id, $template_id) {
        // lay data tu rsm_template va rsm_template_html
        if(!$template = $this->getTemplate($template_id))  return false;
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

                    $fs = $field['field_sub'][$it['field_sub_id']];

                    $item['field_value'][$k][$k2]['name'] = $fs['name'];
                    $item['field_value'][$k][$k2]['data_type'] = $fs['data_type'];
                    $item['field_value'][$k][$k2]['size'] = $fs['size'];
                    $item['field_value'][$k][$k2]['required'] = $fs['required'];
                    $item['field_value'][$k][$k2]['valid_data'] = $fs['valid_data'];
                    $item['field_value'][$k][$k2]['default_value'] = $fs['default_value'];
                    $item['field_value'][$k][$k2]['space_before'] = $fs['space_before'];
                    $item['field_value'][$k][$k2]['isname'] = $fs['isname'];
//                    $item['field_value'][$k]['order'][] = $k2;
                    if ($fs['data_type'] == 4 OR $fs['data_type'] == 7) {
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

    function changeTemplate($resume_id, $to_tem_id) {
        global $mainframe, $db;
        $query = "UPDATE " . $this->tablename . " SET template_id =:template_id WHERE id =:cid ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':template_id', $to_tem_id);
        $query_command->bindParam(':cid', $resume_id);
        $query_command->execute();
        return true;
    }

    function sendemail($resume_id, $resume, $emailto, $emailtitle, $emailbody) {
        
    }

    function addField_user($field_id, $resume_id, $title, $ordering, $status, $field_custome = 0) {
        global $mainframe, $db;
        $query = "INSERT INTO " . $this->tbl_field_user
                . " SET name=:name, ordering=:ordering,  field_id=:field_id, resume_id=:resume_id, status=:status, lastmodify = 0, field_custome = $field_custome";
        $title = trim($title);
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':name', $title);
        $query_command->bindParam(':ordering', $ordering);
        $query_command->bindParam(':field_id', $field_id);
        $query_command->bindParam(':resume_id', $resume_id);
        $query_command->bindParam(':status', $status);

        $return = $query_command->execute();
        $cid = $db->lastInsertID;
        return $cid;
    }

    function addField_value($resume_id, $field_user_id, $field_sub_id, $content, $group_ID, $ordering = 1) {
        global $mainframe, $db;
        $query = "INSERT INTO " . $this->tbl_field_value
                . " SET   resume_id=:resume_id, field_user_id=:field_user_id,field_sub_id=:field_sub_id,content=:content, group_id=:group_ID, ordering = $ordering ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':resume_id', $resume_id);
        $query_command->bindParam(':field_user_id', $field_user_id);
        $query_command->bindParam(':field_sub_id', $field_sub_id);
        $query_command->bindParam(':content', $content);
        $query_command->bindParam(':group_ID', $group_ID);
        $return = $query_command->execute();
        $cid = $db->lastInsertID;
        return $cid;
    }

    function saveBox($resume_id, $resume, $field_id, $data) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        $group_id = 0;
        $return_data = array();
        $field_user_id = Request::getVar('field_user_id', 0);
        $ord = 1;

        foreach ($data as $k1 => $item) {
            $field_value_id = intval($item['field_value_id']);
            if ($item['data_type'] == 8) {
                if (isset($item['file_name']) and $item['file_name'] != "") {
                    if (strpos($item['content'], 'data:image') >= 0) {
                        $item['content'] = preg_replace('/data:image\/\w+\;base64\,/ism', '', $item['content']);
                        file_put_contents($this->path_image_upload . $item['file_name'], base64_decode($item['content']));
                        $item['content'] = $this->link_image_upload . $item['file_name'];
                    }
                }
            }
            if ($field_value_id > 0) {
                $query = "UPDATE " . $this->tbl_field_value . " SET content =:content, ordering=:ordering WHERE id =:field_value_id ";
                $content = $item['content'];
                $content = preg_replace('/font-family\:[^;]+\s*;/ism', " ", $content);              
                $content = preg_replace('/font-size\:[^;]+\s*;/ism', " ", $content);
                $ordering = $item['ordering'];
                $query_command = $db->createCommand($query);
                $query_command->bindParam(':content', $content);
                $query_command->bindParam(':ordering', $ord);
                $query_command->bindParam(':field_value_id', $field_value_id);
                $query_command->execute();
            } else {
                $cid = $this->addField_value($resume_id, $field_user_id, $item['field_sub_id'], $item['content'], $group_id, $ord);
                if ($group_id == 0) {
                    $group_id = $cid;
                    $return_data['cid'] = array();
                }
                $return_data['cid'][$k1] = $cid;
            }
            $ord++;
            $return_data['group_id'] = $group_id;
        }

        $this->return_data = $return_data;

        $query = "UPDATE " . $this->tbl_field_user . " SET lastmodify = UNIX_TIMESTAMP() WHERE id = " . $field_user_id;
        $query_command = $db->createCommand($query);
        $query_command->execute();

        $query = "SELECT * FROM " . $this->tbl_field_user . " WHERE resume_id = " . $resume['id'];
        $query_command = $db->createCommand($query);        
        $field_user = $query_command->queryAll();
        $total2 = count($field_user);
        $step = 0;
        $total1 = 0;
        for($i=0;$i<$total2;$i++)
        {
            if($field_user[$i]['lastmodify'] != 0) $step ++;
            if($field_user[$i]['status'] == 1) $total1 ++;
        }
        $step = "$step/$total1/$total2";
        $query = "UPDATE " . $this->tablename . " SET mdate = now(), step = '$step' WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function addBox($resume_id, $resume, $field_id, $title_section) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        if ($field_id == "custom") {
            $template = $this->getTemplate($resume['template_id']);
            $box = 0;
            foreach ($template as $k => $item) {
                if ($item['default'] == 1) {
                    $box = $item;
                    break;
                }
            }
//            $reg_title = '/^(<div class="tomtat">\s*<div\s*class="title">)(.*?)(<\/div>\s*<\/div>)/ism';
            $reg_title = '/{{open_title}}(.*?){{close_title}}/ism';

            $arr_return = array();
            $arr_return['field_id'] = $box['rsm_field_id'];
            $arr_return['field_name'] = $title_section;
            $length = Request::getVar('length', "");
            $ordering = intval($length) + 1;
            $field_user_id = $this->addField_user($box['rsm_field_id'], $resume_id, $title_section, $ordering, 1, 1);
            $arr_return['field_user_id'] = $field_user_id;
            $arr_return['field_custome'] = 1;
            $arr_return['field_value'] = array();
            $arr_return['html'] = $box['content'];
            $arr_return['html'] = str_replace("\r\n", " ", $arr_return['html']);
            $arr_return['html'] = preg_replace($reg_title, $title_section, $arr_return['html']);
            $arr_return['max_record'] = $box['max_record'];
            $arr_return['status'] = 1;
            $arr_return['length_field_value'] = 1;
            $field_data = $this->getFields();
            $arr_field_sub = $field_data[$box['rsm_field_id']]['field_sub'];
            $group_ID = 0;
            $ordering = 1;
            foreach ($arr_field_sub as $fsk => $field_sub) {
                $reg = '/{{open_detail_' . $box['rsm_field_id'] . '_' . $fsk . '}}(.*?){{close_detail_' . $box['rsm_field_id'] . '_' . $fsk . '}}/ism';
                $value = "";
                if (preg_match($reg, $box['content'], $matches))
                    $value = trim($matches[1]);
                $_group_ID = $this->addField_value($resume_id, $field_user_id, $fsk, $value, $group_ID);
                if ($group_ID == 0) {
                    $group_ID = $_group_ID;
                    $arr_return['field_value'][$group_ID] = array();
                    $arr_return['field_value'][$group_ID][$_group_ID]['group_id'] = 0;
                } else {
                    $arr_return['field_value'][$group_ID][$_group_ID]['group_id'] = $group_ID;
                }
                $arr_return['field_value'][$group_ID][$_group_ID] = array();
                $arr_return['field_value'][$group_ID][$_group_ID]['content'] = $value;
                $arr_return['field_value'][$group_ID][$_group_ID]['data_type'] = $field_sub['data_type'];
                $arr_return['field_value'][$group_ID][$_group_ID]['field_sub_id'] = $fsk;
                $arr_return['field_value'][$group_ID][$_group_ID]['field_value_id'] = $_group_ID;
                $arr_return['field_value'][$group_ID][$_group_ID]['isname'] = $field_sub['isname'];
                $arr_return['field_value'][$group_ID][$_group_ID]['name'] = $title_section;
                $arr_return['field_value'][$group_ID][$_group_ID]['ordering'] = $ordering;
                $arr_return['field_value'][$group_ID][$_group_ID]['size'] = $field_sub['size'];
                $ordering++;
            }
            $arr_return['list_field_value_id'] = array_keys($arr_return['field_value']);
            return $arr_return;
        } else {
            $query = "UPDATE " . $this->tbl_field_user . " SET status = 1 WHERE id =:field_id ";
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':field_id', $field_id);
            $query_command->execute();

            $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
            $query_command = $db->createCommand($query);
            $query_command->execute();
        }
        return true;
    }

    function renameBox($resume_id, $resume, $field_id, $title_section) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        $query = "UPDATE " . $this->tbl_field_user . " SET name=:name WHERE id =:field_id ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':name', $title_section);
        $query_command->bindParam(':field_id', $field_id);
        $query_command->execute();

        $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function removeBox($resume_id, $resume, $field_id) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        $query = "DELETE  FROM " . $this->tbl_field_user . " WHERE id =:field_id AND field_custome = 1 ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':field_id', $field_id);
        $query_command->execute();

        $query = "UPDATE " . $this->tbl_field_user . " SET status = 2 WHERE id =:field_id ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':field_id', $field_id);
        $query_command->execute();

        $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function orderBox($resume_id, $resume, $list_field_id) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        foreach ($list_field_id as $stt => $field_user_id) {
            $query = "UPDATE " . $this->tbl_field_user . " SET ordering =:ordering WHERE id =:field_id ";
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':ordering', $stt);
            $query_command->bindParam(':field_id', $field_user_id);
            $query_command->execute();
        }

        $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function orderBoxSub($resume_id, $resume, $list_field_value_id) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;
        foreach ($list_field_value_id as $stt => $field_value_id) {
            $query = "UPDATE " . $this->tbl_field_value . " SET ordering =:ordering WHERE id =:field_value_id ";
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':ordering', $stt);
            $query_command->bindParam(':field_value_id', $field_value_id);
            $query_command->execute();
        }

        $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function croppingImage($resume_id, $resume, $field_user_value, $img_cropping) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;
        $query = "SELECT content FROM " . $this->tbl_field_value . " WHERE id =:field_user_value AND resume_id =:resume_id";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':field_user_value', $field_user_value);
        $query_command->bindParam(':resume_id', $resume_id);
        $link_img = $query_command->queryScalar();
        $file_name = basename($link_img);

        $old_file_name = preg_replace('/cropping-\d+-image-resume-/ism', "", $file_name);

        if ($file_name != $old_file_name and file_exists($this->path_image_upload . $file_name)) {
            unlink($this->path_image_upload . $file_name);
        }

        if ($old_file_name == "")
            $old_file_name = "no_avatar.png";

        $path_image = $this->path_image_upload . $old_file_name;


        if (!file_exists($path_image)) {
            $this->str_error = "Your file is not existing";
            return false;
        }

        $info = getimagesize($path_image);
        if ($info == null) {
            header('HTTP/1.1 500 Internal Server Error');
            exit('Invalid image type');
        }
        $type = $info[2];
        if ($type == IMAGETYPE_JPEG)
            $src_img = imagecreatefromjpeg($path_image);
        else if ($type == IMAGETYPE_PNG)
            $src_img = imagecreatefrompng($path_image);
        else if ($type == IMAGETYPE_GIF)
            $src_img = imagecreatefromgif($path_image);
        else {
            header('HTTP/1.1 500 Internal Server Error');
            exit('Invalid image type');
        }

        $dst_img = $this->make_thumb($src_img, 840, 440);
        $src_img = $dst_img;
        $thumb_w = $img_cropping['w'];
        $thumb_h = $img_cropping['h'];
        $dst_img = ImageCreateTrueColor(120, 160);
        imagecopyresampled($dst_img, $src_img, 0, 0, $img_cropping['x'], $img_cropping['y'], 120, 160, $thumb_w, $thumb_h);

        $file_name = 'cropping-' . time() . '-image-resume-' . $old_file_name;
        $link_img = $this->link_image_upload . $file_name;

        $path_image = $this->path_image_upload . $file_name;
        
        imagejpeg($dst_img, $path_image);
        if ($type == IMAGETYPE_PNG)
            imagepng($dst_img, $path_image);
        else
            imagejpeg($dst_img, $path_image);

        $img_cropping = json_encode($img_cropping);
        $query = "UPDATE " . $this->tbl_field_value . " SET content=:content, params=:params WHERE id =:field_user_value ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':content', $link_img);
        $query_command->bindParam(':field_user_value', $field_user_value);
        $query_command->bindParam(':params', $img_cropping);
        $query_command->execute();

        return $link_img;
    }

    function uploadImage($resume_id, $resume, $field_user_value, $image_name, $data_image) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        if ($image_name != "") {
            if (strpos($data_image, 'data:image') >= 0) {
                $data_image = preg_replace('/data:image\/\w+\;base64\,/ism', '', $data_image);
                file_put_contents($this->path_image_upload . $image_name, base64_decode($data_image));

                if (filesize($this->path_image_upload . $image_name) > $this->allow_max_filesize * 1024 * 1024) {
                    $this->str_error = "Your photos couldn't be uploaded. Photos should be less than " . $this->allow_max_filesize . " MB and saved as JPG, PNG, GIF or TIFF files.";
                    return false;
                }

                $link_image = $this->link_image_upload . $image_name;
                $query = "UPDATE " . $this->tbl_field_value . " SET content=:content WHERE id =:field_user_value ";
                $query_command = $db->createCommand($query);
                $query_command->bindParam(':content', $link_image);
                $query_command->bindParam(':field_user_value', $field_user_value);
                $query_command->execute();

                return $link_image;
            }
        }
        return false;
        die;
    }

    function changePublic($resume_id, $resume) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        $query = "UPDATE " . $this->tablename . " SET public = 1 - public WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();
        return true;
    }

    function removeResume($resume_id, $resume) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

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

    function removeField($cid, $resume, $field_group) {
        global $mainframe, $db;
        if (!$this->checkAuthorize($resume))
            return false;

        $query = "DELETE FROM " . $this->tbl_field_value . " WHERE resume_id =:cid and (id=:id OR group_id=:id) ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':cid', $cid);
        $query_command->bindParam(':id', $field_group);

        $query_command->execute();

        $query = "UPDATE " . $this->tablename . " SET mdate = now() WHERE id = " . $resume['id'];
        $query_command = $db->createCommand($query);
        $query_command->execute();

        return true;
    }

    function duplicateResume($new_cid, $old_cid) {
        global $mainframe, $db;

        $query = "SELECT * FROM " . $this->tbl_field_user . " WHERE resume_id =$old_cid ORDER BY ordering ASC";
        $query_command = $db->createCommand($query);
        $list_field_old = $query_command->queryAll();

        $query = "SELECT * FROM " . $this->tbl_field_value . " WHERE resume_id =$old_cid  ORDER BY ordering ASC";
        $query_command = $db->createCommand($query);
        $list_field_value_old = $query_command->queryAll();

        $arr_new = array();
        foreach ($list_field_value_old as $k => $field_value) {
            if (!isset($arr_new[$field_value['field_user_id']]))
                $arr_new[$field_value['field_user_id']] = array();
            $key = $field_value['group_id'];
            if ($field_value['group_id'] == 0)
                $key = $field_value['id'];
            if (!isset($arr_new[$field_value['field_user_id']][$key]))
                $arr_new[$field_value['field_user_id']][$key] = array();
            $arr_new[$field_value['field_user_id']][$key][] = $field_value;
        }
        $list_field_value_old = $arr_new;

        $arr_new = array();
        foreach ($list_field_old as $key => $field) {
            $arr_user_value = $list_field_value_old[$field['id']];

            $query = "INSERT INTO " . $this->tbl_field_user
                    . " SET name=:name, user_id=:user_id, ordering=:ordering, field_id=:field_id"
                    . " , resume_id=:resume_id, status=:status, lastmodify =:lastmodify ";
            $query_command = $db->createCommand($query);
            $query_command->bindParam(':name', $field['name']);
            $query_command->bindParam(':user_id', $field['user_id']);
            $query_command->bindParam(':ordering', $field['ordering']);
            $query_command->bindParam(':field_id', $field['field_id']);
            $query_command->bindParam(':resume_id', $new_cid);
            $query_command->bindParam(':status', $field['status']);
            $query_command->bindParam(':lastmodify', $field['lastmodify']);
            $query_command->execute();
            $field_user_id = $db->lastInsertID;
            foreach ($arr_user_value as $key2 => $user_values) {
                $group_id = 0;
                foreach ($user_values as $key3 => $user_value) {
                    $query = "INSERT INTO " . $this->tbl_field_value
                            . " SET resume_id=:resume_id, field_user_id=:field_user_id, field_sub_id=:field_sub_id "
                            . " , group_id=:group_id, content=:content, ordering=:ordering";
                    $query_command = $db->createCommand($query);
                    $query_command->bindParam(':resume_id', $new_cid);
                    $query_command->bindParam(':field_user_id', $field_user_id);
                    $query_command->bindParam(':field_sub_id', $user_value['field_sub_id']);
                    $query_command->bindParam(':group_id', $group_id);
                    $query_command->bindParam(':content', $user_value['content']);
                    $query_command->bindParam(':ordering', $user_value['ordering']);
                    $query_command->execute();
                    $field_user_value_id = $db->lastInsertID;
                    
                    if ($key3 == 0) {
                        $group_id = $field_user_value_id;
                    }
                }
            }
        }
        return true;
    }

    function checkAuthorize($resume) {
        global $mainframe, $db;
        if($this->debug == 1){
            return true;
        }
        $user_id = $mainframe->getUserID();
        
        $client_id =  (ENABLE_SSO == 1 and isset($_COOKIE['apiKey']))?$_COOKIE['apiKey']:session_id();
        if ($resume == null)
            return false;
        if (($user_id == $resume['user_id'] and $user_id !=0 )  OR $client_id == $resume['client_id']) {
            return true;
        } else {
            $this->str_error = "Resume is not existing or You do not have permission";
            return false;
        }
    }

    function buildHtml($arr_data, $hidden_box = 1) {
//        $reg_title = '/^(<div class="tomtat">\s*<div\s*class="title">)(.*?)(<\/div>\s*<\/div>)/ism';
        $reg_title = '/{{open_title}}(.*?){{close_title}}/ism';
        $reg_value = '/(<div class="details">)(.*?)(<\/div>)$/ism';
        $str_return = "";
        if(count($arr_data) == 0 OR !is_array($arr_data)) return '';

        foreach ($arr_data as $field_user_id => $box) {
            if (intval($field_user_id) == 0 OR intval($box['status']) != 1)
                continue;

            if ($box['lastmodify'] <= 0 and $hidden_box == 1)
                continue;

            $content = $box['html'];
            $title = $box['field_name'];
            $content = preg_replace($reg_title, $title, $content);
            if (preg_match('/{{open_block}}(.*?){{close_block}}/ism', $content, $matches)) {
                $block_content = $matches[1];
                $result_content = "";
                foreach ($box['field_value'] as $field_group_id => $item_field_value) {
                    $new_content = $block_content;
                    foreach ($item_field_value as $field_value_id => $field_value) {
                        $key = $box['field_id'] . '_' . $field_value['field_sub_id'];
                        $reg = '/{{open_detail_' . $key . '}}(.*?){{close_detail_' . $key . '}}/ism';
                        
                        $field_value['content'] = preg_replace('/font-family\:[^;]+\s*;/ism', " ", $field_value['content']);
                        $field_value['content'] = preg_replace('/font-size\:[^;]+\s*;/ism', " ", $field_value['content']);
                        
                        if (intval($field_value['data_type']) == 8) {
                            if ($field_value['content'] == "" || $field_value['content'] == "/images/resumes/no_avatar.png") {
                                $reg = '/<img[^>]*{{open_detail_' . $key . '}}(.*?){{close_detail_' . $key . '}}[^>]*>/ism';
                                $new_content = preg_replace($reg, "", $new_content);
                                $field_value['content'] = "/images/resumes/no_avatar.png";
                            } else if (strpos($field_value['content'], 'cropping') === false) {
                                $field_value['content'] = str_replace('/images/resumes', '/cropping/120-160/images/resumes', $field_value['content']);
                                if (strpos($field_value['content'], "http://") < 0) {
                                    $field_value['content'] = WEB_URL . $field_value['content'];
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
            $str_return .= $content . "\r\n";
        }
        $str_return = ' <div class="scrol" >' . $str_return . '</div>';
//        $str_return = str_replace('font-family: Verdana;', ' ', $str_return);

        return $str_return;
    }

    function getResumeClient() {
        global $mainframe, $db;
        $client_id =  (ENABLE_SSO == 1 and isset($_COOKIE['apiKey']))?$_COOKIE['apiKey']:session_id();
        $query = "SELECT * FROM " . $this->tablename . " WHERE client_id =:check_permision AND user_id = 0 ";
        $query_command = $db->createCommand($query);
        $query_command->bindParam(':check_permision', $client_id);
        $resume = $query_command->queryRow();
        return $resume;
    }

    function make_thumb($src_img, $new_w, $new_h) {

        $old_y = imageSY($src_img);
        $old_x = imageSX($src_img);
        if ($old_y < $new_h || $new_h == 0) {
            $new_h = $old_y;
        }

        if ($old_x < $new_w || $new_w == 0) {
            $new_w = $old_x;
        }

        $ratio1 = $old_x / $new_w;
        $ratio2 = $old_y / $new_h;
        if ($ratio1 > $ratio2) {
            $thumb_w = $new_w;
            $thumb_h = $old_y / $ratio1;
        } else {
            $thumb_h = $new_h;
            $thumb_w = $old_x / $ratio2;
        }
        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
        return $dst_img;
    }

}
