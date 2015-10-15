<?php $this->createUrl('user/login'); ?>
<center>        
    <form method="post" action="" class="ui_form">
        <input type="hidden" value="/" name="page" class="ui_hidden">
        <table width="40%" class="shrinkwrapper" id="sortableTableNaN">
            <tbody>
                <tr>
                    <td> </td>
                    <td>
                        <table width="40%" class="ui_table" id="sortableTableNaN">
                            <thead><tr><td><b>Login to ... </b></td></tr></thead>
                            <tbody> 
                                <tr class="ui_table_body"> 
                                    <td colspan="1">			
                                        <table width="100%" id="sortableTableNaN">
                                            <tbody>
                                                <tr class="ui_form_pair">
                                                    <td align="center" colspan="2" class="ui_form_value">You must enter a username and password to login to the ...</td>                                                    
                                                </tr>
                                                <tr>
                                                    <td align="center" colspan="2" class="ui_form_value"><?php YError::showMessage(); ?> </td>
                                                </tr>
                                                <tr class="ui_form_pair">
                                                    <td class="ui_form_label"><b>Username</b></td>
                                                    <td colspan="1" class="ui_form_value"><input size="20" value="" name="LoginForm[username]" class="ui_textbox"></td>
                                                </tr>
                                                <tr class="ui_form_pair">
                                                    <td class="ui_form_label"><b>Password</b></td>
                                                    <td colspan="1" class="ui_form_value"><input type="password" size="20" value="" name="LoginForm[password]" class="ui_password"></td>
                                                </tr>
                                                <tr class="ui_form_pair">
                                                    <td class="ui_form_label"><b> </b></td>
                                                    <td colspan="1" class="ui_form_value"><input type="checkbox" id="save_1" value="1" name="LoginForm[rememberMe]" class="ui_checkbox"> 
                                                        <label for="save_1">Remember login permanently?</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="Login" class="ui_submit">
        <input type="reset" value="Clear">            
    </form>
</center>

