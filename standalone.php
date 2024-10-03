<?php

$filename = 'listing.txt';
chdir(dirname(__FILE__));

//include("include2.php");

function get_video($filter) {
  $line = exec("cat listing2.txt | grep \"avi\|mp4\|mkv\" | grep $filter | shuf -n 1");
  $filename = substr($line, 2);

  // Set file path.
  error_log($filename);
  return $filename;
}

// Default programs for 1 hour.
$programs = [];
$filters = [
  'Commercial',
  'Aqua\ Teen',
  'Commercial', 
  'Aqua\ Teen',
  'Commercial', 
  'Saturday',
  'Commercial', 
  'Saturday',
  'Commercial', 
  'Sunrise',
  'Commercial', 
  'Sunrise',
];

$hour = date('G');
if ($hour >= 21) { // 9-midnight
  $filters = [
    'Commercial',
    'Aqua\ Teen',
    'Commercial',
    'Aqua\ Teen',
  ];
}
elseif ($hour >= 18) { // 6-9pm
  $filters = [
    'Commercial',
    'Tom\ and\ Jerry',
    'Commercial',
    'Tom\ and\ Jerry',
  ];
}
elseif ($hour >= 16) { // 4-6pm
  $filters = [
    'Commercial',
    'Kids\ In\ The\ Hall',
    'Commercial',
    'Kids\ In\ The\ Hall',
  ];
}
elseif ($hour >= 14) { // 2-4pm
  $filters = [
    'Commercial',
//    'Kids\ In\ The\ Hall',
    'Friends',
    'Commercial',
    'Seinfeld',
    'Commercial',

  ];
}
elseif ($hour >= 10) { // 10am-2pm
  $filters = [
    'Commercial',
    'IT\ Crowd',
    'Commercial',
    'IT\ Crowd',
  ];
}
elseif ($hour >= 4) { //4am-10am
  $filters = [
    'Commercial',
    'Sunrise\ Earth',
    'Commercial',
    'Sunrise\ Earth',
  ];
}
elseif ($hour >= 0) {
  $filters = [
    'Commercial',
    'South\ Park',
    'Commercial',
    'South\ Park',
  ];
}


// Generate M3U.

header('Content-Type: audio/x-mpegurl');
header('Content-Disposition: attachment; filename="playlist.m3u"');

/**
 * #EXTM3U
 * #EXTINF:721,Aqua Teen Hunger Force - Rabbot
 * file:///V:/TV/Aqua%20Teen%20Hunger%20Force/Season%201/01%20Rabbot.mp4
 */

print "#EXTM3U\r\n";
foreach ($filters as $filter) {
  $program = get_video($filter);
  error_log($program);

  $videodir = "/mnt/8tb/video/";
  $file = $videodir . $program;
  $command = 'mediainfo --Inform="Video;%Duration%" "' . $file . '"';
  error_log($command);
  $length = exec($command);
  $length = intval($length / 1000);

//  $length = 721;
  print "#EXTINF:$length,$filter\r\n";
  print "file:///V:/" . rawurlencode($program) . "\r\n";
}
exit;
