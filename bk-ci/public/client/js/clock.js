function clock(){year=date.getFullYear();month=""+(date.getMonth()+1);day=date.getDate();hours=date.getHours();minutes=date.getMinutes();seconds=date.getSeconds();hours=""+hours;minutes=""+minutes;seconds=""+seconds;if(month.length<2){month="0"+month}if(day<10){day="0"+day}if(hours.length<2){hours="0"+hours}if(minutes.length<2){minutes="0"+minutes}if(seconds.length<2){seconds="0"+seconds}ngay=year+"-"+month+"-"+day;$(".timeplace").html("Ngày "+day+"/"+month+"/"+year+": "+hours+":"+minutes+":"+seconds);date.setTime(date.getTime()+1000);setTimeout("clock()",1000)}function timesync(c,b){c=c||3600000;b=b||5000;var a=new Date().getTime();$.ajax({url:uri_root+"client/chat/timesrv",timeout:b,success:function(e){if(e.length){var d=new Date().getTime()-a;date.setTime(e*1000+d)}setTimeout(function(){timesync(c,b)},c)},error:function(d){if(d.statusText!="abort"){setTimeout(function(){timesync(c,b)},b*2)}}})}function intime(){if(month.length<2){month="0"+month}if(month.length<2){day="0"+day}var a=year+"-"+month+"-"+day;return !is_tet(a)&&hours==18&&minutes>5&&minutes<45}function is_tet(a){return a=="2014-01-30"||a=="2014-01-31"||a=="2014-02-01"||a=="2014-02-02"}function chuaquay(c){var a=new Date(year,month-1,day);c=c.split("-");var b=new Date(c[0],c[1]-1,c[2]);return a.getTime()==b.getTime()&&hours<18||a.getTime()<b.getTime()}function ds(a,e){a=sqldate(a);a=a.split("-");var c=new Date(a[0],a[1]-1,a[2]);c.setTime(c.getTime()+e*86400000);var d=c.getFullYear();var f=c.getMonth()+1;var b=c.getDate();if((f+"").length<2){f="0"+f}if((b+"").length<2){b="0"+b}return d+"-"+f+"-"+b}function betday(){if(chuaquay()){betday=year+"/"+month+"/"+day}else{return ds(year+"-"+month+"-"+day,1)}}function sqldate(a){a=a.replace(/[^0-9\-\/]/g,"");if(a.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/)){a=a.split("/");if(a[0].length<2){a[0]="0"+a[0]}if(a[1].length<2){a[1]="0"+a[1]}return a[2]+"-"+a[1]+"-"+a[0]}else{return a}}function normaldate(a){a=a.replace(/[^0-9\-\/]/g,"");if(a.match(/^\d{4}\-\d{1,2}\-\d{1,2}$/)){a=a.split("-");if(a[2].length<2){a[2]="0"+a[2]}if(a[1].length<2){a[1]="0"+a[1]}return a[2]+"/"+a[1]+"/"+a[0]}else{return a}}function getwday(a){a=sqldate(a);a=a.split("-");var b=new Date(a[0],a[1]-1,a[2]);return weekday[b.getDay()]}function today(){return year+"-"+month+"-"+day}function timeshow(d){var a=new Date();a.setTime(d*1000);var i=a.getFullYear();var e=""+(a.getMonth()+1);var h=a.getDate()+"";var j=a.getHours()+"";var f=a.getMinutes()+"";var b=a.getSeconds()+"";if(e.length<2){e="0"+e}if(h.length<2){h="0"+h}if(j.length<2){j="0"+j}if(f.length<2){f="0"+f}if(b.length<2){b="0"+b}var g=i+"-"+e+"-"+h;if(g==ngay){var c="hôm nay"}else{if(g==ds(ngay,-1)){var c="hôm qua"}else{c="ngày "+normaldate(g)}}return j+":"+f+":"+b+" "+c};