 
<form name="adminForm" method="post" action="">
    <div class='items-tree user-tree'>
        <?php
        showNodeTree($items);
        ?> 
    </div> 
    <?php
    foreach ($items as $item) {
        
    }
    ?>

    <input type="hidden" value="0" name="boxchecked">
    <input type="hidden" value="" name="filter_order">
    <input type="hidden" value="" name="filter_order_Dir">
    <input type="hidden" value="" name="task" />
</form>

<?php function showNodeTree($items, $level = 0)
{
    $class = $level >1?"line":"";
    echo '<ul class="'.$class.'">';    
        $k = 0;
        foreach($items as $item){
            $_class = $k==0?"first":($k==count($items)-1?"last":"");
            $img_type = " <img src='/images/icons/affected_$item->affected.png' style='height: 16px;' />";
            if($item->status == 1)
                $img_status = " <img src='/images/icons/jicon/tick.png' style='height: 16px;' />";
            else
                $img_status = " <img src='/images/icons/jicon/publish_x.png' style='height: 16px;' />";
            if(isset($item->data_child) AND count($item->data_child)>0){                
                    if($level != 0){
                        echo '<li class="folder parent '.$_class.'">';
                        echo '<i class="folder-btn btn-open" rel=""></i>';
                        echo '<input id="cb'.$item->id.'" type="checkbox" value="'.$item->id.'" name="cid[]" onclick="isChecked(this.checked);" />';
                        echo ' <a>'.$item->title.'</a>' . $img_type . $img_status;
                    }else{                        
                        echo '<li>';                        
                        echo ' <a>'.$item->title.'</a>';
                    }
                    
                    $level++;
                    showNodeTree($item->data_child, $level);
                echo '</li>';
            }else{
                echo '<li class="file '.$_class.'">';
                    echo '<input id="cb'.$item->id.'" type="checkbox" value="'.$item->id.'" name="cid[]" onclick="isChecked(this.checked);" />';
                    echo ' <a>'.$item->title.'</a>' . $img_type . $img_status;
                echo '</li>';
            }
            $k++;
        }
    echo '</ul>';
    
}




