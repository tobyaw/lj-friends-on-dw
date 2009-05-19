<?php
$user = isset($_GET['user']) ? $_GET['user'] : '';
?><?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="../dw-tools.css"/>
<title>LiveJournal friends on Dreamwidth</title>
</head>

<body onload="lj_friends_on_dw();">

<div id="page">
<h1>LiveJournal friends on Dreamwidth</h1>

<p>Enter a LiveJournal username to check. This will fetch a list of your LJ friends, and will then check to see if Dreamwidth accounts with the same names exist. (Note: even if a Dreamwidth username exists, it doesn’t mean that it is the same person as the LiveJournal username!)</p>
<form id="ui" action="./" method="get">
<p>
	<input name="user" type="text" value="<?php print $user;?>"/>
	<input type="submit" ="Check"/>
</p>
</form>

<?php
function livejournal_user($user)
{
	$user_url = str_replace("_", "-", $user);

	return "<span><a href='http://" . $user_url . ".livejournal.com/profile'><img src='http://l-stat.livejournal.com/img/userinfo.gif' alt='[info]' width='17' height='17' style='vertical-align: bottom; border: 0; padding-right: 1px;' /></a><a href='http://" . $user_url . ".livejournal.com/'><b>" . $user . "</b></a></span>";
}

function dreamwidth_user($user)
{
	$user_url = str_replace("_", "-", $user);

	return "<span><a href='http://" . $user_url . ".dreamwidth.org/profile'><img src='http://s.dreamwidth.org/img/silk/identity/user.png' alt='[info]' width='17' height='17' style='vertical-align: bottom; border: 0; padding-right: 1px;' /></a><a href='http://" . $user_url . ".dreamwidth.org/'><b>" . $user . "</b></a></span>";
}

if ($user != "")
{
	$friends = array();

	foreach (explode("\n", file_get_contents("http://www.livejournal.com/misc/fdata.bml?user=$user")) as $row)
	{
		$row = trim($row);
		if (preg_match('/^<|>/', $row))
		{
			array_push($friends, substr($row, 2));
		}
	}

	$friends = array_unique($friends);
	sort($friends);

	$livejournal_user = livejournal_user($user);

	if (count($friends) > 0)
	{
		print "<p>Friends of LiveJournal user $livejournal_user:</p>\n";
		
		print "<ul>\n";
		foreach ($friends as $friend)
		{
			$livejournal_friend = livejournal_user($friend);
			
			print "<li>LiveJournal user $livejournal_friend ";
			
			if (preg_match('/^<h1>Unknown User/', file_get_contents("http://users.dreamwidth.org/$friend")))
			{
				print "is not found on Dreamwidth.";
			}
			else
			{
				$dreamwidth_user = dreamwidth_user($friend);
				print "may be user $dreamwidth_user on Dreamwidth.";
			}
			
			print "</li>\n";
		}
		print "</ul>\n";
	}
	else
	{
		print "<p><em>No LiveJournal friends were found for $livejournal_user.</em></p>\n";
	}	
}
?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6686527-4");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
