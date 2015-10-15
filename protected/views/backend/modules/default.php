
<div class="row">
    <div class="panel panel-primary">
        <div class="panel panel-heading">
            <span><i class="fa fa-bars"></i> Modules</span>
        </div>
        <div class="panel-body">
            <div class="">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#uploadExtention"><i class="fa fa-cog"></i> Upload</a>
                <a href="<?php echo $this->createUrl('modules/create') ?>" class="btn btn-danger"><i class="fa fa-plus"></i> Create Module</a>
            </div>
            <br/>
            <form action="<?php echo $this->createUrl('modules/updatestatus') ?>" method="post">
                <table class="table table-bordered table-hover table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Extention name</th>
                            <th>Extention description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($extentions) && $extentions): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($extentions as $ext): ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $ext['title']; ?>
                                        <div class="clearfix"></div>
                                        <label class="checkbox-inline">
                                            <input type="hidden" name="ids[]" value="<?php echo $ext['id'] ?>">
                                            <input type="checkbox" name="status[]" <?php echo ($ext['status'] == 1) ? 'checked' : '' ?> value="<?php echo $ext['id'] ?>"/>Enable
                                        </label>
                                        <label class="checkbox-inline">
                                            <a href="modules/delete?id=<?php echo $ext['id'] ?>">Delete</a>
                                        </label>
                                         <label class="checkbox-inline">
                                            <a href="modules/create?id=<?php echo $ext['id'] ?>">Edit</a>
                                        </label>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                                <?php $i ++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">
                                    <h3 class="text-center">Not extention dispplay</h3>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <br/>
                <tfoot>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Config</button>
                </tfoot>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadExtention" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="uploadExtentionLabel">
    <div class="modal-dialog" role="document">
        <form action="<?php echo $this->createUrl('modules/ajaxuploadextention') ?>" method="post" id="ajaxUploadExtention" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload Extention</h4>
                </div>
                <div class="modal-body">
                    <div id="progress">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                60%
                            </div>
                        </div>
                    </div>
                    <div class="form-group file-upload">
                        <div class="input-group">
                            <input type="text" name="feck_file" class="form-control" placeholder="" >
                            <span class="input-group-btn">
                                <span class="btn btn-warning btn-file">
                                    <i class="fa fa-cloud-upload"></i> Browse...<input type="file" id="userfile" name="userfile" >
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
