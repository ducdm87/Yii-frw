<form action="<?php echo $this->createUrl('users/addgroup') ?>" method="post">    
    <div class="panel role_group">
        <?php
        foreach ($arr_group_list as $key => $items) {
            if ($items['parent_id'] == 0) {
                subGroup($arr_group_list, $items['id']);
            }
        }
        ?>
    </div>
</form>

<?php
    function subGroup($items, $id) {
        echo '<ul>';
        foreach ($items as $item) {
            if ($item['parent_id'] == $id) {
                if ($item['isActive'] != 0){
                    $link_edit = Yii::app()->createUrl("/usergroups/edit")."?cid=".$item['id'];
                    $link_delete = Yii::app()->createUrl("/usergroups/remove")."?cid=".$item['id'];
                    echo '<li><div class="alert alert-info alert-dismissible" role="alert"><strong>' . $item['name'] . '</strong>';
                    echo '<a href="'.$link_edit.'" class="close" ><span aria-hidden="false">&#10000;</span></a>&nbsp;'
                        . '<a href="'.$link_delete.'" class="close" ><span aria-hidden="false">&times;</span></a>';
                    echo '</div>';
                }else{
                    echo '<li><div class="alert alert-warning alert-dismissible" role="alert"><strong>' . $item['name'] . '</strong>';                
                         echo '</div>';
                }
                
                subGroup($items, $item['id']);
                echo '</li>';
            }
        }
        echo '</ul>';
    }
?>

<style>
    .role_group ul{
        margin-left: -20px;
    }
    .role_group ul li{
        list-style: none;
    }
    
    .alert {
        border: 1px solid transparent;
        border-radius: 4px;
        margin-bottom: 5px;
        padding: 5px;
    }
</style>