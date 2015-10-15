<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
 
$content['fulltext'] = preg_replace('/style="[^"]+"/ism', "", $content['fulltext']);

?>
<div class="page-content-detail">
    <?php if(getSysConfig("news.detail.showpath",1) AND $content['showpath'] == 1){ ?>
        <div class="path">
            <div class="path-inner">
                <a href="<?php echo $content['cat_link']; ?>" class="left"><?php echo $content['cat_title']; ?></a>
                <span class="arrowpath bg-sprites"></span>
                <span><?php echo $content['title']; ?></span>
           </div>
        </div>
    <?php } ?>
    <div class="page-header">
        <h2><?php echo $content['title']; ?></h2>
    </div>
    <div class="articleBody">
        <?php echo $content['fulltext']; ?>
    </div>
    <?php
    if(strpos($content['link_original'], "vietbao.vn")){
        echo '<a href="'.$content['link_original'].'" target="_blank">'.$content['link_original'].'</a>';
    }
    ?>
    <div class="box-std box-comment-fb box-underline">
        <div class="box-title">
            <h3 class="head">Bình Luận</h3>
        </div>
        <div class="inner">
            <div class="fb-comments" data-href="http://tv2.vietbao.vn/chuong-trinh/phim-truyen-hinh/aid-18-tro-ve-3.html" data-num-posts="5" data-width="100%"></div>
        </div>
    </div>
</div>