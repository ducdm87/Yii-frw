<div class="">
    <form action="<?php echo $this->createUrl('modules/dopost') ?>" method="post">
        <div class="panel panel-primary">
            <div class="panel panel-heading">
                <span>Modified Extention</span>
                <div class="text-right right">
                    <button type="submit" class="btn btn-default" value="save" name="btn-action"><i class="fa fa-plus" style="color:blue"></i> Save</button>
                    <button type="submit" class="btn btn-default" value="apply" name="btn-action"><i class="fa fa-plus" style="color:blue"></i> Apply</button>
                    <button type="submit" class="btn btn-default" value="close" name="btn-action"><i class="glyphicon glyphicon-remove-circle" style="color:red"></i> Close</button>
                </div>
            </div>
            <div class="panel-body">
                <input type="hidden" name="id"  value="<?php echo $item->id; ?>"/>
                <div class="col-md-6">
                     <fieldset class="adminform">
                        <legend>Detail</legend>
                        <div class="form-group row">
                            <div class="col-md-3">Tên</div>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="<?php echo $item->title; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Module</div>
                            <div class="col-md-9"><?php echo $item->folder; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Hiển thị tên</div>
                            <div class="col-md-9">
                                <input type="radio" value="1" name="showtitle" <?php if($item->showtitle == 1) echo 'checked=""'; ?>  /> Yes
                                <input type="radio" value="0" name="showtitle" <?php if($item->showtitle == 0) echo 'checked=""'; ?> /> No
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Tác giả</div>
                            <div class="col-md-9"><?php echo $item->author; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Vị Trí</div>
                            <div class="col-md-9">
                                <input type="text" name="position" class="form-control" value="<?php echo $item->position; ?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <div class="col-md-3">Trạng thái</div>
                            <div class="col-md-9">
                                <select name="status" class="">
                                    <option value="1">Enable</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>
                        </div>                      
                        <div class="form-group row">
                            <div class="col-md-3">Mô tả</div>
                            <div class="col-md-9"><?php echo $item->description; ?></div>
                        </div> 
                    </fieldset>
                    
                    <fieldset class="adminform">
                        <legend>Menu Assignment</legend>
                        <div class="form-group row">
                            <div class="col-md-4">Menus</div>
                            <div class="col-md-8">
                                <input type="radio" name="selection-menu-select" class="selection-menu-select" value="all" /> All
                                <input type="radio" name="selection-menu-select" class="selection-menu-select" value="none" /> None
                                <input type="radio" name="selection-menu-select" class="selection-menu-select" value="select" checked="" /> Select Menu Item(s) from the List
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">Menu Selection</div>
                            <div class="col-md-8">                                
                                <?php echo $lists['selection-menu']; ?>
                            </div>
                        </div>
                     </fieldset>
                    
                </div>
                <div class="col-md-6">
                    <?php 
                   
                    $tabs = array();
                    foreach($item->params as $param){
                        $str_tab = "";
                        foreach($param->fields as $field){
                           $str_tab .= $field;
                        }
                        $tabs[$param->title] = $str_tab;
                    }
                    
//                    http://www.yiiframework.com/doc/api/1.1/CJuiTabs
                    $this->widget('zii.widgets.jui.CJuiTabs',array(
                        'tabs'=>$tabs ,'options'=>array('collapsible'=>true,),));
                    ?>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    $(".selection-menu-select").click(function(){
        var val = $(this).val();
        var opts = $("#selection-menu").find("option");
        if(val == "all"){
            $("#selection-menu").attr("disabled", true);
            for(var i=0; i<opts.length;i++){
                opts[i].selected = true;
            }
        }else if(val == "none"){
            $("#selection-menu").attr("disabled", true);
            for(var i=0; i<opts.length;i++){
                opts[i].selected = false;
            }
        }else{
            $("#selection-menu").attr("disabled", false);
        }
    });
</script>