<?php

include("/var/www/html/dysproseum.com/include/dbfunc.php");
dbConnect('tv_station');

//$files[] = dirname(__FILE__) ."/Misc/Alien Song.MPEG";
//$files[] = dirname(__FILE__) ."/Misc/PBR_draft_1.avi";
//$files[] = dirname(__FILE__) ."/Misc/lasercats.avi";

function get_file( ) {
	$file = '';
	$sql = "SELECT id, path FROM queue WHERE played = 0 ORDER BY id ASC LIMIT 1";
	$results = mysql_query($sql);
	if(!$results) {
		$file = "/Misc/Alien Song.MPEG"; //bad query
	} else {
		$num = mysql_num_rows($results);
		if($num == 0) {
			//select random
			$file = "/Misc/PBR_draft_1.avi"; //no unplayed videos
		} else {
			while($row = mysql_fetch_array($results)) {
				$file = $row['path'];
				$id = $row['id'];
				$sql = "UPDATE queue SET played = 1 WHERE id = $id";
				$results = mysql_query($sql);
				if(!$results) die("couldnt update playlist");
			}
		}
	}
	
	return $file;
}

$file = dirname(__FILE__) . get_file();
        
	$fp = fopen($file, "rb");
	if($fp) {

$type = exec('file -i '.escapeshellarg($file));
$type = explode(':', $type);
$type = trim($type[1]);
//header('HTTP/1.1 206 Partial Content'); // Allows scanning in a stream.
header('Accept-Ranges: bytes'); // Allows scanning in a stream based on byte count.
header('Content-Type: '.$type); // Launches the correct player.
header('Content-Length: '.filesize($file)); // This allows the player to know the song length or remaining time.
//header('Content-Range: bytes '.$count.'-'.($count + filesize($file))); // This tells the player what byte we're starting with.
		while(!feof($fp)) {
			$data = fread($fp, 8192);
			echo $data;
		}	
		fclose($fp);
	} else {
		echo "couldnt open";
	}
