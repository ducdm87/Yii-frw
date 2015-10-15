<?php

class News {

    var $tablename = "{{news_content}}";
    var $tbl_category = "{{categories}}";
    var $tbl_contentauto = "{{news_content_auto}}";
    var $str_error = "";
    var $str_return = "";
    var $return_data = "";
    var $arr_resumes = array();

    function __construct() { 
    }

    static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new News();
        }
        return $instance;
    }
    
    function getLastNews($scope = "article", $limit = 6)
    {
        global $mainframe, $db;
        
        $where = " ";
        if($scope != "*"){
            $where .= " AND B.`scope` = '$scope' AND B.`alias` != 'uncategorised' ";
        }
		$list_idnews = getListObjectID("news");
		if($list_idnews != false and $list_idnews != ""){
			$where .= " id in($list_idnews) ";
		}
		
        $query = "SELECT A.*, B.alias cat_alias, B.title cat_title "
                    ."FROM " . $this->tablename 
                             . " A LEFT JOIN ". $this->tbl_category . " B ON A.catid = B.id "
                    ." WHERE A.status = 1 AND B.status = 1 "
                        . $where
                   ." ORDER BY A.created DESC, A.ordering DESC LIMIT $limit";
        $query_command = $db->createCommand($query);
        $items = $query_command->queryAll();
        
        if(count($items))
            foreach($items as &$item){
                $item['link'] = fnCreateUrlNewsDetail($item['id'],$item['alias'],$item['catid'], $item['cat_alias'] );
                addObjectID($item['id'], "news");
            }
        return $items;
    }
    
    /*
     * $listid: danh sach id chuyen muc
	*/
    function getTinTuc($scope = "article", $listid = "", $limit = 6){
        global $mainframe, $db;
        $where = " ";
        if($scope != "*"){
            $where .= " AND `scope` = '$scope' AND `alias` != 'uncategorised' ";
        }
        
        if($listid != ""){ $where .= " AND id in($listid) "; }
        
        $query = "SELECT * FROM " . $this->tbl_category 
                    ." WHERE status = 1 $where "
                   ." ORDER BY ordering ASC";
        $query_command = $db->createCommand($query);
        $items = $query_command->queryAll();
        
        $arr_new = array();
         for($i=0;$i<count($items);$i++){
             $item = $items[$i];
             $item['link'] = Yii::app()->createUrl("news/category",array("alias"=>$item['alias']));
             $item['contents'] = $this->getNewsCategoy($item['id'],0, $limit);
             $arr_new[$item['id']] = $item;
         }
         $items = $arr_new;
         
         if($listid != ""){
             $listid = explode(",", $listid);
             $arr_new = array();
             foreach ($listid as $k=>$id){
                 if(isset($items[$id]))
                    $arr_new[$id] = $items[$id];
             }
             $items = $arr_new;
         }
         
        return $items;
        
        return $str_out;
    }
    
    function getNewsCategoy($catID, $start = 0, $limit = 10)
    {
        global $mainframe, $db;
        $list_idnews = getListObjectID("news");

        $where = array();
        $where[] = "A.status = 1";
        $where[] = "B.status = 1";
        $where[] = "A.catid = $catID ";
        if($list_idnews != false and $list_idnews != ""){
        	$where[] = "A.id not in($list_idnews)";
        }
        $where = implode(" AND ",$where);
        $query = "SELECT A.*, B.alias cat_alias, B.title cat_title "
                    ."FROM " . $this->tablename 
                             . " A LEFT JOIN ". $this->tbl_category . " B ON A.catid = B.id "
                    ." WHERE  $where "
                   ." ORDER BY A.created DESC, A.ordering DESC LIMIT $start, $limit";
        $query_command = $db->createCommand($query);
        $items = $query_command->queryAll();
        
        if(count($items))
            foreach($items as &$item){
                $item['link'] = fnCreateUrlNewsDetail($item['id'],$item['alias'],$item['catid'], $item['cat_alias'] );
                addObjectID($item['id'], "news");
            }
        
        return $items;
    } 
    
    function getObjCatFromAlias($alias)
    {
        global $mainframe, $db;
        $query = "SELECT * FROM " . $this->tbl_category
                        ." WHERE status = 1 "
                        ." AND alias = '$alias' LIMIT 1";
        $query_command = $db->createCommand($query);
        $item = $query_command->queryRow();
        $item['link'] = Yii::app()->createUrl("news/category",array("alias"=>$item['alias']));
        
        
        $query = "SELECT count(*) FROM " . $this->tablename 
                    ." WHERE status = 1 AND catid = ". $item['id'];
        $query_command = $db->createCommand($query);
        $item['total'] = $query_command->queryScalar();
        
        return $item;
    }
    
    function getNewsDetail($cid, $alias){
        global $mainframe, $db;
        if(intval($cid) != 0){
            $query = "SELECT A.*, B.alias cat_alias, B.title cat_title, B.showpath, C.link_original "
                        ."FROM " . $this->tablename ." A "
                                 . " LEFT JOIN ". $this->tbl_category . " B ON A.catid = B.id "
                                 . " LEFT JOIN ". $this->tbl_contentauto . " C ON A.id = C.aid "
                        ." WHERE A.status = 1 AND B.status = 1 AND A.id = $cid ";
        }else if($alias != ""){
            $query = "SELECT A.*, B.alias cat_alias, B.title cat_title, B.showpath, C.link_original "
                        ."FROM " . $this->tablename ." A "
                                 . " LEFT JOIN ". $this->tbl_category . " B ON A.catid = B.id "
                                 . " LEFT JOIN ". $this->tbl_contentauto . " C ON A.id = C.aid "
                        ." WHERE A.status = 1 AND B.status = 1 AND A.alias = '$alias' ";
        }
 
        $query_command = $db->createCommand($query);
        
        $item = $query_command->queryRow();
        if($item == FALSE) return false;
        $item['slug'] = $item['id']."-".$item['alias'];
         $item['cat_link'] = Yii::app()->createUrl("news/category",array("alias"=>$item['cat_alias']));
        $item['link'] = Yii::app()->createUrl("news/detail",array("cid"=>$item['id'],"alias"=>$item['alias'], "cat"=>$item['cat_alias']));
        addObjectID($item['id'], "news");
        return $item;
    }
    
    /*
     * 
     */
    function buildHtmlHome($dataCart)
    {
         $items = $dataCart['contents'];
        if(count($items) == 0) return;
        $firstItem = $items[0];
        $items = array_slice($items, 1);

        $tg = "";
        if($dataCart['redirect'] == 1){
            $dataCart['link'] = $dataCart['link_original'];
            $firstItem['link'] = $firstItem['link_original'];
            $tg = "_blank";
        }
        ob_start();
            ?>
                <div class="mod-modules mod-news left width-48 box-std">
                    <div class="box-title">
                        <h3 class="head"><a href="<?php echo $dataCart['link']; ?>"><?php echo $dataCart['title']; ?></a></h3>
                    </div>
                    <div class="mod-content inner">
                       <div class="featured">
                           <div class="imgs">
                               <a href="<?php echo $firstItem['link']; ?>" class="title-news">
                                   <img width="94" height="94" src="<?php echo $firstItem['thumbnail']; ?>" alt="<?php echo htmlspecialchars($firstItem['title']); ?>">
                               </a>
                           </div>
                           <div class="infor">
                               <a href="<?php echo $firstItem['link']; ?>" class="title-news"><?php echo ($firstItem['title']); ?></a>
                               <div class="date"></div>
                               <p><?php echo htmlspecialchars($firstItem['introtext']); ?></p>
                           </div>
                       </div>
                       <ul class="read-more">
                           <?php for($i=1; $i<count($items);$i++){
                               $item = $items[$i];
                                if($dataCart['redirect'] == 1){ 
                                  $item['link'] = $item['link_original']; 
                                  $tg = "_blank";
                                }
                               ?>
                           <li><a href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a></li>
                           <?php } ?>
                       </ul>
                     </div>
               </div>
            <?php
        $str_out = ob_get_contents();
        ob_end_clean();
        return $str_out;
    }
    
}
