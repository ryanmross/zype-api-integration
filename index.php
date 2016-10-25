<?php
	
ob_start();	

header('Content-Type: application/rss+xml; charset=utf-8');
date_default_timezone_set('America/Los_Angeles');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require __DIR__ . '/vendor/autoload.php';

use \Curl\Curl;

$admin_key = getenv('ADMIN_KEY');
$read_key = getenv('READ_KEY');
/* $ini_array = parse_ini_file("../api_config.ini");

if (!$ini_array) {
	print 'Could not find api_config.ini file.';
	die();
}
$admin_key = $ini_array['admin_key'];
$read_key = $ini_array['read_key'];

*/

// global variables
$playlist_id = '57928c6ee7b34c2c0a000006';
$videos_per_page = 100;
$key = '';

parse_str($_SERVER['QUERY_STRING']);

if ($key!= 'havockey'){
	die('Invalid Key');
}

$playlist = new Curl();
$playlist->get('https://api.zype.com/playlists/' . $playlist_id, array(
	'api_key' => $read_key,
	'per_page' => 10
));

$playlist->close();

if ($playlist->error) {
    echo 'Error: ' . $playlist->errorCode . ': ' . $playlist->errorMessage;
} else {
    //echo 'Response:' . "\n";
    //print_r($playlist->response->response->title);
}


	// This is the age rating.
	/*
	<channel><item><age_rating> number values:
	【Movies】1=NR(also for TV and short form)、2=G、3=PG、4=PG13、5=R-、 6=NC17
	【Variety Show】7=TY-Y、8=TV-Y7、9=TV-Y7-FV、10=TV-G、11=TV-PG、12=TV-PG-D、13=TV-14, 14=TV-MA
	*/
	$age_rating = "13";



	// This is the language
	$language = "en";

	//<video_type> number values: 1-TV series, 2-Movie, 3-Animation, 4-Variety, 5-Sports, 6-Education, 7-Music, 8-News, 9-Automotive, 10-Finance, 11-Game, 12-Lifestyle, 16-Documentary.
	if ($videotype) {
		$video_type = $videotype;
	} else {
		$video_type = 7;
	}
	
	// This is the category
	if ($video_type == "5"){
		$category = "Sports";
		
	} else {
		$category = "Music";
	}
	

	$screen_year = date("Y");
	$link = 'http://havoc.tv';
	
	$playlist_keywords = '';

	
	// Customize the code below to return the videos and fields for your feed;	
	print('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
	print "\n";
	print('<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">');
	print "\n";
	print('	<channel>');
	print "\n";
	print('		<guid>'. $playlist->response->response->_id . '</guid>');
	print "\n";
	print('		<title>'. htmlspecialchars($playlist->response->response->title, ENT_QUOTES, "UTF-8") . '</title>');
	print "\n";
	print('		<link>'. $link . '</link>');
	print "\n";
	print('		<description>' . htmlspecialchars($playlist->response->response->description, ENT_QUOTES, "UTF-8") . '</description>');
	print "\n";
	print('		<video_type>'. $video_type . '</video_type>');
	print "\n";
	print('		<category>'. $category . '</category>');
	print "\n";
	print('		<screen_year>'. $screen_year . '</screen_year>');
	print "\n";
	print('		<language>'. $language . '</language>');
	print "\n";
	print('		<area>US</area>');
	print "\n";
	print('		<age_rating>'. $age_rating . '</age_rating>');
	print "\n";
	print('		<keywords>');
	foreach($playlist->response->response->_keywords as $tags)
	{
		$playlist_keywords = $playlist_keywords . ($playlist_keywords == "" ? "" : ",") . htmlspecialchars($tags, ENT_QUOTES, "UTF-8");
	}
	print $playlist_keywords;
	print ('</keywords>');
	print "\n";


	//thumbnails
	foreach ($playlist->response->response->thumbnails as $thumbnail) {

		print('		<media:thumbnail');
		print ' url="' . $thumbnail->url .'"';
		print ' height="' . $thumbnail->height .'"';
		print ' width="' . $thumbnail->width .'"';
		print(' />');
		print "\n";	
	}





$videos = new Curl();
$videos->get('https://api.zype.com/playlists/' . $playlist_id . '/videos', array(
	'api_key' => $read_key,
	'per_page' => $videos_per_page
));


$videos->close();

if ($videos->error) {
    echo 'Error: ' . $videos->errorCode . ': ' . $videos->errorMessage;
} else {
    //echo 'Response:' . "\n";
    //print_r($videos->response->response);
}




	foreach($videos->response->response as $items)
	{
		
		$video = new Curl();
		$video->get('https://api.zype.com/videos/' . $items->_id . '/download', array(
			'api_key' => $admin_key
		));
		
		
		$video->close();
		
		if ($video->error) {
		    echo 'Error: ' . $video->errorCode . ': ' . $video->errorMessage;
		} else {
		    //echo 'Response:' . "\n";
		    //print_r($video->response->response);
		}
		
		




		// print mrss

	    print('		<item>');	
	    print "\n";

		//guid
		print('			<guid>');
		print ('' . $items->_id);
		print('</guid>');	
	    print "\n";		

		//type
		print('			<type>20</type>');
	    print "\n";	

		//media:group
		print('			<media:group>');
	    print "\n";

		//media:title
		print('				<media:title>');
		print (htmlspecialchars($items->title, ENT_QUOTES, "UTF-8"));
		print('</media:title>');	
	    print "\n";

		//media:description
		print('				<media:description>');
		print (htmlspecialchars($items->description, ENT_QUOTES, "UTF-8"));
		print('</media:description>');	
	    print "\n";


		//media:content
		print('				<media:content ');
		print 'url="' . $video->response->response->url . '" ';
		print('/>');	
	    print "\n";

		//media:genre
		print('				<media:genre>');
		foreach($items->categories as $category)
		{
			if($category->title == "Videos") {
				print (htmlspecialchars($category->value[0], ENT_QUOTES, "UTF-8"));
			}
		}

		print('</media:genre>');	
	    print "\n";

		//media:screen_year
		print('				<media:screen_year>');
		foreach($items->categories as $category)
		{
			if($category->title == "Year") {
				if ($category->value[0]) {
					$video_year = (htmlspecialchars($category->value[0], ENT_QUOTES, "UTF-8"));
				}
			}
		}

		print ($video_year == '' ? $screen_year : $video_year); 

		print('</media:screen_year>');	
	    print "\n";

		//media:region
		print('				<media:region>');
		print 'US';
		print('</media:region>');	
	    print "\n";

		//media:age_rating
		print('				<media:age_rating>');
		foreach($items->categories as $category)
		{
			if($category->title == "Rating") {
				
				switch ($category->value[0]) {
				    case 'TV-Y':
				        print '7';
				        break;
				    case 'TV-G':
				        print '10';
				        break;
				    case 'TV-PG':
				        print '11';
				        break;
				    case 'TV-14':
				        print '13';
				        break;
				    case 'TV-MA':
				        print '14';
				        break;
				    default:
				        print '11';
				}

			}
		}
		print('</media:age_rating>');	
	    print "\n";


		//media:keywords
		print('				<media:keywords>');
		$keywords = "";
		foreach($items->keywords as $tags)
		{
			$explodedTags = explode(PHP_EOL, $tags);
		if (count($explodedTags) > 1) {
			foreach($explodedTags as $tag) {
				$keywords = $keywords . ($keywords == "" ? "" : ",") . htmlspecialchars(trim($tag), ENT_QUOTES, "UTF-8");
			}
		} else {
			$keywords = $keywords . ($keywords == "" ? "" : ",") . htmlspecialchars(trim($tags), ENT_QUOTES, "UTF-8");
		}
			
		}
		print $keywords;
		print('</media:keywords>');	
	    print "\n";


		//thumbnails
		foreach ($items->thumbnails as $thumbnail) {

			print('				<media:thumbnail');
			print ' url="' . $thumbnail->url .'"';
			print ' height="' . $thumbnail->height .'"';
			print ' width="' . $thumbnail->width .'"';
			print(' />');
			print "\n";	
		}

		// start and end dates

		print '				<media:copyrightstart>2016-09-27 23:37:43 UTC</media:copyrightstart>';
	    print "\n";	
		print '				<media:copyrightend>2099-12-31 23:23:59 UTC</media:copyrightend>';
	    print "\n";	

		// end media:group
		print('			</media:group>');	
	    print "\n";	


	    print('		</item>');
	    print "\n";


	}

	print('	</channel>');
	print "\n";
print('</rss>');

ob_end_flush(); 

?>