function get_friends(lj_username)
{
	var friends = new Array();
	var request = new XMLHttpRequest();

	request.open("GET", "lj-friends.php?user=" + lj_username, false);
	request.send(null);

	if (request.status == 200)
	{
		var rows = request.responseText.split("\n");

		for (var row_id in rows)
		{
			var row = rows[row_id];

			if (row != "")
			{
				friends.push(row);
			}
		}
	}

	return friends;
}

function check_friends(friends)
{
	if (friends.length > 0)
	{
		var request = new XMLHttpRequest();
		var table = document.getElementById("results");

		// empty the table
		for (var index = table.rows.length - 1; index > 0; index--)
		{
		    table.deleteRow(index);
		}

		friends.reverse();

		// put the lj friends list into the table
		for (var friend_id in friends)
		{
			var friend = friends[friend_id];

			var row = table.insertRow(1);
			var lj_cell = row.insertCell(0);
			var dw_cell = row.insertCell(1);

			var friend_url = friend.replace(/_/, "-");

			lj_cell.innerHTML = "<span><a href='http://" + friend_url + ".livejournal.com/profile'><img src='http://l-stat.livejournal.com/img/userinfo.gif' alt='[info]' width='17' height='17' style='vertical-align: bottom; border: 0; padding-right: 1px;' /></a><a href='http://" + friend_url + ".livejournal.com/'><b>" + friend + "</b></a></span>";
			dw_cell.innerHTML = "";
			dw_cell.id = "cell-" + friend;
		}

		friends.reverse();

		// for each friend search for a dw page
		for (var friend_id in friends)
		{
			var friend = friends[friend_id];
			inner = "<em>not found</em>";

			request.open("GET", "dw-user-exists.php?user=" + friend, false);
			request.send(null);

			if (request.status == 200)
			{
				// process the response
				if (request.responseText.match(/^yes/))
				{
					var friend_url = friend.replace(/_/, "-");
					inner = "<span><a href='http://" + friend_url + ".dreamwidth.org/profile'><img src='http://s.dreamwidth.org/img/silk/identity/user.png' alt='[info]' width='17' height='17' style='vertical-align: bottom; border: 0; padding-right: 1px;' /></a><a href='http://" + friend_url + ".dreamwidth.org/'><b>" + friend + "</b></a></span>";
				}
			}

			var cell = document.getElementById("cell-" + friend);
			cell.innerHTML = inner;
		}

		// show the table
		document.getElementById("results").style.display = 'block';
	}
	else
	{
		// hide the output
		document.getElementById("no-results").style.display = 'block';
	}
}

function lj_friends_on_dw()
{
	var lj_username = document.getElementById("ui-text").value;
	if (lj_username == '') return;

	// disable the user interface
	document.getElementById("ui-text").disabled = true;
	document.getElementById("ui-button").disabled = true;

	// hide the output
	document.getElementById("results").style.display = 'none';
	document.getElementById("no-results").style.display = 'none';

	// get the list of friends
	var friends = get_friends(lj_username);

	// check the friends to see if the names are on DW
	check_friends(friends);

	// reenable the text box and the submit button
	document.getElementById("ui-text").disabled = false;
	document.getElementById("ui-button").disabled = false;
}
