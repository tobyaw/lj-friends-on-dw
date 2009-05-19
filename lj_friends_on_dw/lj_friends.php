<?php
header("Content-Type: text/plain");

if (isset($_GET['user']))
{
	$user = $_GET['user'];
	$friends = array();
	$page = file_get_contents("http://www.livejournal.com/misc/fdata.bml?user=$user");
	
	foreach (explode("\n", $page) as $row)
	{
		$row = trim($row);
		if (preg_match('/^<|>/', $row))
		{
			array_push($friends, substr($row, 2));
		}
	}
	
	$friends = array_unique($friends);
	sort($friends);
	
	foreach ($friends as $friend)
	{
			print "$friend\n";
	}
}
?>
