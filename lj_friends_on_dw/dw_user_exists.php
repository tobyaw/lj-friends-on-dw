<?php
header("Content-Type: text/plain");

if (isset($_GET['user']))
{
	$user = $_GET['user'];
	$page = file_get_contents("http://users.dreamwidth.org/$user");

	if (preg_match('/^<h1>Unknown User/', $page))
	{
		print "no\n";
	}
	else
	{
		print "yes\n";
	}
}
?>
