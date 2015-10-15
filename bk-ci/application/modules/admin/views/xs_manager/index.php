<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/jscal/src/css/jscal2.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>public/jscal/src/js/jscal2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/jscal/src/js/lang/en.js"></script>
<h1>Quản trị loại hình xổ số truyền thống</h1>
<div class="toppage">
    <span class="left">
        <form method="get" action="">
            <select name="lid" style="width: 200px;">
                <option value="">-- Chọn khu vực --</option>
                <?php foreach ($xs_location as $k => $v): ?>                            
                    <option <?php echo(isset($_GET['lid']) && $_GET['lid'] == $v->id ? 'selected="selected"' : ''); ?> value="<?php echo $v->id; ?>"><?php echo $v->name; ?></option>
                <?php endforeach; ?>
            </select>
            <span style="float: left; margin-left: 2px;">Lọc theo ngày</span>
            <input name="date" <?php echo (isset($_GET["date"]) && $_GET["date"] == 1 ? 'checked="checked"' : ''); ?> value="1" type="checkbox"/>
            <input id="f_rangeStart1" type="text" name="start_date"  value="<?php echo (isset($_GET["start_date"]) ? $_GET["start_date"] : date("d/m/Y")); ?>" style="width:100px;" />
            <input style="border: none;" type="image" id="f_rangeStart_trigger1" onclick="return false;" src="<?php echo img_link('date.gif') ?>"/>
            <input id="f_rangeStart2" type="text" name="end_date"  value="<?php echo (isset($_GET["end_date"]) ? $_GET["end_date"] : date("d/m/Y")); ?>" style="width:100px;" />
            <input style="border: none;" type="image" id="f_rangeStart_trigger2" onclick="return false;" src="<?php echo img_link('date.gif') ?>"/>
            <script type="text/javascript">
                new Calendar({
                    inputField: "f_rangeStart1",
                    dateFormat: "%d/%m/%Y",
                    trigger: "f_rangeStart_trigger1",
                    bottomBar: false,
                    onSelect: function() {
                        var date = Calendar.intToDate(this.selection.get());
                        this.hide();
                    }
                });
                new Calendar({
                    inputField: "f_rangeStart2",
                    dateFormat: "%d/%m/%Y",
                    trigger: "f_rangeStart_trigger2",
                    bottomBar: false,
                    onSelect: function() {
                        var date = Calendar.intToDate(this.selection.get());
                        this.hide();
                    }
                });
            </script>
            <input type="submit" value="Apply" class="btn" />
        </form>
    </span>
    <?php if ($ADD_ACTION == TRUE): ?>
        <span class="btnadd"><a href="<?php echo admin_url($module . '/add'); ?>">Thêm mới</a></span>
    <?php endif; ?>
</div>

<div class="toppage">
    <div style="float:left;font-size:13px"><a href="javascript:;" style="color:#2195C8;text-decoration:none"><strong>Tổng số:</strong> </a><font class="number"><?php echo $count; ?></font></div>
    <div class="pagination">
        <?php echo (isset($pagnav) ? $pagnav : ''); ?>
    </div>
</div>

<div class="tableout">
    <div class="title1">
        <div class="column ta-center" style="width:4%;"><?php echo lang('STT') ?></div>
        <div class="column ta-center" style="width:10%;">ID</div>        
        <div class="column ta-center" style="width:37%;">Alias</div>
        <div class="column ta-center" style="width:8%;">Giải đặc biệt</div>
        <div class="column ta-center" style="width:7%;">Giải nhất</div>
        <div class="column ta-center" style="width:10%;">Khu vực</div>
        <div class="column ta-center" style="width:10%;">Ngày</div>
        <div class="column ta-center" style="width:10%;"><?php echo lang('ACTIONS'); ?></div>
    </div>
    <form action="" method="post">
        <?php foreach ($rows as $k => $row): ?>
            <div class="linecate">
                <div class="column ta-center" style="width:4%;"><?php echo ($offset + $k + 1); ?></div>
                <div class="column ta-center" style="width:10%;"><?php echo $row->id ?></div>
                <div class="column" style="width: 37%;">
                    <a class="menu3000" href="javascript:;">
                        <?php
                        if ($row->alias)
                            echo $row->alias;
                        else
                            echo $row->lalias;
                        ?>
                    </a>
                </div>
                <div class="column ta-center" style="width:8%;"><?php echo $row->a0 ?></div>
                <div class="column ta-center" style="width:7%;"><?php echo $row->a1 ?></div>
                <div class="column ta-center" style="width: 10%;"><?php echo $row->lname ?></div>
                <div class="column ta-center" style="width: 10%;"><?php echo date('d/m/Y', strtotime($row->date)) ?></div>
                <div class="column ta-center" style="width:10%;">
                    <?php if ($EDIT_ACTION == TRUE): ?>
                        <img src="<?php echo img_link('edit.gif', 'admin'); ?>" /><a href="<?php echo admin_url($module . '/edit/' . $row->id); ?>" onclick =""><?php echo lang('EDIT'); ?></a>
                    <?php endif; ?>
                    <?php if ($EDIT_ACTION == TRUE): ?>
                        |&nbsp;<img src="<?php echo img_link('delete.gif', 'admin'); ?>" /><a onclick="return confirm('Bạn chắc chắn muốn xóa?');" href="<?php echo admin_url($module . '/delete/' . $row->id) ?>"><font color="#be0000"><?php echo lang('DELETE'); ?></font></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="bottom1">
            <div class="pagination">
                <?php echo (isset($pagnav) ? $pagnav : ''); ?>
            </div>
        </div>
    </form>  
</div>