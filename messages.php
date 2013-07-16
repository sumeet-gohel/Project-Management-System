<?php
include('include/db_config.php');
include('include/header.php');
include('include/sidebar.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Messages</title>
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="include/js/validation_message.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
$(function(){
	$("#datepicker-end-date").datepicker();
});
</script>
<?php
if(isset($_GET['del_id']))
{
	$f_d_id=$_GET['del_id'];
	$sql_del="DELETE FROM message_tbl WHERE message_id='".$f_d_id."'";
	$res_del=mysql_query($sql_del);
	header('location:messages.php');
}
?>
<?php
if(isset($_GET['msg_id']))
{
	$g_id=$_GET['msg_id'];
	$sql_ed="SELECT * FROM message_tbl where message_id='".$g_id."'";
	$res_ed=mysql_query($sql_ed);
	$row_ed=mysql_fetch_array($res_ed);
}
?>
</head>
<body>
<div id="container">
 <h2><a href="#">Messages</a> &raquo; <!--<a href="#" class="active">Dashboard</a>--></h2>
      <div id="main">
        <form id="tbl_message" action="" class="jNice">
          <table cellpadding="0" cellspacing="0">
            <tr>
               <th width="160" id="tbl-th">Message</th>
              <th width="200" id="tbl-th">Sent By</th>
              <th width="200" id="tbl-th">Created</th>
              <th id="operation-row">Operations</th>
            </tr>
            <?php 
				$cur_user=$_SESSION['uname'];
				$sel_id="select * from userinfo_tbl WHERE username='".$cur_user."'";
				$res_id=mysql_query($sel_id);
				$row_id=mysql_fetch_array($res_id);
				$f_id=$row_id['user_id'];
				$prj_sel="SELECT * FROM message_tbl where receiver='".$f_id."'";
				$res=mysql_query($prj_sel);
				while($row=mysql_fetch_array($res))
				{
					
				?>
            <tr>
              <td><?php echo $row['message_subject'];?></td>
              <td><?php echo $row['sent_by'];?></td>
              <td><?php echo $row['created'];?></td>              
              <td class="action">
              <a id="msg_view"  href="messages.php?msg_id=<?php echo $row['message_id'];?>&view_status=1"  class="view">View</a>
              <!--<a href="#" class="edit">Edit</a>-->
              <a href="messages.php?del_id=<?php echo $row['message_id'];?>" class="delete">Delete</a></td>
            </tr>
            <?php 
							}
							?>
          </table>
 </form>
 <!--Form Elements Starts-->
    <div class="add-box">
      <!--<input type="button" id="add-box-input" value="Add New Project" name="box-input" />-->
      <button id="add_btn" class="button-submit" style="margin:0px;" >Send Message</button>
    </div>
  </div>
<div id="form-div" <?php if(isset($_GET['msg_id'])){?> style="display:block" <?php }else {?> style="display:none" <?php }?>>
  <div id="main">
    <form name="message_form" id="message_form" method="post" action="#" class="jNice" onsubmit="return validateForm()">
    	<fieldset>
      <p>
      	<label>Send To :</label>
        <select id="msg_sender" name="msg_sender">
        <option value="">--Select--</option>
        <?php
			
			$sel_developers="SELECT * from userinfo_tbl where username!='".$cur_user."'";
			$res=mysql_query($sel_developers);
			while($row=mysql_fetch_array($res))
			{
		?>
 <option value="<?php $uid= $row['user_id']; echo $uid;?>" <?php if(isset($_GET['msg_id'])) { if($uid==$row_ed['receiver']) {?> selected="selected" disabled="disabled" <?php } }?> ><?php echo $row['username'];?></option>
        <?php
			}
		?>
        </select>
      </p>
      <p>
        <label>Subject :</label>
        <input type="text" id="msg_subject" class="text-long" value="<?php if(isset($_GET['msg_id'])) { echo $row_ed['message_subject']; }?>" name="msg_subject" />
      </p>
      <p>
        <label>Message:</label>
        <textarea rows="1" cols="1" id="msg_text" name="msg_text"><?php if(isset($_GET['msg_id'])) { echo $row_ed['message'];}?></textarea>
      </p>
      <p>
      	<label>Tags :</label>
        <input type="text" class="text-long"  id="msg_tags" name="msg_tags" value="<?php if(isset($_GET['msg_id'])) { echo $row_ed['tags'];}?>" />
      </p>
      <p>
        <label>Date :</label>
        <input type="text" id="datepicker" class="text-long" value="<?php if(isset($_GET['msg_id'])) { echo $row_ed['created']; }?>" name="prj_start_date" />
      </p>
      <?php if(!isset($_GET['msg_id'])) {?>
      <input type="submit" value="Send" name="msg_send" />
      <input type="reset" value="Reset"  />
      <input type="button" class="button-submit" id="div_close" value="Cancel" name="div_close" />
      <?php } else {?>
      <input type="button" class="button-submit" id="div_close" value="Close" name="div_close" />
      <?php }?>
      <input type="hidden" value="<?php echo $log_user=$_SESSION['uname'];?>" name="from" />
      </fieldset>
    </form>
  </div>
 <!--Form Elements Ends-->
</div>
 <div class="clear"></div>
    </div>
 </div>
   <p id="footer">&copy; Copyright by viaviweb 2013 <a href="http://www.viaviweb.com">viaviweb</a></p>
<script type="text/javascript">
/* RT Change  */
$("#add_btn").click(function () {
	$("#tbl_message").slideUp(2000);	
$("#form-div").slideToggle(2000);
});
$("#msg_view").click(function () {
	$("#form-div").slideToggle(2000);
});
$("#div_close").click(function () {
	$("#tbl_message").slideDown(2000);
	$("#form-div").slideUp(2000);
	window.location='messages.php';
});
</script>
</body>
</html>