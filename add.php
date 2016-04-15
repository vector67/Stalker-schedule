<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("dbconnect.php");
include("hits.php");
$s = 'selected="selected"';
/*
 * This is the "official" OO way to do it,
 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
 */
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
$success = 1;	
if(isset($_POST['add'])&&$_POST['add']=="true"){
	$times = $_POST['time'].":30:00,".($_POST['time']+1).":30:00";
	$times = explode(",",$times);
	$starttime = $times[0];
	$endtime = $times[1];
	$query = "select * from times where day=".mysql_escape_string($_POST['day'])." and starttime='$starttime' and endtime='$endtime'";
	if($result = $mysqli->query($query)){
		$tid = ($result->fetch_object()->id);
		echo "success";
	} else {
		echo "failure";
	}	
	$query = "INSERT INTO `timepeople` (`tid` ,`pid`) VALUES ('".$tid."',  '".mysql_escape_string($_POST['pid'])."')";
	if($mysqli->query($query)){
		$success = 2;
	} else {
		$success = 3;
	}
}
$day = (isset($_POST['day'])?$_POST['day']:0);
$pid = (isset($_POST['pid'])?$_POST['pid']:0);
$time = (isset($_POST['time'])?$_POST['time']:0);
$query = 'select * from people';

$result = $mysqli->query($query);
$rows= array();
if($result){
     // Cycle through results
    while ($row = $result->fetch_object()){
        $rows[] = $row;
    }
    // Free result set
    $result->close();
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="addstyle.css?v=<?php echo time();?>" />
</head>
<body> 
<div class = "container">
	<div class="heading">
		<h1>Add free time!</h1>
		<p>Use the form below to add you free times, just select the time slot and name from the list and then click "Add free time"</p>
		<?php echo (($success==2)?"<p style='color:#CCFFCC;'>Successfully added.</p>":(($success==3)?"<p style='color:#FFCCCC;'>Was not successfully added.</p>":""));?>
	</div>
	<div class="formcontainer">
		<form method="post" action="#">
			<select name = "day" class="day">
				<option value ="0" <?php echo ($day==0)?$s:''?>>Monday</option>
				<option value ="1" <?php echo ($day==1)?$s:''?>>Tuesday</option>
				<option value ="2" <?php echo ($day==2)?$s:''?>>Wednesday</option>
				<option value ="3" <?php echo ($day==3)?$s:''?>>Thursday</option>
				<option value ="4" <?php echo ($day==4)?$s:''?>>Friday</option>
			</select><br/>
			<select name="time" class="time">
				<option value="7" <?php echo ($time=="7")?$s:''?>>7:30-8:20</option>
				<option value="8" <?php echo ($time=="8")?$s:''?>>8:30-9:20</option>
				<option value="9" <?php echo ($time=="9")?$s:''?>>9:30-10:20</option>
				<option value="10" <?php echo ($time=="10")?$s:''?>>10:30-11:20</option>
				<option value="11" <?php echo ($time=="11")?$s:''?>>11:30-12:20</option>
				<option value="12" <?php echo ($time=="12")?$s:''?>>12:30-1:20</option>
				<option value="13" <?php echo ($time=="13")?$s:''?>>1:30-2:20</option>
				<option value="14" <?php echo ($time=="14")?$s:''?>>2:30-3:20</option>
				<option value="15" <?php echo ($time=="15")?$s:''?>>3:30-4:20</option>
				<option value="16" <?php echo ($time=="16")?$s:''?>>4:30-5:20</option>
				<option value="17" <?php echo ($time=="17")?$s:''?>>5:30-6:20</option>
			</select><br/>
			<select name="pid" class="name">
				<?php
				foreach($rows as $row){
					$id = $row->id;
					$name = $row->name;
					echo "<option value='$id' ".(($pid==$id)?$s:'').">$name</option>";
				}
				?>
			</select><br>
			<input type="hidden" name="add" value="true" />
			<input type="submit" class="button" value="Add free time"/>
		</form>
	</div>
	<?php echo "test"; ?>
</div>
</body>
