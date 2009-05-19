<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="../dw-tools.css"/>
<script language="javascript" type="text/javascript" src="lj-friends-on-dw.js"></script>
<title>LJ friends on DW</title>
</head>

<body>

<div id="page">
<h1>LiveJournal friends on Dreamwidth</h1>

<p>Enter a LiveJournal username to check. This will fetch a list of your LJ friends, and will then check to see if Dreamwidth accounts with the same names exist. (Note: the names may match, but they may not be the same user!)</p>
<form id="ui" onsubmit="lj_friends_on_dw(); return false;">
<p>
	<input id="ui-text" type="text"/>
	<input id="ui-button" type="submit" ="Check"/>
</p>
</form>

<table id="results">
<tr><th>LiveJournal friends</th><th>Dreamwidth account with same username</th></tr>
</table>

<p id="no-results"><em>No LJ friends were found for that username.</em></p>

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