<style>
/*
Copyright (c) 2008, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 2.6.0
*/
 html{color:#000;background:#FFF;}
 body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}
 table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}
 address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}
 li{list-style:none;}
 caption,th{text-align:left;}
 h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}
 q:before,q:after{content:'';}
 abbr,acronym{border:0;font-variant:normal;}
 sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}
 input,textarea,select{*font-size:100%;}legend{color:#000;}del,ins{text-decoration:none;}
 body{font:13px/1.231 arial,helvetica,clean,sans-serif;*font-size:small;*font:x-small;}
 select,input,button,textarea{font:99% arial,helvetica,clean,sans-serif;}
 table{font-size:inherit;font:100%;}pre,code,kbd,samp,tt{font-family:monospace;*font-size:108%;line-height:100%;}body{text-align:center;}
 #ft{clear:both;}#doc,#doc2,#doc3,#doc4,.yui-t1,.yui-t2,.yui-t3,.yui-t4,.yui-t5,.yui-t6,.yui-t7{margin:auto;text-align:left;width:57.69em;*width:56.25em;min-width:750px;}#doc2{width:73.076em;*width:71.25em;}#doc3{margin:auto 10px;width:auto;}#doc4{width:74.923em;*width:73.05em;}.yui-b{position:relative;}.yui-b{_position:static;}#yui-main .yui-b{position:static;}#yui-main,.yui-g .yui-u .yui-g{width:100%;}{width:100%;}.yui-t1 #yui-main,.yui-t2 #yui-main,.yui-t3 #yui-main{float:right;margin-left:-25em;}.yui-t4 #yui-main,.yui-t5 #yui-main,.yui-t6 #yui-main{float:left;margin-right:-25em;}.yui-t1 .yui-b{float:left;width:12.30769em;*width:12.00em;}.yui-t1 #yui-main .yui-b{margin-left:13.30769em;*margin-left:13.05em;}.yui-t2 .yui-b{float:left;width:13.8461em;*width:13.50em;}.yui-t2 #yui-main .yui-b{margin-left:14.8461em;*margin-left:14.55em;}.yui-t3 .yui-b{float:left;width:23.0769em;*width:22.50em;}.yui-t3 #yui-main .yui-b{margin-left:24.0769em;*margin-left:23.62em;}.yui-t4 .yui-b{float:right;width:13.8456em;*width:13.50em;}.yui-t4 #yui-main .yui-b{margin-right:14.8456em;*margin-right:14.55em;}.yui-t5 .yui-b{float:right;width:18.4615em;*width:18.00em;}.yui-t5 #yui-main .yui-b{margin-right:19.4615em;*margin-right:19.125em;}.yui-t6 .yui-b{float:right;width:23.0769em;*width:22.50em;}.yui-t6 #yui-main .yui-b{margin-right:24.0769em;*margin-right:23.62em;}.yui-t7 #yui-main .yui-b{display:block;margin:0 0 1em 0;}#yui-main .yui-b{float:none;width:auto;}.yui-gb .yui-u,.yui-g .yui-gb .yui-u,.yui-gb .yui-g,.yui-gb .yui-gb,.yui-gb .yui-gc,.yui-gb .yui-gd,.yui-gb .yui-ge,.yui-gb .yui-gf,.yui-gc .yui-u,.yui-gc .yui-g,.yui-gd .yui-u{float:left;}.yui-g .yui-u,.yui-g .yui-g,.yui-g .yui-gb,.yui-g .yui-gc,.yui-g .yui-gd,.yui-g .yui-ge,.yui-g .yui-gf,.yui-gc .yui-u,.yui-gd .yui-g,.yui-g .yui-gc .yui-u,.yui-ge .yui-u,.yui-ge .yui-g,.yui-gf .yui-g,.yui-gf .yui-u{float:right;}.yui-g div.first,.yui-gb div.first,.yui-gc div.first,.yui-gd div.first,.yui-ge div.first,.yui-gf div.first,.yui-g .yui-gc div.first,.yui-g .yui-ge div.first,.yui-gc div.first div.first{float:left;}.yui-g .yui-u,.yui-g .yui-g,.yui-g .yui-gb,.yui-g .yui-gc,.yui-g .yui-gd,.yui-g .yui-ge,.yui-g .yui-gf{width:49.1%;}.yui-gb .yui-u,.yui-g .yui-gb .yui-u,.yui-gb .yui-g,.yui-gb .yui-gb,.yui-gb .yui-gc,.yui-gb .yui-gd,.yui-gb .yui-ge,.yui-gb .yui-gf,.yui-gc .yui-u,.yui-gc .yui-g,.yui-gd .yui-u{width:32%;margin-left:1.99%;}.yui-gb .yui-u{*margin-left:1.9%;*width:31.9%;}.yui-gc div.first,.yui-gd .yui-u{width:66%;}.yui-gd div.first{width:32%;}.yui-ge div.first,.yui-gf .yui-u{width:74.2%;}.yui-ge .yui-u,.yui-gf div.first{width:24%;}.yui-g .yui-gb div.first,.yui-gb div.first,.yui-gc div.first,.yui-gd div.first{margin-left:0;}.yui-g .yui-g .yui-u,.yui-gb .yui-g .yui-u,.yui-gc .yui-g .yui-u,.yui-gd .yui-g .yui-u,.yui-ge .yui-g .yui-u,.yui-gf .yui-g .yui-u{width:49%;*width:48.1%;*margin-left:0;}.yui-g .yui-g .yui-u{width:48.1%;}.yui-g .yui-gb div.first,.yui-gb .yui-gb div.first{*margin-right:0;*width:32%;_width:31.7%;}.yui-g .yui-gc div.first,.yui-gd .yui-g{width:66%;}.yui-gb .yui-g div.first{*margin-right:4%;_margin-right:1.3%;}.yui-gb .yui-gc div.first,.yui-gb .yui-gd div.first{*margin-right:0;}.yui-gb .yui-gb .yui-u,.yui-gb .yui-gc .yui-u{*margin-left:1.8%;_margin-left:4%;}.yui-g .yui-gb .yui-u{_margin-left:1.0%;}.yui-gb .yui-gd .yui-u{*width:66%;_width:61.2%;}.yui-gb .yui-gd div.first{*width:31%;_width:29.5%;}.yui-g .yui-gc .yui-u,.yui-gb .yui-gc .yui-u{width:32%;_float:right;margin-right:0;_margin-left:0;}.yui-gb .yui-gc div.first{width:66%;*float:left;*margin-left:0;}.yui-gb .yui-ge .yui-u,.yui-gb .yui-gf .yui-u{margin:0;}.yui-gb .yui-gb .yui-u{_margin-left:.7%;}.yui-gb .yui-g div.first,.yui-gb .yui-gb div.first{*margin-left:0;}.yui-gc .yui-g .yui-u,.yui-gd .yui-g .yui-u{*width:48.1%;*margin-left:0;} .yui-gb .yui-gd div.first{width:32%;}.yui-g .yui-gd div.first{_width:29.9%;}.yui-ge .yui-g{width:24%;}.yui-gf .yui-g{width:74.2%;}.yui-gb .yui-ge div.yui-u,.yui-gb .yui-gf div.yui-u{float:right;}.yui-gb .yui-ge div.first,.yui-gb .yui-gf div.first{float:left;}.yui-gb .yui-ge .yui-u,.yui-gb .yui-gf div.first{*width:24%;_width:20%;}.yui-gb .yui-ge div.first,.yui-gb .yui-gf .yui-u{*width:73.5%;_width:65.5%;}.yui-ge div.first .yui-gd .yui-u{width:65%;}.yui-ge div.first .yui-gd div.first{width:32%;}#bd:after,.yui-g:after,.yui-gb:after,.yui-gc:after,.yui-gd:after,.yui-ge:after,.yui-gf:after{content:".";display:block;height:0;clear:both;visibility:hidden;}#bd,.yui-g,.yui-gb,.yui-gc,.yui-gd,.yui-ge,.yui-gf{zoom:1;}
h1{font-size:138.5%;}h2{font-size:123.1%;}h3{font-size:108%;}h1,h2,h3{margin:1em 0;}h1,h2,h3,h4,h5,h6,strong{font-weight:bold;}abbr,acronym{border-bottom:1px dotted #000;cursor:help;} em{font-style:italic;}blockquote,ul,ol,dl{margin:1em;}ol,ul,dl{margin-left:2em;}ol li{list-style:decimal outside;}ul li{list-style:disc outside;}dl dd{margin-left:1em;}caption{margin-bottom:.5em;text-align:center;}p,fieldset,table,pre{margin-bottom:1em;}




body {margin: 8px; color: #393939; line-height:1.5em; text-align:left;}
p { margin-top:4px; }

#system-message{
    margin: 0 auto !important;
     margin-bottom: 10px;
    margin-left: 20px;
    padding-left: 0;
}

#system-message .warning{
    background-color: #E6C0C0;
    border-bottom: 3px solid #DE7A7B;
    border-top: 3px solid #DE7A7B;
    color: #CC0000;
}

#system-message .notice{
    background-color: #E6C0C0;
    border-bottom: 3px solid #DE7A7B;
    border-top: 3px solid #DE7A7B;
    color: #CC0000;
}


