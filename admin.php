<?php
include('include/db_config.php');
include('include/header.php');
include('include/sidebar.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin Settings</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="include/js/validation_admin.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
function uname_check()
{
	var uname=document.getElementById('frm_username').value;
	window.location='admin.php?q_uname='+uname;
}
</script>
<?php
if(isset($_GET['q_uname']))
{
$f_name=$_GET['q_uname'];
$sel_uname="SELECT * FROM userinfo_tbl where username='".$f_name."'";
$res_user=mysql_query($sel_uname);
$row_user=mysql_num_rows($res_user);
if($row_user==1)
{
?>
<script>alert('Sorry, UserName already Exist !');
document.getElementById('frm_username').focus();
</script>
<?php	
}
else
{
?>
<script>alert('Congo, Username Available !');
document.getElementById('frm_username').focus();
</script>
<?php
	
}
}

?>
            <?php
if(isset($_GET['edit_id']))
{
	$f_id=$_GET['edit_id'];
	$q_edit="SELECT * FROM userinfo_tbl WHERE user_id='".$f_id."'";
	$res_edit=mysql_query($q_edit);
	$row_edit=mysql_fetch_array($res_edit);
}
if(isset($_GET['del_id']))
{
	$f_del_id=$_GET['del_id'];
	$q_del="DELETE FROM userinfo_tbl WHERE user_id='".$f_del_id."'";
	$q_res=mysql_query($q_del);
	header('location:admin.php');
}

?>
            </head>
            <body>
<div id="container">
              <h2><a href="#">Administration Panel</a> &raquo; <!--<a href="#" class="active">Dashboard</a>--></h2>
              <div id="main">
    <form id="tbl_userdata" action="" class="jNice"  <?php if((!isset($_GET['edit_id']))|| (!isset($_GET['view_status']))) {?> style="display:block;" <?php } else {?>
     style="display:none;"<?php }?>>
    <table cellpadding="0" cellspacing="0">
                  <tr>
        <th width="160" id="tbl-th">Username</th>
        <th width="200" id="tbl-th">Firstname</th>
        <th width="200" id="tbl-th">Skills</th>
        <th width="200" id="tbl-th">Designation</th>
        <!--<th width="200" id="tbl-th">Mail IDs</th>-->
        <th id="operation-row">Operations</th>
      </tr>
                  <?php 
				$cur_user=$_SESSION['uname'];
				$sel_id="select * from userinfo_tbl WHERE username!='".$cur_user."'";
				$res_id=mysql_query($sel_id);
				while($row=mysql_fetch_array($res_id))
				{
					
				?>
                  <tr id="tbl_rw_<?php echo $row['user_id'];?>">
        <td><?php echo $row['username'];?></td>
        <td><?php echo $row['firstname'];?></td>
        <td><?php echo $row['skills'];?></td>
        <td><?php echo $row['designation'];?></td>
        <!--<td><?php //echo $row['mail_ids'];?></td>-->
        <td class="action"><a href="admin.php?edit_id=<?php echo $row['user_id'];?>&view_status=1" ssid="user_view" class="view">View</a> <a id="user_edit" href="admin.php?edit_id=<?php echo $row['user_id'];?>" class="edit">Edit</a> <a href="admin.php?del_id=<?php echo $row['user_id'];?>" class="delete">Delete</a></td>
      </tr>
                  <?php 
							}
		?>
                </table>
    </form>
    <!--Form Elements Starts-->
    <div class="add-box"> 
                  <!--<input type="button" id="add-box-input" value="Add New Project" name="box-input" />-->
                  <?php if((!isset($_GET['edit_id']))|| (!isset($_GET['view_status']))) {?>
                  <button id="add_btn" class="button-submit" style="margin:0px;">Add New User</button>
                  <?php }?>
                </div>
  </div>
              <div id="form-div" <?php if(isset($_GET['edit_id'])){ ?> style="display:block"<?php }else {?> style="display:none"<?php }?>>
              <div id="main">
    <form name="message_form" id="message_form" method="post" action="#" class="jNice" enctype="multipart/form-data" onsubmit="return validateForm()">
                  <fieldset>
        <p>
                      <label>User Name :</label>
                      <input type="text" name="frm_username" id="frm_username" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['username'];}?>" />
                      <button class="button-submit" onclick="uname_check();">Check Availability</button>
                    </p>
        <p>
                      <label>Password</label>
                      <input type="password" name="frm_password" id="frm_password" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['password'];}?>" />
                    </p>
        <p>
                      <label>Firstname</label>
                      <input type="text" class="text-long"  id="frm_firstname" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['firstname'];}?>" name="frm_firstname" />
                    </p>
        <p>
                      <label>Last name</label>
                      <input type="text" id="frm_lastname" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['lastname'];}?>" name="frm_lastname" />
                    </p>
        <p>
                      <label>Gender :</label>
                      <input type="radio" class="jNiceRadio" name="frm_gender" value="male" <?php if(isset($_GET['edit_id'])){ $get_g=$row_edit['gender']; if($get_g=='male'){   ?> checked="checked" <?php } }?> />
                      <label>Male </label>
                      <input type="radio" class="jNiceRadio" name="frm_gender" value="female" <?php if(isset($_GET['edit_id'])){ $get_g=$row_edit['gender']; if($get_g=='female'){   ?> checked="checked" <?php } }?>  />
                      Female </p>
        <p>
                      <label>Address :</label>
                      <textarea rows="1" id="frm_address" name="frm_address"><?php if(isset($_GET['edit_id'])){ echo $row_edit['address'];}?>
