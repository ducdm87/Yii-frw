<script type="text/javascript" src="<?php echo base_url();?>public/ckeditor/ckeditor.js"></script>

<h1><?php //echo lang($MODULE.'_ADD_NEW');?></h1>

 <div class="tableout">
		<div class="title1">
            <div class="column" style="width:100%;"><?php echo lang($MODULE.'_ADD_NEW');?></div>
        </div>
<form enctype="multipart/form-data" method="post" action="" id="articles_form">
<div class="editcate_ct">
	<div class="btarticle">
    	<!-- <input type="button" value="Cancel" class="btn" onclick="$('#light_adct').hide();$('#fade_adct').hide();" /> -->
        <input type="submit" value="<?php echo lang('ADD');?>" class="btn" />
    </div>
	<div class="boxadd">
    	<ul class="lineadd2"> 
    		<?php if($this->message->has('error')):?>
    		<li>
    			<span class="left">&nbsp;</span>
    			<span class="right">
				<?php echo $this->message->display();?>
				</span>
    		</li>
    		<?php endif;?>
    		<li>
                <span class="left"><b><?php echo lang($MODULE.'_CATEGORY');?><font color="red">(*)</font> :</b></span>
                <span class="right">
                	<select name="catid" style="width: 200px;">
                		<option value="0">---ROOT---</option>
                		<?php foreach($list as $k => $v):?>
                		<option <?php echo(isset($submitted['pid']) && $submitted['pid'] == $v->id ? 'selected="selected"' : '');?> value="<?php echo $v->id;?>"><?php echo $v->name;?></option>
                		<?php endforeach;?>
                	</select>
                </span>
            </li>  
            <li>
                <span class="left"><b><?php echo lang($MODULE.'_NAME');?><font color="red">(*)</font> :</b></span>
                <span class="right"><input type="text" name="name" value="<?php echo(isset($submitted['name']) ? $submitted['name'] : '');?>" style="width:60%; margin:0;" /></span>
            </li>   	   	
            <li>
                <span class="left"><b><?php echo lang($MODULE.'_TITLE');?><font color="red">(*)</font> :</b></span>
                <span class="right"><input type="text" name="title" value="<?php echo(isset($submitted['title']) ? $submitted['title'] : '');?>" style="width:60%; margin:0;" /></span>
            </li> 
            
            <li>
            	<span class="left"><b><?php echo lang($MODULE."_CONTENT");?></b><font color="red">(*)</font></span>
                <span class="right"><textarea id="content" name="content"  style="width:60%; height:200px; border: none;"><?php echo(isset($submitted['content']) ? $submitted['content'] : '');?></textarea></span>
                <script type="text/javascript">
				$(function() {	
					if(CKEDITOR.instances['contents']) {						
						CKEDITOR.remove(CKEDITOR.instances['content']);
					}
					CKEDITOR.config.width = "60%";
					CKEDITOR.config.border = "none";
				    CKEDITOR.config.height = 400;
					CKEDITOR.replace('content',{
				    	toolbar :
				    		[['Source','Maximize','-','Format','Font','FontSize'],"/",
				    		 ['PasteText','PasteFromWord'],
					         ['TextColor','BGColor','-','Bold','Italic','Underline'],
					         ['NumberedList','BulletedList'],'/',
					         ['Outdent','Indent','Blockquote'],
					         ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					         ['Image','Table','-', 'Link', 'Unlink' ]]
					});
				})
				</script>                
            </li>  
            <li>
                <span class="left"><b><?php echo lang($MODULE.'_ORDER');?><font color="red">(*)</font> :</b></span>
                <span class="right"><input type="text" name="order" value="<?php echo(isset($submitted['order']) ? $submitted['order'] : '');?>" style="width:60%; margin:0;" /></span>
            </li>   
            <li>
       			<span class="left"><b><?php echo "Menu";?>:</b></span>
       			<span class="right">
                	<input name="main" <?php echo (isset($submitted['main']) && $submitted['main'] == '1' ? 'checked="checked"' : '');?> value="1" type="checkbox"/> 
                </span>
       		</li>   
       		<li>
       			<span class="left"><b><?php echo lang('ACTIVE');?>:</b></span>
       			<span class="right">
                	<input name="active" <?php echo (isset($submitted['active']) && $submitted['active'] == 'yes' ? 'checked="checked"' : '');?> value="yes" type="checkbox"/> 
                </span>
       		</li>
       		
        </ul>
    </div>
    <div class="btarticle">
    	
        <input type="submit" value="<?php echo lang(($row ? 'EDIT' : 'ADD'));?>" class="btn" />

    </div>
</div>
</form>
</div>
