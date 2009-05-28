<?php
ini_set('zlib.output_compression', 0);
ini_set('implicit_flush', 1);
while(ob_get_level()) ob_end_flush();
ob_start();
ob_implicit_flush(true);

$user = isset($_GET['user']) ? $_GET['user'] : '';

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

$author_toby = dreamwidth_user('tobyaw');
$author_james = dreamwidth_user('deadnode');
?><?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta name="verify-v1" content="iHbI1Y0WkkRuoB23hIrxSye+Aeyg3eOyjvPfPkg3Rfg="/>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<title>LiveJournal friends on Dreamwidth</title>
<style type="text/css">
div#page
{
        margin-right: auto;
        margin-left: auto;
        width: 90%;
        padding: 8px;
}

body
{
        color: black;
        background-color: white;
        border: 0px;
        margin: 0px;
        text-align: left;
        font-size: 18px;
        font-family: "Myriad Pro", Myriad, Helvetica, sans-serif;
}
</style>
</head>

<body>

<div id="page">
<h1>LiveJournal friends on Dreamwidth</h1>
<p>Enter a LiveJournal username to check. This will fetch a list of your LiveJournal friends, and will then check to see if Dreamwidth accounts with the same names exist. (Note: even if a Dreamwidth username exists, it is not necessarily the same person as the LiveJournal username!) Written by <?php print $author_toby;?> with input from <?php print $author_james;?>.</p>
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
	ob_flush(); flush();

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
		print "<p>LiveJournal friends of $livejournal_user:</p>\n";
		print "<ul>\n";
		ob_flush(); flush();

		foreach ($friends as $friend)
		{
			$livejournal_friend = livejournal_user($friend);

			print "<li>$livejournal_friend ";

			if (preg_match('/^<h1>Unknown User/', file_get_contents("http://users.dreamwidth.org/$friend")))
			{
				print "is not found on Dreamwidth.";
			}
			else
			{
				$dreamwidth_user = dreamwidth_user($friend);
				print "may be $dreamwidth_user on Dreamwidth.";
			}

			print "</li>\n";
			ob_flush(); flush();
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
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6686527-6");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
