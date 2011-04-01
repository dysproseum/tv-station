
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

<script type="text/javascript">
//for files, post their hrefs
function add_file(obj) {
	//alert(obj.href);
	$.ajax({
	  url: "/util.php",
	  async: false,
	  data: {'href': obj.id}
	});
}

</script>
</head>
<body>

<?php
include("/var/www/html/dysproseum.com/include/dbfunc.php");
dbConnect("tv_station");
$base_dir = dirname(__FILE__);
$dir = '';

if(isset($_REQUEST['dir']) && ($_REQUEST['dir'] != '')) 
   $dir = $_REQUEST['dir'];

$dir_to_open = $base_dir . $dir;
echo '<h1><a href="/browse.php">video</a>' . $dir . "</h1>";

$handle = opendir($dir_to_open);
if($handle) {
	$array = scandir($dir_to_open);
	foreach($array as $item) {
		if(substr($item, 0, 1) == ".") continue;
		if(is_dir($dir_to_open . "/" . $item)) {
			echo '<a class="dir_to_browse" href="/browse.php?dir='.urlencode($dir . "/" . $item).'">' . $item .'</a><br>';
		} else {
			echo $item . ' <a class="file_to_add" onclick="add_file(this); return false;" id="'.$dir . "/" . $item.'" href="#">Add to queue</a><br>';
		}
	}
} else {
  die("couldnt open $base_dir");
}

echo "<h1>Queue:</h1>";

$sql = "SELECT path FROM queue WHERE played = 0 ORDER BY id ASC";
$results = mysql_query($sql);
if($results) {
	while($row = mysql_fetch_array($results)) {
		echo $row['path'] . "<br>";
	}
} else {
	die(mysql_error());
}


echo "<h1>Last Played:</h1>";

$sql = "SELECT path FROM queue WHERE played = 1 ORDER BY id DESC LIMIT 10";
$results = mysql_query($sql);
if($results) {
	while($row = mysql_fetch_array($results)) {
		echo $row['path'] . "<br>";
	}
} else {
	die(mysql_error());
}

?>
</body>
</html>
