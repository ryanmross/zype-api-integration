<?php
	
//ob_start();	

header('Content-Type: application/rss+xml; charset=utf-8');
date_default_timezone_set('America/Los_Angeles');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

parse_str($_SERVER['QUERY_STRING']);

if ($key!= 'havockey'){
	die('Invalid Key');
}

$playlists = array(
	array('Havoc Subscription Music', '57fd7b83e43f900d1d001f82', 'http://www.havoc.tv/videos/music', '7'),
	array('Havoc Subscription Sports', '57fdbd3035fdb30d14003919', 'http://www.havoc.tv/videos/sports', '5'),
);

print('<opml version="1.0" encoding="UTF-8">');
print "\n";
print('	<head>');
print "\n";
print('		<title>HavocTV</title>');
print "\n";
print('	</head>');
print "\n";
print('	<body>');
print "\n";
print('		<outline title="HavocTV SVOD Playlists" text="HavocTV SVOD Playlists>');

foreach ($playlists as $playlist){
	
	print "\n";
	print('			<outline text="' . $playlist[0] . '"> title="' . $playlist[0] . '" xmlUrl="http://ads.havoc.tv/mrss/leeco/?key=havockey&playlist_id=' . $playlist[1] . '&videotype=' . $playlist[3] . '" htmlUrl="' . $playlist[2] . '" />');
	
}

print "\n";
print('		</outine>');
print "\n";
print('	</body>');
print "\n";
print('</opml>');


//ob_end_flush(); 

?>