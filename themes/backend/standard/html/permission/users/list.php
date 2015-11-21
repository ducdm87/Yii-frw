
<form name="adminForm" method="post" action="">
    <div class="row">  
        <div class="col-lg-7">            
        </div>
        <div class="col-lg-5">
            <?php echo $lists['filter_group']; ?>
            <?php echo $lists['filter_state']; ?>
        </div>
    </div>
    <table class="adminlist" cellpadding="1">
        <thead>
            <tr>
                <th width="2%" class="title"> #	</th>
                <th width="3%" class="title"> <input type="checkbox" onclick="checkAll(<?php echo count($list_user); ?>);" value="" name="toggle"> </th>
                <th class="title"> <a>User Name</a></th>
                <th class="title"> <a>Group</a></th>
                <th class="title"> <a>ID</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            foreach ($list_user as $i => $item) {
                $link_grant = Router::buildLink("permission", array("view"=>"users", "layout"=>"grant",'cid'=>$item['id']));
                $link_grant_group = Router::buildLink("permission", array("view"=>"groups", "layout"=>"grant",'cid'=>$item['groupID']));
                ?>
                <tr class="row1">
                    <td><?php echo ($i + 1); ?></td>
                    <td><input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo $item['id'] ?>" name="cid[]" id="cb<?php echo ($i); ?>"></td>
                    <td>
                        <a href="<?php echo $link_grant; ?>">
                            <?php echo $item['username'] ?>
                        <a>
                    </td>
                    <td>
                        <a href="<?php echo $link_grant_group; ?>">
                            <?php echo isset($arr_group[$item['groupID']])?$arr_group[$item['groupID']]['name']:""; ?>
                        <a>
                    </td>                    
                    <td><?php echo $item['id'] ?></td>
                </tr>
                <?php $k = 1 - $k;
            }
            ?>
        </tbody>


    </table>
    <input type="hidden" value="0" name="boxchecked">
    <input type="hidden" value="" name="filter_order">
    <input type="hidden" value="" name="filter_order_Dir">
    <input type="hidden" value="" name="task" />
</form>




