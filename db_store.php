<?php
session_start();
//This file contains the PHP Database Storage.
include('include/db_config.php');

if(isset($_POST['login']))
{
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	
	$_SESSION['uname']=$username;
	
	$sel_user="SELECT * FROM userinfo_tbl WHERE username='".$username."' AND password='".$password."'";
	$res=mysql_query($sel_user);
	$row=mysql_num_rows($res);
	if($row==1)
	{
		header('location:index.php');
		
	}
	else
	{
		echo "Invalid username and password information";
	}
}
//For Add New User
if(isset($_POST['add_user']))
{
		//For Upload Image
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["frm_image"]["name"]);
	$extension = end($temp);
	if ((($_FILES["frm_image"]["type"] == "image/gif")
	|| ($_FILES["frm_image"]["type"] == "image/jpeg")
	|| ($_FILES["frm_image"]["type"] == "image/jpg")
	|| ($_FILES["frm_image"]["type"] == "image/pjpeg")
	|| ($_FILES["frm_image"]["type"] == "image/x-png")
	|| ($_FILES["frm_image"]["type"] == "image/png"))
	&& ($_FILES["frm_image"]["size"] < 20000)
	&& in_array($extension, $allowedExts))
	  {
	  	if ($_FILES["frm_image"]["error"] > 0)
		{
		echo "Error: " . $_FILES["frm_image"]["error"] . "<br>";
		}
	    else
		{
			if (file_exists("images/" . $_FILES["frm_image"]["name"]))
    		{
			      echo $_FILES["frm_image"]["name"] . " already exists. ";
      		}
		    else
      		{
				$dc_root=$_SERVER['DOCUMENT_ROOT'];
				$img_root='/sumeet/pms/images/';
				$target=$dc_root.$img_root;
				$fname=$_FILES["frm_image"]["name"];
		      	move_uploaded_file($_FILES["frm_image"]["tmp_name"],$target.$fname);
			}
		}
	  }
	else
	{
	  echo "Invalid file";
	}
	//DB Entry
	$img_name=$_FILES['frm_image']['name'];
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$q_ins="INSERT INTO userinfo_tbl
	(username, 
	 password, 
	 firstname, 
	 lastname, 
	 gender, 
	 address, 
	 skills, 
	 img_name, 
	 mail_ids, 
	 designation,
	 join_date,
	 city, 
	 state, 
	 country, 
	 mobile_no) 
	 
	 VALUES ('$_POST[frm_username]',
	 '$_POST[frm_password]',
	 '$_POST[frm_firstname]',
	 '$_POST[frm_lastname]',
	 '$_POST[frm_gender]',
	 '$_POST[frm_address]',
	 '$_POST[frm_skills]',
	 '$img_name',
	 '$_POST[frm_email]',
	 '$_POST[frm_designation]',
	 '$newDate',
	 '$_POST[frm_city]',
	 '$_POST[frm_state]',
	 '$_POST[frm_country]',
	 '$_POST[frm_mobileno]')";
	 
	$res_q=mysql_query($q_ins);
	
	if(mysql_error())
	{
		echo mysql_error();
	}
	else
	{
		header('location:admin.php');
	}
	
}
//For Update USER Information
if(isset($_POST['user_upload']))
{
	$f_user_id=$_POST['pass_id'];
	$img_name=$_FILES['frm_image']['name'];
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$update_info="UPDATE `userinfo_tbl` 
	 SET 	
	 `username`='$_POST[frm_username]',
	`password`='$_POST[frm_password]',
	`firstname`='$_POST[frm_firstname]',
	`lastname`='$_POST[frm_lastname]',
	`gender`='$_POST[frm_gender]',
	`address`='$_POST[frm_address]',
	`skills`='$_POST[frm_skills]',
	`img_name`='$img_name',
	`mail_ids`='$_POST[frm_email]',
	`designation`='$_POST[frm_designation]',
	`join_date`='$newDate',
	`city`='$_POST[frm_city]',
	`state`='$_POST[frm_state]',
	`country`='$_POST[frm_country]',
	`mobile_no`='$_POST[frm_mobileno]'
	WHERE user_id='".$f_user_id."'";
	 
	$res_q=mysql_query($update_info);
	
	if(mysql_error())
	{
		echo mysql_error();
		echo "<br/>";
		echo $update_info;
	}
	else
	{
		header('location:admin.php');
	}
}
//For Add New Project
if(isset($_POST['prj_add']))
{
	//Check Project Name 
	$f_prj_nm=$_POST['prj_name'];
	$sql_ck_prj_nm="SELECT * FROM `project_detail_tbl` WHERE `project_name`='".$f_prj_nm."'";
	$run_prj=mysql_query($sql_ck_prj_nm);
	$count=mysql_num_rows($run_prj);
	if($count==1)
	{
		echo "<p id='exist' style='color:red;'>Name Exist</p>";
		exit();
	} 
	$developers=implode(",",$_POST['prj_developers']);
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$endDate=$_POST['prj_end_date'];
	$end_date=date("Y-m-d",strtotime($endDate));
	$sql_ins="INSERT INTO `project_detail_tbl`( 
	`project_name`,
	`description`, 
	`start_date`, 
	`finish_date`,
	`client_name`,
	 `progress`,
	 `developers`) 
	 VALUES(
	'$_POST[prj_name]',
	'$_POST[prj_desc]',
	'$newDate',
	'$end_date',
	'$_POST[prj_client_name]',
	'$_POST[prj_efforts]',
	'$developers')";
	$res=mysql_query($sql_ins);
	if(mysql_error())
	{
		echo mysql_error();
	}
	else
	{
			header('location:myprojects.php');
			echo $developers;
	}
}
//Update Project
if(isset($_POST['prj_update']))
{
	$prj_id=$_POST['pass_id'];
	$developers=implode(",",$_POST['prj_developers']);
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$endDate=$_POST['prj_end_date'];
	$end_date=date("Y-m-d",strtotime($endDate));
	$sql_update="UPDATE `project_detail_tbl` 
	SET `project_name`='$_POST[prj_name]',
	`description`='$_POST[prj_desc]',
	`start_date`='$newDate',
	`finish_date`='$end_date',
	`client_name`='$_POST[prj_client_name]',
	`progress`='$_POST[prj_efforts]',
	`developers`='$developers' WHERE project_id='".$prj_id."'";
	$res=mysql_query($sql_update);
	if(mysql_error())
	{
		echo mysql_error();
	}
	else
	{
		header('location:myprojects.php');
	}
}
//FOR ADD TASK
if(isset($_POST['tsk_add']))
{

	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$developers=implode(",",$_POST['prj_developers']);
		$ins_task="INSERT INTO `task_tbl`(
		`project_name`,
		`task_title`,
		`task_description`,
		`task_date`,
		`given_to`,
		`raised_by`) 
		VALUES (
		'$_POST[prj_name]',
		'$_POST[tsk_title]',
		'$_POST[tsk_desc]',
		'$newDate',
		'$developers',
		'$_POST[raised_by]'
		)";
		$res=mysql_query($ins_task);
	if(mysql_error())
	{
		echo mysql_error();
	}
	else
	{
		header('location:mytask.php');
	}
}
//For Task Update
if(isset($_POST['tsk_update']))
{
	$get_id=$_POST['tid_edit'];
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$developers=implode(",",$_POST['prj_developers']);
	$task_update="UPDATE `task_tbl` 
	SET `project_name`='$_POST[prj_name]',
	`task_title`='$_POST[tsk_title]',
	`task_description`='$_POST[tsk_desc]',
	`task_date`=$newDate,
	`raised_by`='$_POST[raised_by]',
	`given_to`='$developers' 
	WHERE task_id='".$get_id."'";
	$res=mysql_query($task_update);
	if(mysql_error())
	{
		echo mysql_error();
		echo $task_update;
	}
	else
	{
		header('location:mytask.php');
	}
	
}
//For message send
if(isset($_POST['msg_send']))
{
	$dateToday=$_POST['prj_start_date'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	$ins_msg="INSERT INTO `message_tbl`(`message_subject`,`sent_by`,`receiver`, `message`,`tags`,`created`) 
	VALUES (
	'$_POST[msg_subject]',
	'$_POST[from]',
	'$_POST[msg_sender]',
	'$_POST[msg_text]',
	'$_POST[msg_tags]',
	'$newDate')";
	$res=mysql_query($ins_msg);
	if(mysql_error())
	{
		echo mysql_error();
	}
	else
	{
		header('location:messages.php');
	}

}

//FOR LOGOUT
if(isset($_REQUEST['logout']))
{
	header('location:login.html');
	unset($_SESSION['uname']);
}
?>
