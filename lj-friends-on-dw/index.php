<?php
if ($_GET['auser'])
{
	$found=null;
	$friend=$_GET['auser'];
	$livejournal_friend = livejournal_user($friend);

/*
 CREATE TABLE names(username VARCHAR(32) UNIQUE,found BIT,time TIMESTAMP);
 */
	$cache=new mysqli('p:127.0.0.1','lj2dw','UW6C1nCBPk','dwcache');
	if (!$cache->connect_error) {
		$r=$cache->query("SELECT found FROM names WHERE username='".$cache->real_escape_string($friend)."'");
		if ($r!==FALSE) {
			$r=$r->fetch_row();
			if ($r!==NULL) {
				$found=$r[0]==1;
			}
		}
	}
	print "$livejournal_friend ";

	if ($found===null) {
		$found=(!preg_match('/^<h1>Unknown User/', @file_get_contents("http://users.dreamwidth.org/$friend")));
	}

	if (!$found)
	{
		# Doesn't exist YET? Might change soon...
		if (!$cache->connect_error) {
			$cache->query("INSERT INTO names (username,found) VALUES ('".$cache->real_escape_string($friend)."',0)");
		}
		$expires = 60*60;
		header("Pragma: public");
		header("Cache-Control: public, maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
		print "is not found on Dreamwidth.";
	}
	else
	{
		# Once an account exists, it's likely to stay for two weeks or more
		if (!$cache->connect_error) {
			$cache->query("INSERT INTO names (username,found) VALUES ('".$cache->real_escape_string($friend)."',1)");
		}
		$expires = 60*60*24*14;
		header("Pragma: public");
		header("Cache-Control: public, maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
		$dreamwidth_user = dreamwidth_user($friend);
		print "may be $dreamwidth_user on Dreamwidth.";
	}

	print "\n";
	exit();
}

$user = isset($_GET['user']) ? $_GET['user'] : '';

function livejournal_user($user)
{
        $user_url = str_replace("_", "-", $user);
        return "<span><a href='http://" . $user_url . ".livejournal.com/profile'><img class='avatar' src='http://l-stat.livejournal.com/img/userinfo.gif' alt='[info]' /></a><a href='http://" . $user_url . ".livejournal.com/'><b>" . $user . "</b></a></span>";
}

function dreamwidth_user($user)
{
        $user_url = str_replace("_", "-", $user);
        return "<span><a href='http://" . $user_url . ".dreamwidth.org/profile'><img class='avatar' src='http://s.dreamwidth.org/img/silk/identity/user.png' alt='[info]' /></a><a href='http://" . $user_url . ".dreamwidth.org/'><b>" . $user . "</b></a></span>";
}

$author_toby = dreamwidth_user('tobyaw');
$author_james = dreamwidth_user('deadnode');
echo '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
	<title>LiveJournal friends on Dreamwidth</title>
	<style type="text/css">
	body
	{
		margin-right: auto;
		margin-left: auto;
		width: 90%;
		padding: 8px;
		color: black;
		background-color: white;
		border: 0px;
		margin: 0px;
		text-align: left;
		font-size: 18px;
		font-family: "Myriad Pro", Myriad, Helvetica, sans-serif;
	}

	img.avatar {
		width: 17px;
		height: 17px;
		vertical-align: bottom;
		border: 0;
		padding-right: 1px;
	}
	</style>
	<script src="http://www.google.com/jsapi" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
	google.load("jquery", "1.4");
	google.setOnLoadCallback(function() {
		// Iterate over friends list, populating via AJAX
		$('#friendslist li').each(function(){
			var $u=$(this).attr('title');
			$(this).load('./?auser='+$u);
		});
	});
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-6686527-6']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	//]]>
	</script>
</head>
<body>
	<div id="page">
		<h1>LiveJournal friends on Dreamwidth</h1>
		<p>Enter a LiveJournal username to check. This will fetch a list of your LiveJournal friends, and will then check to see if Dreamwidth accounts with the same names exist. (Note: even if a Dreamwidth username exists, it is not necessarily the same person as the LiveJournal username!) Originally written by <?php print $author_toby;?> with input and AJAX/caching enhancement from <?php print $author_james;?>.</p>
		<form action="./" method="get">
			<p>
				LiveJournal username:
				<input name="user" type="text" value="<?php print $user;?>"/>
				<input type="submit" value="Check"/>
			</p>
		</form>

		<?php
	if ($user != "")
	{
		print "<hr/>\n";

		$friends = array();

		foreach (explode("\n", file_get_contents("http://www.livejournal.com/misc/fdata.bml?user=$user")) as $row)
		{
			$row = trim($row);
			if (preg_match('/^<|>/', $row))
			{
				$friends[]=substr($row,2);
			}
		}

		$friends = array_unique($friends);
		sort($friends);

		$livejournal_user = livejournal_user($user);

		if (count($friends) > 0)
		{
			print "<p>LiveJournal friends of $livejournal_user:</p>\n";
			print "<ul id='friendslist'>\n";

			foreach ($friends as $friend)
			{
				echo "<li title='".htmlentities($friend)."'>".htmlentities($friend)."</li>\n";
			}
			print "</ul>\n";
		}
		else
		{
			print "<p><em>No LiveJournal friends were found for $livejournal_user.</em></p>\n";
		}
	}
	?>
	<hr/>
	<p>If you want to leave a comment about this page, do so at my post on the Dreamwidth LiveJournal community <a href="http://community.livejournal.com/dreamwidth/20074.html">http://community.livejournal.com/dreamwidth/20074.html</a>. The PHP source code for this page is available at <a href="http://github.com/filmgold/dw-tools">http://github.com/filmgold/dw-tools</a>.</p>
</div>
</body>
</html>