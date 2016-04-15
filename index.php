<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("dbconnect.php");
include("hits.php");

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

$query = '
Select * from (
SELECT  * 
FROM timepeople
JOIN times ON timepeople.tid = times.id
) AS  `t` 
JOIN people ON t.pid = people.id
WHERE t.day = WEEKDAY( NOW(  )  ) 
AND t.starttime < TIME( CONVERT_TZ( NOW(  ) ,  "+00:00",  "+9:00"  )  )
AND t.endtime > TIME( CONVERT_TZ( NOW(  ) ,  "+00:00",  "+9:00"  )  )';

$result = $mysqli->query($query);
$user_arr = array();
if($result){
     // Cycle through results
    while ($row = $result->fetch_object()){
        $user_arr[] = $row;
    }
    // Free result set
    $result->close();
}
$names = array();
foreach($user_arr as $row){
	$names[] = $row->name;
}
/*
$names[] = "Claudine";

$names[] = "Sonet";

$names[] = "Nicholas";
$names[] = "Tamara";
$names[] = "Caleb";*/

$mysqli->close();
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time();?>" />
</head>
<body>
<?php
if(count($names)==0){
	?> <div class="sadheadingcontainer" > <h1 class="sadheading">No one is free :(</h1></div> <?php
} else {
	?>
	<h1 class="happyheading">
	These people are free:
	</h1>
	<div class = "container">
	<?php
	foreach($names as $value){?>
		<p class="names">
			<?php echo $value;?>
		</p>
		<?php 
	}
	 ?>
	</div>
	<?php
}
?>
</body>
</html>