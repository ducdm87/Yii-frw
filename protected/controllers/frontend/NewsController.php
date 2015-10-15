<?php

class NewsController extends FrontEndController {

    public $item = array();
    public $tablename = "{{gx_info}}";
    public $primary = "id";

    function init() {
//        echo Yii::app()->urlManager->parseUrl(Yii::app()->request)  ;
        $scope = Request::getVar('scope',null);
        if($scope != null){
            $this->layout = "//$scope/tintuc";
        } else $this->layout = "//news/default";

        parent::init();
    }
    
    function render($action, $data = NULL, $return = false) {
         $scope = Request::getVar('scope',null);
          
         if($scope != null)
             $view =  "//$scope/tpl/news/$action";
          else $view =  "/tpl/$action";
 
        parent::render($view, $data, $return);
    }

    public function actionDisplay() {
        $model = News::getInstance();
        $params = array();
        $news_scope = getSysConfig("news.scope",array("*"));
      
        $params["contents"] = $model->getLastNews($news_scope);
       
        $scope = Request::getVar('scope',null);
        $this->render('home', $params);
    }

    function actionCategory() {
        global $mainframe, $db;  
        $params = array();
        $scope = Request::getVar('scope',null);
        $cat_alias = Request::getVar('alias',null);
        $page = Request::getVar('page',1);
        $limit = getSysConfig("news.limit",15);
        $start = ($page - 1) * $limit;
         
        $model = News::getInstance();
        $params['obj_cat'] = $model->getObjCatFromAlias($cat_alias);
         if($params['obj_cat'] == false){
            throw new CHttpException(404,'The specified post cannot be found.');
        }
        
        $params['contents'] = $model->getNewsCategoy($params['obj_cat']['id'], $start, $limit);
         
        $scope = Request::getVar('scope',null);
        
        $params['obj_cat']['metadesc'] = str_replace("'", "", $params['obj_cat']['metadesc']);
        $params['obj_cat']['metadesc'] = str_replace('"', "", $params['obj_cat']['metadesc']);

        $page = Request::getVar('page',1);
        
        if($page>1){
            $params['obj_cat']['title'] = $params['obj_cat']['title'] . " - trang $page";
            $params['obj_cat']['metakey'] .= ", ".$params['obj_cat']['title'];
            $params['obj_cat']['metadesc'] .= ". ".$params['obj_cat']['title'];
        }
        
        $this->pageTitle = $params['obj_cat']['title'];
        $this->metaKey = $params['obj_cat']['metakey'];
        $this->metaDesc = $params['obj_cat']['metadesc'];
        
       $this->render('category', $params);
    }
    
    function actionDetail() {
        global $mainframe, $db;  
        $params = array();
        $scope = Request::getVar('scope',null);
        $cat_alias = Request::getVar('cat_alias',null);
        $cid = Request::getInt('cid',null);
        $alias = Request::getVar('alias',null);
        $showpath = Request::getVar('showpath',1);
        setSysConfig("news.detail.showpath",$showpath);
        
        $model = News::getInstance();
        $params['content'] = $model->getNewsDetail($cid, $alias);
        
        if($params['content'] == false){
            throw new CHttpException(404,'The specified post cannot be found.');
        }
        
        $scope = Request::getVar('scope',null);
       
        $params['content']['metadesc'] = str_replace("'", "", $params['content']['metadesc']);
        $params['content']['metadesc'] = str_replace('"', "", $params['content']['metadesc']);

        $this->pageTitle = $params['content']['title'];
        $this->metaKey = $params['content']['metakey'];
        $this->metaDesc = trim(strip_tags($params['content']['metadesc']));
        
       $this->render('detail', $params);
    }
}
