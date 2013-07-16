<?php
include('include/db_config.php');
include('include/header.php');
include('include/sidebar.php');
$cur_user=$_SESSION['uname'];
$chk_user="SELECT designation from userinfo_tbl WHERE username='".$cur_user."'";
$res_user=mysql_query($chk_user);
$row_user=mysql_fetch_array($res_user);
$dg_user=$row_user['designation'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Projects</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="include/js/validation_myprojects.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
$(function(){
	$("#datepicker-end-date").datepicker();
});
/*
function hide_msg()
{
document.getElementById('available').style.display='none';
document.getElementById('exist').style.display='none';
}
*/
function uname_check()
{
	var uname=document.getElementById('prj_name').value;
	window.location='myprojects.php?q_uname='+uname;
}
</script>
<!--<script> var con=confirm("Are, You Sure to Delete ?");if(con==true){}</script>-->
<?php
	if(isset($_GET['del_id']))
	{
	$f_id=$_GET['del_id'];
	$del_q="DELETE FROM project_detail_tbl WHERE project_id='".$f_id."'";
	$res_del=mysql_query($del_q);
	header('location:myprojects.php');
	}
?>
<?php
if(isset($_GET['edit_id']))
{
	$f_e_id=$_GET['edit_id'];
	$sel_edit="SELECT * from project_detail_tbl where project_id='".$f_e_id."'";
	$res_edit=mysql_query($sel_edit);
	$row_edit=mysql_fetch_array($res_edit);
}
?>
<?php
if(isset($_GET['status_id']))
{
	$f_id=$_GET['status_id'];
	$status_update="UPDATE project_detail_tbl SET status='completed',progress=100 WHERE project_id='".$f_id."'";
	$res_update=mysql_query($status_update);
	header('location:myprojects.php');
}

?>
</head>
<body onload="hide_msg()">
<div id="container">
  <h2><a href="#">My Projects</a> &raquo; <!--<a href="#" class="active">Dashboard</a>--></h2>
  <div id="main">
    <form id="tbl_view" action="db_store.php" class="jNice" <?php if(isset($_GET['view_status'])or isset($_GET['edit_id']) or isset($_GET['q_uname']) ){?> style="display:none;"  <?php }?>>
      <table cellpadding="0" cellspacing="0">
        <tr>
          <th width="160" id="tbl-th">Project Name</th>
          <th width="200" id="tbl-th">Progress</th>
          <th width="111" id="tbl-th">Days Left</th>
          <th id="operation-row">Operations</th>
        </tr>
        <?php 
				$dev_nm=$_SESSION['uname'];
				if($dg_user=='pm')
				{
				$sel="SELECT * FROM project_detail_tbl";
				}
				else
				{
				$sel="SELECT * FROM `project_detail_tbl` WHERE developers REGEXP '".$dev_nm."'";
				}
				$prj_res=mysql_query($sel);
				while($row=mysql_fetch_array($prj_res))
				{
				?>
        <tr>
          <td><?php echo $row['project_name'];?></td>
          <td><?php echo $row['progress'];?>%</td>
          <td>5</td>
          <td class="action"><a href="myprojects.php?edit_id=<?php echo $row['project_id'];?>&view_status=1" id="prj_view" class="view">View</a>
            <?php if($dg_user=='pm'){?>
            <a href="myprojects.php?edit_id=<?php echo $row['project_id'];?>" id="prj_edit" class="edit">Edit</a> <a href="myprojects.php?del_id=<?php echo $row['project_id'];?>" class="delete">Delete</a> <a href="myprojects.php?status_id=<?php echo $row['project_id'];?>" class="view">Complete</a></td>
          <?php }?>
        </tr>
        <?php 
					}
		?>
      </table>
    </form>
    <?php if(($dg_user=='pm') or (isset($_GET['view_status'])))  {?>
    <div class="add-box"> 
      <!--<input type="button" id="add-box-input" value="Add New Project" name="box-input" />-->
      <button <?php if((isset($_GET['edit_id']) || (isset($_GET['q_uname'])))) {?> style="display:none" <?php }?>id="add_btn" class="button-submit" style="margin:0px;">Add New Project</button>
    </div>
    <?php }?>
  </div>
  <div id="form-div" <?php if((isset($_GET['edit_id'])) or isset($_GET['q_uname'])){ ?> style="display:block"<?php }else {?> style="display:none"<?php }?>>
  <div id="main">
    <form id="prj_form" name="prj_form"  method="post" class="jNice" action="db_store.php"  onsubmit="return validateForm();" >
      <fieldset>
        <p>
          <label>Project Name :</label>
          <input type="text" class="text-long" id="prj_name" value="<?php if(isset($_GET['edit_id'])){echo $row_edit['project_name'];}?>" name="prj_name" />
          <button type="button" class="button-submit" onclick="return uname_check();">Check Availability</button>
          <?php
			if(isset($_GET['q_uname']))
			{
				$f_prj_nm=$_GET['q_uname'];
				$sql_ck_prj_nm="SELECT * FROM `project_detail_tbl` WHERE `project_name`='".$f_prj_nm."'";
				$run_prj=mysql_query($sql_ck_prj_nm);
				$count=mysql_num_rows($run_prj);
				if($count==1)
				{
					echo "<p id='exist' style='color:red;'>Name Exist</p>";
				} 
				else 
				{ 
					echo "<p id='available' style='color:green;'>Name Available</p>";
					echo "<script>document.getElementById('prj_name').value=$f_prj_nm;</script>";
				}
			} 
	
		?>
        </p>
        <p>
          <label>Description:</label>
			<textarea rows="1" name="prj_desc" id="prj_desc" cols="1"><?php if(isset($_GET['edit_id'])){echo $row_edit['description'];}?></textarea>
        </p>
        <p>
          <label>Client Name :</label>
          <input type="text" id="prj_client_name" class="text-long" value="<?php if(isset($_GET['edit_id'])){echo $row_edit['client_name'];}?>" name="prj_client_name" />
        </p>
        <p>
          <label>Start Date :</label>
          <input type="text" id="datepicker"  readonly="readonly" class="text-long" value="<?php if(isset($_GET['edit_id'])){echo $row_edit['start_date'];}?>" name="prj_start_date" />
        </p>
        <p>
          <label>End Date:</label>
   <input type="text" id="datepicker-end-date" readonly="readonly" class="text-long" value="<?php if(isset($_GET['edit_id'])){echo $row_edit['finish_date'];}?>" name="prj_end_date" />
        </p>
        <p>
          <label>Plan Efforts(Project Progress):</label>
          <input type="text" id="prj_efforts" class="text-long" maxlength="3" value="<?php if(isset($_GET['edit_id'])){echo $row_edit['progress'];}?>" name="prj_efforts" />
        </p>
        <p>
          <label>Assign Developers :</label>
          <?php 
			if(isset($_GET['edit_id']))
			{
				$dev_a=$row_edit['developers'];
				$ex_arr=explode(',',$dev_a);
				foreach($ex_arr as $list_array)
				{
					echo "<p>";
					echo "<label>";
					echo "<input type='checkbox' class='jNiceCheckbox' name='prj_developers[]' checked='checked' value='$list_array'>$list_array";
					echo "</label>";
					echo "<p>";
				}
            }
            else
			{
			$sel_developers="SELECT username from userinfo_tbl where designation!='pm' and username!='".$cur_user."'";
			$res=mysql_query($sel_developers);
			while($row=mysql_fetch_array($res))
			{
		?>
        <p>
          <label>
            <input type="checkbox" id="prj_developers[]" class="jNiceCheckbox" name="prj_developers[]" value="<?php echo $row['username'];?>"/>
            <?php echo $row['username'];?></label>
        <p>
          <?php
			}
			}
		?>
        </p>
        <?php if(!isset($_GET['view_status'])){?>
        
        <?php if(!isset($_GET['edit_id'])){?>
        <input type="submit" id="prj_add" class="button-submit" value="Add Project" name="prj_add" />
        <input type="reset" value="Reset"  />
        <?php } else {  ?>
        <input type="submit" id="prj_update" class="button-submit" value="Update" name="prj_update" />
        <?php } ?>
        <?php } //else {?>
        <!--<input type="button" class="button-submit" id="div_close" value="Close" name="div_close" />-->
        <?php //}?>
        <input type="button" class="button-submit" id="div_close" value="Cancel" name="div_close" />
        <input type="hidden" value="<?php if(isset($_GET['edit_id'])){ $val=$_GET['edit_id']; echo $val;}?>" name="pass_id"  />
      </fieldset>
    </form>
  </div>
</div>
<div class="clear"></div>
</div>
</div>
<p id="footer">&copy; Copyright by viaviweb 2013 <a href="http://www.viaviweb.com">viaviweb</a></p>
<script type="text/javascript">
/* RT Change  */
$("#add_btn").click(function () {
$("#form-div").slideToggle(2000);
$("#tbl_view").slideUp(2000);
$("#add_btn").hide();

});
$("#prj_edit").click(function () {
	$("#tbl_view").slideUp(1000);
	$("#form-div").slideDown(2000);
	$("#add_btn").hide();

});
$("#prj_view").click(function () {
	$("#add_btn").hide();
	$("#form-div").slideToggle(2000);
	$(".text-long").attr("disabled",true);

});
$("#div_close").click(function () {
	$("#form-div").slideUp(2000);
	window.location='myprojects.php';
});
</script>
</body>
</html>