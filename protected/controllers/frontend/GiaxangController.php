<?php

class GiaxangController extends FrontEndController {

    public $item = array();
    public $tablename = "{{gx_info}}";
    public $primary = "id";
    public $scopenews = "tin-kinh-te";

    function init() {
        $this->layout = "//benhvienphusan/default";
        parent::init();
    }

    public function actionDisplay() {
        global $classSuffix;
        $classSuffix = "homepage";
        $this->pageTitle = "Giá xăng dầu hôm nay, gia xang dau";
        $this->metaKey = "giá xăng hôm nay, gia xang, gia xang dau, giá xăng, giá xăng hiện tại, giá xăng dầu hôm nay, gia xang hom nay, giá xăng dầu, giá xăng a92";
        $this->metaDesc = "Giá xăng hôm nay, cập nhật nhanh nhất bảng gia xang dau mới nhất, chính xác nhất, giá xăng hiện tại, giá xăng a92";
        $params = array();
        $model = Benhvienphusan::getInstance();
        $modelNews = News::getInstance();
        
        $params['giabanle'] = $model->getGiaBanLe();
        $params['giathegioi'] = $model->getGiaTheGioi();
//        $arrNews = $modelNews->getTinTuc($this->scopenews);
        $arrNews = $modelNews->getTinTuc("*", "1,8,19,3");
        $str_tintuc = "";
        $k=0;
        foreach ($arrNews as $dataCart) {
            if($k == 0) $str_tintuc .= "<div style='overflow: hidden;'>";
            $str_tintuc .= $modelNews->buildHtmlHome($dataCart);
            if($k == 1) $str_tintuc .= "</div>";
            $k = 1 - $k;
        }
        $params['tintuc'] = $str_tintuc;
        $this->render('tpl/homepage', $params);
    }

    function actionChart() {
        global $mainframe, $db;
        $model = Benhvienphusan::getInstance();
        $params = array();
        $params['bieudogia'] = $model->getBieuDo();
        setSysConfig("showChartColumn", 0);
        
        $this->pageTitle = "Biểu đồ giá xăng dầu";
        $this->metaKey = "biểu đồ giá xăng dầu,biểu đồ giá xăng RON 92, giá xăng RON 92, giá dầu ngọt nhẹ, sàn Nymex";
        $this->metaDesc = "Biểu đồ giá xăng dầu thể hiện sự biến động về giá xăng dầu tăng giảm theo thời gian";
       
        
        $this->render('tpl/chart', $params);
    }
    
    function actionMaps() {
        global $mainframe, $db;  
        $model = Benhvienphusan::getInstance();
        $modelNews = News::getInstance();
        $params = array();
        $params['maps'] = $model->getBanDo();
        
        $arrNews = $modelNews->getTinTuc("*", "1,8,19,3");
        $str_tintuc = "";
        $k=0;
        foreach ($arrNews as $dataCart) {
            if($k == 0) $str_tintuc .= "<div style='overflow: hidden;'>";
            $str_tintuc .= $modelNews->buildHtmlHome($dataCart);
            if($k == 1) $str_tintuc .= "</div>";
            $k = 1 - $k;
        }
        $params['tintuc'] = $str_tintuc;
        
        setSysConfig("colright.display",false); 
        setSysConfig("page.classSuffix","page-full-width");
        setSysConfig("showMapsColumn", 0);
        
        $this->pageTitle = "Bản đồ cây xăng";
        $this->metaKey = "bản đồ cây xăng";
        $this->metaDesc = "Bản đồ cây xăng trên mọi tỉnh thành trong cả nước, cây xăng hà nội, cây xăng tphcm…";
       
        
        $this->render('tpl/maps', $params);
    } 

    function postUrl($SourceURL_c, & $content = "", $fields  = array()) {        
        $curl_options_html = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => true, // don't return headers
            //       CURLOPT_NOBODY         => true,
            //       CURLOPT_CUSTOMREQUEST  => 'HEAD',
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );
        $ch = curl_init($SourceURL_c);
        curl_setopt_array($ch, $curl_options_html);
        if(count($fields))
        {
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
            rtrim($fields_string, '&');
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }

        $content = curl_exec($ch);
        $header_items = curl_getinfo($ch);
        curl_close($ch);        
        return $header_items;
    }

}
