<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. modNewsXangHomBox
 */
$this->pageTitle = "Thông tin về xăng dầu";
$this->metaKey = "xăng dầu, xang dau, tin xăng dầu, tin tuc xang dau";
$this->metaDesc = "Cập nhật thông tin về xăng dầu, thị trường xang dau trong và ngoài nước nhanh nhất, chính xác nhất.";
 
?>
<div class="box-std box-trang-chuyenmuc-tintuc">
    <div class="box-title">
        <h2 class="head"><?php echo $this->pageTitle; ?></h2>
    </div>
    <div class="inner">
        <div class="items">
        <?php 
            for($i=0;$i<count($contents); $i++){
                $item = $contents[$i];
                ?>
                    <div class="item">
                        <a href="<?php echo $item['link']; ?>" class="thumb">
                            <img src="<?php echo $item['thumbnail']; ?>" />
                        </a>
                        <h3><a href="<?php echo $item['link']; ?>"><?php echo $item['title'] ?></a></h3>
                        <p class="desc"><?php echo trim(strip_tags($item['introtext'])); ?></p>
                        <p class="time"><?php echo trim(strip_tags($item['created'])); ?></p>
                    </div>
                <?php
            }
        ?>
        </div>
    </div>
</div> 

<?php


$modelNews = News::getInstance();
$arrNews = $modelNews->getTinTuc("*", "1,2,19,3,5,4");
$k=0;
foreach ($arrNews as $dataCart) {
    if($k == 0) echo "<div style='overflow: hidden;'>";
    echo $modelNews->buildHtmlHome($dataCart);
    if($k == 1) echo "</div>";
    $k = 1 - $k;
}

$dataCart = $modelNews->getTinTuc("*", "6",12);
$dataCart = $dataCart[6];
$items = $dataCart['contents'];

$n0 = count($items);
$n1 = ceil($n0/2);
$n2 = $n0 - $n1;
 if($n0 <= 5){ $n1 = $n0; $n2 = 0; }
$items1 = array_splice($items,0,$n1);
$items2 = $items;

$tg = "";
if($dataCart['redirect'] == 1){
    $dataCart['link'] = $dataCart['link_original'];
    $tg = "_blank";
}
        
?>

<div class="mod-modules mod-news box-std">
    <div class="box-title">
        <h3 class="head"><a target="<?php echo $tg; ?>" href="<?php echo $dataCart['link']; ?>"><?php echo $dataCart['title']; ?></a></h3>
    </div>
    <div class="mod-content inner">
        <div class="left width-50"><?php echo fnShowNewsColumn($items1,2, $dataCart['redirect']); ?></div>
        <div class="left width-50"><?php echo fnShowNewsColumn($items2,2, $dataCart['redirect']); ?></div>
         
     </div>
</div>