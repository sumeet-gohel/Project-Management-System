<?php
include('include/db_config.php');
include('include/header.php');
include('include/sidebar.php');
$cur_user=$_SESSION['uname'];
$chk_user="SELECT designation from userinfo_tbl WHERE username='".$cur_user."'";
$res_user=mysql_query($chk_user);
$row_user=mysql_fetch_array($res_user);
$dg_user=$row_user['designation'];
$cur_date=date('d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My Tasks</title>
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="include/js/validation_task.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
$(function(){
	$("#datepicker-end-date").datepicker();
});
</script>
<?php
//For Delete
if(isset($_GET['tid_delete']))
{
	$f_id=$_GET['tid_delete'];
	$del_pj="DELETE FROM task_tbl WHERE task_id='".$f_id."'";
	$run_q=mysql_query($del_pj);
	header('location:mytask.php');
}
//For Edit
if(isset($_GET['tid_edit']))
{
	$f_g_id=$_GET['tid_edit'];
	$sql_edit="SELECT * FROM task_tbl WHERE task_id='".$f_g_id."'";
	$res_edit=mysql_query($sql_edit);
	$row_edit=mysql_fetch_array($res_edit);
}

?>
</head>
<body>
<div id="container">
  <h2><a href="#">Tasks</a> &raquo; <!--<a href="#" class="active">Dashboard</a>--></h2>
  <div id="main">
    <form id="tsk_tbl" action="#" class="jNice" <?php if((isset($_GET['view_status']))||(isset($_GET['tid_edit']))){?> style="display:none;"  <?php }?>>
      <table cellpadding="0" cellspacing="0">
        <tr>
          <th width="160" id="tbl-th">Task </th>
          <th width="200" id="tbl-th">Project</th>
          <?php if($dg_user=='pm') {?>
          <th width="111" id="tbl-th">Assigned By</th>
          <th width="111" id="tbl-th">Given To</th>
          <?php }?>
          <th width="111" id="tbl-th">Days Left</th>
          <th id="operation-row">Operations</th>
        </tr>
        <?php 
				$prj_sel="SELECT * FROM task_tbl where given_to REGEXP '".$cur_user."' or raised_by REGEXP'".$cur_user."'";
				$res=mysql_query($prj_sel);
				while($row=mysql_fetch_array($res))
				{
				?>
        <tr>
          <td><?php echo $row['task_title'];?></td>
          <td><?php echo $row['project_name'];?></td>
          <?php if($dg_user=='pm') {?>
          <td><?php $chk_user=$row['raised_by']; if($chk_user==$cur_user){ echo "Me";}else { echo $row['raised_by'];}?></td>
          <td><?php echo $row['given_to'];?></td>
          <?php }?>
          <td><?php $f_date=date('d',strtotime($row['task_date']));$diff=abs($cur_date - $f_date); echo $diff;?></td>
          <td class="action">
          <a href="mytask.php?tid_edit=<?php echo $row['task_id'];?>&view_status=1" id="tsk_view" class="view">View</a> 
          <?php if($dg_user=='pm'){?>
          <a id="task_edit" href="mytask.php?tid_edit=<?php echo $row['task_id'];?>" class="edit">Edit</a> 
          <a href="mytask.php?tid_delete=<?php echo $row['task_id'];?>" class="delete">Delete</a></td>
          <?php }?>
        </tr>
        <?php 
				}
				?>
      </table>
    </form>
  </div>
  <?php if($dg_user=='pm') {?>
  <div class="add-box"> 
    <!--<input type="button" id="add-box-input" value="Add New Project" name="box-input" />-->
    <button id="add_btn" class="button-submit">Add New Task</button>
  </div>
  <?php }?>
</div>
<div id="form-div" <?php if(isset($_GET['tid_edit'])){ ?> style="display:block"<?php }else {?> style="display:none"<?php }?>>
  <div id="main">
    <form id="task_form"  method="post" action="db_store.php" class="jNice" onsubmit="return validateForm();">
      <fieldset>
        <p>
          <label>Select Project :</label>
          <select id="prj_name" name="prj_name">
            <option value="" >--Select--</option>
            <?php
				$sel_prj="SELECT * From project_detail_tbl";
				$res=mysql_query($sel_prj);
				$row_edit_nm=$row_edit['project_name'];
				while($row=mysql_fetch_array($res))
				{
			?>
            <option value="<?php echo $row['project_name'];?>" <?php if($row_edit_nm==$row['project_name']){ ?> selected="selected"<?php }?>;?><?php echo $row['project_name'];?></option>
            <?php
				}
            ?>
          </select>
        </p>
        <p>
          <label>Title :</label>
          <input type="text" id="tsk_title" class="text-long" value="<?php if(isset($_GET['tid_edit'])){echo $row_edit['task_title'];}?>" name="tsk_title" />
        </p>
        <p>
          <label>Description:</label>
          <textarea rows="1" id="tsk_desc" cols="1" name="tsk_desc"><?php if(isset($_GET['tid_edit'])){echo $row_edit['task_description'];}?>
</textarea>
        </p>
        <p>
          <label>Assign To Developers :</label>
          <?php 
			if(isset($_GET['tid_edit']))
			{
				$dev_a=$row_edit['given_to'];
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
            <input type="checkbox" class="jNiceCheckbox" name="prj_developers[]" value="<?php echo $row['username'];?>"/>
            <?php echo $row['username'];?></label>
        <p>
          <?php
			}
			}
		?>
        </p>
        <p>
          <label>Finish Date :</label>
          <input type="text" readonly="readonly" id="datepicker" class="text-long" value="<?php if(isset($_GET['tid_edit'])){echo $row_edit['task_date'];}?>" name="prj_start_date" />
        </p>
        <?php if(!isset($_GET['view_status'])){?>
        <?php if(!isset($_GET['tid_edit'])){?>
        <input type="submit" class="button-submit" value="Add Task" name="tsk_add" />
        <input type="reset" value="Reset"  />
        <input type="button" class="button-submit" id="div_close" value="Cancel" name="div_close" />
        <?php }else{?>
        <input type="submit" class="button-submit" value="Update" name="tsk_update" />
       <input type="reset" value="Reset"  />
       <input type="button" class="button-submit" id="div_close" value="Cancel" name="div_close" />
        <?php }?>
        <?php } else { ?>
        <input type="button" class="button-submit" id="div_close" value="Close" name="div_close" />
        <?php }?>
        <input type="hidden" value="<?php echo $log_user=$_SESSION['uname'];?>" name="raised_by" />
        <input type="hidden" value="<?php if(isset($_GET['tid_edit'])){ $val=$_GET['tid_edit']; echo $val;}?>" name="tid_edit" />
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
});
$("#task_edit").click(function () {
$("#form-div").slideToggle(2000);
$("#tsk_tbl").slideUp(2000);
});
$("#tsk_view").click(function () {
		$("#form-div").slideToggle(2000);
});
$("#div_close").click(function () {
	$("#form-div").slideUp(2000);
	$("#tsk_tbl").slideDown(2000);
	window.location='mytask.php';
});
</script>
</body>
</html>