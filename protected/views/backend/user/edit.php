<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($item);
?>


<form name="adminForm" method="post" action="">
    <div class="left width-50">
        <table class="admintable" cellspacing="1">
            <tr>
                <td width="150" class="key"> <label for="first_name"> First Name </label> </td>
                <td> <input type="text" value="<?php echo $item["first_name"]; ?>" size="40" class="inputbox" id="first_name" name="first_name"> </td>
            </tr>
            <tr>
                <td width="150" class="key"> <label for="last_name"> Last Name </label> </td>
                <td> <input type="text" value="<?php echo $item["last_name"]; ?>" size="40" class="inputbox" id="last_name" name="last_name"> </td>
            </tr>
            <tr>
                <td width="150" class="key"> <label for="username"> Username </label> </td>
                <td> <input type="text" value="<?php echo $item["username"]; ?>" size="40" class="inputbox" id="username" name="username"> </td>
            </tr>       
            <tr>
                <td width="150" class="key"> <label for="email"> Email </label> </td>
                <td> <input type="text" value="<?php echo $item["email"]; ?>" size="40" class="inputbox" id="email" name="email"> </td>
            </tr>
            <tr>
                <td width="150" class="key"> <label for="password"> New Password  </label> </td>
                <td> <input type="password" value="" size="40" class="inputbox" id="name" name="password"> </td>
            </tr>
            <tr>
                <td width="150" class="key"> <label for="repassword"> Verify Password </label> </td>
                <td> <input type="password" value="" size="40" class="inputbox" id="name" name="repassword"> </td>
            </tr>
            <tr>
                <td width="150" class="key"> <label for="status"> Block User </label> </td>
                <td>
                    <input type="radio" value="1" class="inputbox" id="block0"  name="status" <?php if ($item["status"] == 1) echo "checked" ?> /> 
                    <label for="block0">No</label>
                    <input type="radio" value="0" class="inputbox" id="block1"  name="status" <?php if ($item["status"] == 0) echo "checked" ?> /> 
                    <label for="block0">Yes</label>
                </td>
            </tr>
            <?php
            if ($item["id"] != 0) {
                ?>
                <tr>
                    <td width="150" class="key"> <label> Register Date </label> </td>
                    <td> <?php echo $item["cdate"]; ?></td>
                </tr>            
                <tr>
                    <td width="150" class="key"> <label>  Last modified Date </label> </td>
                    <td> <?php if ($item["mdate"]) echo $item["mdate"]; ?> </td>
                </tr>
                <tr>
                    <td width="150" class="key"> <label> Last Visit Date    </label> </td>
                    <td> <?php echo $item["lastvisit"]; ?></td>
                </tr>
            <?php } ?>

        </table>
    </div>

    <div class="left width-50">
        <table class="admintable" cellspacing="1">
            <tr>
                <td width="100" class="key"> <label for="first_name"> Group </label> </td>
                <td> 
                    <select size="10" id="gid" name="gid">                        
                        <?php
                        foreach ($arr_group as $gid => $group) {                            
                            $str = $group[3] == 0 ? "." : '';
                            $str .= str_repeat('&nbsp;&nbsp', $group[2]) . "-";
                            ?>
                            <option <?php if ($item['groupID'] == $gid) echo 'selected'; ?> value="<?php echo $gid; ?>"><?php echo $str; ?><?php echo $group[1] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" value="<?php echo $item["id"]; ?>" name="id" />
    <input type="hidden" value="<?php echo $item["id"]; ?>" name="cid[]" />
    <input type="hidden" value="" name="task" />
</form>




