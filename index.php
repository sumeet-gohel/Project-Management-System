<?php
include('include/db_config.php');
include('include/header.php');
include('include/sidebar.php');
$cur_user=$_SESSION['uname'];
$cur_date=date('d');
$sel_id="select * from userinfo_tbl WHERE username='".$cur_user."'";
$res_id=mysql_query($sel_id);
$row_id=mysql_fetch_array($res_id);
$f_id=$row_id['user_id'];
$dg_user=$row_id['designation'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ViAviWebtech PMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--JavaScript-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>-->
<!-- End-->
<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->

<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>
<body>
<h2><a href="#">Dashboard</a> &raquo; <!--<a href="#" class="active">Dashboard</a>--></h2>
<div id="main">
  <form action="" class="jNice">
    <h3><a href="myprojects.php" style="text-decoration:none; color:inherit;">My Project</a></h3>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <th width="160" id="tbl-th">Project Name</th>
        <th width="200" id="tbl-th">Progress</th>
        <th width="111" id="tbl-th">Days Left</th>
        <th id="operation-row">Status</th>
      </tr>
      <?php 
							$cur_user=$_SESSION['uname'];
							$cur_dev="select designation FROM userinfo_tbl where username='".$cur_user."'";
							$res_dev=mysql_query($cur_dev);
							$row_dev=mysql_fetch_array($res_dev);
							$des=$row_dev['designation'];
							if($des=='pm')
							{
							$prj_sel="SELECT * FROM project_detail_tbl";
							}
							else
							{
							$prj_sel="SELECT * FROM `project_detail_tbl` WHERE developers REGEXP '".$cur_user."'";
							}
							$res=mysql_query($prj_sel);
							while($row=mysql_fetch_array($res))
							{
							?>
      <tr>
        <td><?php echo $row['project_name'];?></td>
        <td><?php echo $row['progress'];?>%</td>
        <td><?php $f_date=date('d',strtotime($row['start_date']));$diff=abs($cur_date - $f_date); echo $diff;?></td>
        <td class="action">
        <!--
        <a href="#" class="view">View</a>-->
        <?php if($dg_user=='pm'){?>
        <a href="#" class="edit"><?php echo $row['status'];?></a>
        <!--<a href="#" class="delete">Delete</a>-->
        <?php }?>
        </td>
      </tr>
      <?php 
							}
							?>
    </table>
    <!--My Tasks-->
    <h3><a href="mytask.php" style="text-decoration:none; color:inherit;" >Tasks</a></h3>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <th width="160" id="tbl-th">Task </th>
        <th width="200" id="tbl-th">Project</th>
        <th width="200" id="tbl-th">Given To</th>
        <th width="111" id="tbl-th">Days Left</th>
        <!--<th id="operation-row">Operations</th>-->
      </tr>
      <?php 
				$prj_sel="SELECT * FROM task_tbl where raised_by='".$cur_user."'";
				$res=mysql_query($prj_sel);
				while($row=mysql_fetch_array($res))
				{
				?>
      <tr>
        <td><?php echo $row['task_title'];?></td>
        <td><?php echo $row['project_name'];?></td>
        <td><?php echo $row['given_to'];?></td>
        <td><?php $f_date=date('d',strtotime($row['task_date']));$diff=abs($cur_date - $f_date); echo $diff;?></td>
        <!--
        <td class="action">
        <a href="#" class="view">View</a>
        <?php //if($dg_user=='pm'){?>
        <a href="#" class="edit">Edit</a>
        <a href="#" class="delete">Delete</a>
        <?php //}?>
        -->
        </td>
      </tr>
      <?php 
							}
							?>
    </table>
    <!--End My Tasks--> 
    <!--My Messages-->
    <h3><a href="messages.php" style="text-decoration:none; color:inherit;">Messages</a></h3>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <th width="160" id="tbl-th">Message</th>
        <th width="200" id="tbl-th">Sent By</th>
        <th width="200" id="tbl-th">Created</th>
        <!--<th id="operation-row">Operations</th>-->
      </tr>
      <?php 
				$prj_sel="SELECT * FROM message_tbl where receiver='".$f_id."'";
				$res=mysql_query($prj_sel);
				while($row=mysql_fetch_array($res))
				{
				?>
      <tr>
        <td><?php echo $row['message_subject'];?></td>
        <td><?php echo $row['sent_by'];?></td>
        <td><?php echo $row['created'];?></td>
        <!--<td class="action"><a href="#" class="view">View</a>
        <?php //if($dg_user=='pm'){?>
        <a href="#" class="edit">Edit</a>
        <a href="#" class="delete">Delete</a>
        <?php //}?>
        </td>
        -->
      </tr>
      <?php 
							}
							?>
    </table>
    <!--end my messages-->
  </form>
</div>
<!-- // #main -->
<div class="clear"></div>
</div>
<!-- // #container -->
</div>
<!-- // #containerHolder -->
<p id="footer">&copy; Copyright by viaviweb 2013 <a href="http://www.viaviweb.com">viaviweb</a></p>
</div>
<!-- // #wrapper -->
</body>
</html>