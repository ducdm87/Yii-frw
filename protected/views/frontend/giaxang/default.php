<?php require_once 'function.php'; ?>
<?php require_once 'block/head.php'; ?>
            <!-- begin main page -->
            <div id="page-content">
                <div class="col-main">
                    <div class="main-inner">
                         <?php echo $content; ?>
                    </div>
                </div>
                <?php
                if(getSysConfig('colright.display',true) == true)
                    echo $this->renderPartial('/benhvienphusan/block/colright'); 
                ?>
            </div>
            <!-- end main page -->
            <!-- Begin footer -->
            <?php echo $this->renderPartial('/benhvienphusan/block/footer'); ?>
            <!-- End footer -->
        </div>
    </body>
    <div id="overview" style="display: none;">
        <div class="" style="z-index: 900; position: fixed; top: 0px; left: 0px; visibility: visible; opacity: 0.7; width: 100%; height: 100%;" id="sbox-overlay"></div>
    </div>
    <div id="master_popup_login" style="display:none"> </div>
</html>