table { border-width: 0px;
  empty-cells: hide;
  border-collapse: separate;
  margin:0;
}
table.formsection, table.ui_table, table.loginform {
  border-collapse: separate;
  *border-collapse: collapse;
  border: 1px solid #FFFFFF;
  width: 100%;
}
table.ui_table .ui_table {
  border: none;
}
img, a img { border:0; }
tr.row0 {background-color:#e8e8ea;}
tr.row1 {background-color:#f8f8fa;}
table.formsection thead, table.sortable thead, table.ui_table thead, table.loginform thead {
    background-color: #DFF0D8;
    border: 0 none;
    color: #468847;
    text-align: left;
}
table.ui_table thead td {
  padding: 0 2px;
}
table.sortable tbody td {
  padding: 2px;
}
table.ui_table td * {
  line-height:1.5em;
}
table.ui_table td textarea {line-height:normal; font-family:monospace;}
table.ui_table td div.barchart * {
  margin: 0;
}
table.ui_table thead td * {
  vertical-align:middle;
  margin: 0;
  padding: 0 0 0 1px; 
}
table.ui_table dt, table.ui_table dd {
  padding: 0 2px;
}
table.ui_form_end_buttons td {
  padding: 6px 3px;
}
div.ui_form_end_buttons {
  padding: 6px 3px;
}
.ui_form_end_buttons input {
  padding: 2px;
}
table.ui_grid_table td { padding: 2px 4px; }
input {
  padding: 1px;
}
th, td {
	border: none;
}
table.formsection tbody, table.sortable tbody, table.ui_table tbody, table.loginform tbody {
background-color:#f5f5f5;
}
a:link, a:visited { color: #0b46ab;
  text-decoration: none;
}
a:hover, a:visited:hover { color: #0b46ab;
  text-decoration: none;
}
a.ui-hidden-table-title { color: #393939; }
title { color: #393939;
  font-family: sans-serif;
}
h1,h2,h3,h4,h5 { color: #393939;
}
/* pre { font-size: 77%; } */
#main { border-style: solid;
  border:1px solid #FFFFFF;
  margin:0;
  padding:0;
}
table#main td {
  padding: 4px;
}
tr.mainsel { background-color: #ddffbb; }
tr.mainhigh { background-color: #ffffbb; }
tr.mainhighsel { background-color: #bbffcc; }
.itemhidden { display: none; }
.itemshown { display:block; }
.barchart { padding: 1px;
  border: 1px solid #d9d9d9;
  white-space: nowrap;
  /*position:relative;*/
}
.ui_post_header{ font-size: 116%;
 text-align: center;
 padding: 4px;
}
.ui_subheading { font-size: 116%;
  font-weight: bold;
}
hr { border: 0;
  width: 90%;
  height: 1px;
  color: #D9D9D9;
  background-color: #D9D9D9;
}
table.wrapper, table.shrinkwrapper {
  background-color:#D9D9D9;
  border:1px solid #D9D9D9;
  border-collapse:collapse;
  empty-cells: hide;
  margin:0;
  padding:0;
}
table.wrapper table.wrapper, table.shrinkwrapper table.shrinkwrapper {
  border:none;
  background-color:#f5f5f5;
}
div.wrapper {
  border:1px solid #D9D9D9;
  background-color:#F5F5F5;
  padding:0;
  margin:0;
}
.tabSelected {
  background-color:#D0E0FC;
}
.tabUnselected {
  background-color:#D9D9D9;
}
.goArrow { margin-bottom: -4px; }

.ui_form_label{
  text-align:right;
  vertical-align:top;
}
.ui_form_pair td {padding:4px;}
.ui_form_pair td table.ui_radio_table tr td {padding:2px;}
.ui_form_pair td table tr td {padding:0px;}
.ui_radio_table tr td {padding:2px;}
.ui_buttons_table tr td {padding:4px;}
/* left menu */
.mode {
  left: auto;
  right: auto;
}
.linkwithicon {
  position: relative;
  white-space: nowrap;
  left: 2px;
  width: 16px;
  padding-top: 3px;
  padding-bottom: 3px;
}
.linkwithicon img, .mode img {
  margin-bottom: -2px;
}
.leftlink {
  position: relative;
  left: 4px;
  right: 4px;
  line-height: 1.5em;
}
.linkindented {
  border: none;
  position: relative;
  margin-right: 2px;
  margin-left: 20px;
  line-height: 1.5em;
}
.aftericon {
  display: inline;
}
.domainmenu {
  white-space: nowrap;
  position: relative;
  left: 4px;
  padding-top: 2px;
}
</style>
<?php
die;