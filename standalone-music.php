<?php

//chdir(dirname(__FILE__));

//include("include2.php");

function get_video($filter) {
  $filename = 'listing2.txt';
  $line = exec("cat $filename | grep -v DS_Store | grep $filter | shuf -n 1");
  $filename = substr($line, 2);

  // Set file path.
  error_log($filename);
  return $filename;
}

// Default programs for 1 hour.
$programs = [];
$filters = [
//  'Daniele\ Manoli',
  'Commercial',
  'Misc\/gj',
  'Family\ Guy',
//  'MusicVideosYouTube',
//  'Music\ Video',
];

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
