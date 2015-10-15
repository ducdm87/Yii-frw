<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$tg = "";
if($obj_cat['redirect'] == 1){
    $obj_cat['link'] = $obj_cat['link_original'];
    $tg = "_blank";
}

?>
<div class="box-std box-trang-chuyenmuc-tintuc">
    <div class="box-title">
        <h2 class="head"><a href="<?php echo $obj_cat['link']; ?>" title="<?php echo $obj_cat['title']; ?>"  target="<?php echo $tg; ?>"><?php echo $obj_cat['title']; ?></a></h2>
    </div>
    <div class="inner">
        <div class="items">
        <?php 
            for($i=0;$i<count($contents); $i++){
                $item = $contents[$i];
                $tg = "";
                if($obj_cat['redirect'] == 1){
                    $item['link'] = $item['link_original'];
                    $tg = "_blank";
                }
                ?>
                    <div class="item">
                        <a href="<?php echo $item['link']; ?>" class="thumb" target="<?php echo $tg; ?>">
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
        <?php 
            $page = Request::getVar('page',1);
            $limit = getSysConfig("news.limit",15);
            echo fnShowPagenation($obj_cat['link'], $obj_cat['total'], $limit, $page);  
        ?>
    </div>
</div>