</textarea>
                    </p>
        <p>
                      <label>Skills</label>
                      <input type="text"  id="frm_skills" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['skills'];}?>" class="text-long" name="frm_skills">
                      </textarea>
                    </p>
        <p>
                      <label>Designation</label>
                      <select name="frm_designation" id="frm_designation" >
            <option selected="selected" value="">--Select--</option>
            <option value="developer" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['designation']; if($f_desig=='developer') {?> selected="selected" <?php } }?>>Developer</option>
            <option value="client" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['designation']; if($f_desig=='client') {?> selected="selected" <?php } }?>>Client</option>
            <option value="pm" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['designation']; if($f_desig=='pm') {?> selected="selected" <?php } }?>>Project Manager</option>
          </select>
                    </p>
        <p>
                      <label>E-Mail ID</label>
                      <input type="email" id="frm_email" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['mail_ids'];}?>" name="frm_email" required="required" />
                    </p>
        <p>
                      <label>Upload Photo :</label>
                      <input type="file"  id="frm_image" class="button-submit" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['img_name'];}?>" name="frm_image" />
                    </p>
        <p>
                      <label>Date of Joining:</label>
                      <input type="text" readonly="readonly" id="datepicker" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['join_date'];}?>" name="prj_start_date" />
                    </p>
        <p style="z-index:0">
                      <label>City :</label>
                      <select name="frm_city" id="frm_city" style="z-index:0;">
            <option value="">--Select--</option>
            <option value="rajkot" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['city']; if($f_desig=='rajkot') {?> selected="selected" <?php } }?>>Rajkot</option>
            <option value="ahmedabad">Ahmedabad</option>
            <option value="mumbai">Mumbai</option>
            <option value="bhopal">Bhopal</option>
          </select>
                    </p>
        <p>
                      <label>State :</label>
                      <select name="frm_state" style="z-index:0;" id="frm_state">
            <option value="">--Select--</option>
            <option value="gujarat" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['state']; if($f_desig=='gujarat') {?> selected="selected" <?php } }?>>Gujarat</option>
            <option value="maharashtra">Maharashtra</option>
            <option value="mp">Madhya Pradesh</option>
          </select>
                    </p>
        <p>
                      <label>Country</label>
                      <select name="frm_country" id="frm_country">
            <option value="">--Select--</option>
            <option value="india" <?php if(isset($_GET['edit_id'])){ $f_desig=$row_edit['country']; if($f_desig=='india') {?> selected="selected" <?php } }?>>India</option>
          </select>
                    </p>
        <p>
                      <label>Mobile No </label>
                      <input type="text" id="frm_mobileno" class="text-long" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['mobile_no'];}?>" maxlength="10" name="frm_mobileno" />
                    </p>
        <?php if(!isset($_GET['edit_id'])) {?>
        <input type="submit" value="Add" name="add_user" />
        <input type="reset" value="Reset"  />
        <?php } else { ?>
        <input type="submit" value="Update" name="user_upload" />
        <?php } ?>
        <input type="button" class="button-submit" id="div_close" value="Close" name="div_close" />
        <input type="hidden" value="<?php if(isset($_GET['edit_id'])){ echo $row_edit['user_id'];}?>" name="pass_id" />
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
$("#form-div").slideToggle(2000)
$("#tbl_userdata").slideUp(2000);
});
/*Edit Button*/
$("#user_edit").click(function () {
$("#form-div").slideToggle(2000);
});
$("#user_view").click(function () {
	$("#form-div").slideToggle(2000)
	$("#tbl_userdata").slideUp(2000);
});
$("#div_close").click(function () {
	$("#tbl_userdata").slideDown(2000);
	$("#form-div").slideUp(2000);
	window.location='admin.php';
});
</script>
</body>
</html>