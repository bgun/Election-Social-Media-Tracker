<html>
<head>
<title>Campaign 2010 - Update</title>
<style><!--
* {
	margin: 0;
	padding: 0;
}
body {
	font-family: Arial, sans-serif;
	font-size: 11px;
	margin: 1em;
}
p {
	margin: 1em 0;
}
#metrics {
	border-collapse: collapse;
	font-size: 11px;
}
#metrics td, #metrics th {
	border: 1px solid #AAA;
	padding: 2px 6px;
}

--></style>
</head>

<body>

<table id="metrics">
	<tr>
		<th>Name</th>
		<th>Facebook ID</th>
		<th>Fans</th>
		<th>Twitter ID</th>
		<th>Followers</th>
	</tr>
	<?php
	$conn = mysql_connect('mysql305.hostexcellence.com', 'bengund_elect', 'elect2010g7B') or die('Error connecting to MySQL');
	mysql_select_db('bengund_elections');
	if(isset($_GET["rt"])) {
		switch($_GET["rt"]) {
			case "senate":
				// Get all Senate candidates, in order of oldest Twitter metrics retrieved.
				// Twitter rate limit is 150 per hour and we want to ensure that we take
				// care of the candidates with the oldest data first.
				$query  = "SELECT *";
				$query .= ",(SELECT RetrieveDate FROM Metrics M WHERE M.CandidateID = C.CandidateID AND MetricType = 'TW' ORDER BY RetrieveDate DESC LIMIT 1) AS LastTW";
				$query .= " FROM Candidates C LEFT JOIN Races R ON R.RaceID = C.RaceID WHERE RaceType LIKE 'Senate%' ORDER BY LastTW";
				break;
			case "governor":
				// Same thing, but with Governor candidates.
				$query  = "SELECT *";
				$query .= ",(SELECT RetrieveDate FROM Metrics M WHERE M.CandidateID = C.CandidateID AND MetricType = 'TW' ORDER BY RetrieveDate DESC LIMIT 1) AS LastTW";
				$query .= " FROM Candidates C LEFT JOIN Races R ON R.RaceID = C.RaceID WHERE RaceType = 'Governor' ORDER BY LastTW";
				break;
		}
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)) {
			$today = date('Y-m-d H:i:s');
		
			print "<tr>";
			print "<td>" . $row['LastName'] . ", " . $row['FirstName'] . "</td>";
			
			// Facebook fans
			$fbjson = file_get_contents("https://graph.facebook.com/" . $row['FacebookID']);
			$obj = json_decode($fbjson);
			$fans = $obj->{'fan_count'};
			print "<td>" . $row['FacebookID'] .  "</td>";
			if(!empty($fans)) {
				print "<td>" . $obj->{'fan_count'} . "</td>";
				$tempstr  = "INSERT INTO Metrics (RetrieveDate, CandidateID, MetricType, MetricValue) ";
				$tempstr .= "VALUES ('".$today."',".$row['CandidateID'].",'FB',".$obj->{'fan_count'}.")";
				mysql_query($tempstr);
			} else {
				print "<td>N/A</td>";
			}
			
			// Twitter followers and statuses
			$twjson = file_get_contents("http://api.twitter.com/1/users/show/" . $row['TwitterID'] . ".json");
			$obj = json_decode($twjson);
			$followers = $obj->{'followers_count'};
			print "<td>" . $row['TwitterID'] . "</td>";
			if(!empty($followers)) {
				print "<td>" . $followers . "</td>";
				
				$tempstr  = "INSERT INTO Metrics (RetrieveDate, CandidateID, MetricType, MetricValue) ";
				$tempstr .= "VALUES ('".$today."',".$row['CandidateID'].",'TW',".$obj->{'followers_count'}.")";
				mysql_query($tempstr);
				
				$tempstr  = "INSERT INTO Metrics (RetrieveDate, CandidateID, MetricType, MetricValue) ";
				$tempstr .= "VALUES ('".$today."',".$row['CandidateID'].",'TS',".$obj->{'statuses_count'}.")";
				mysql_query($tempstr);
			} else {
				print "<td>N/A</td>";
			}
			print "</tr>";
		}
		print "</table><p>Record count: ".mysql_num_rows($result).". Update completed at ".$today."</p>";
	}
	else {
		print "</table><p>Error - no paramaters</p>";
	}
	mysql_close($conn);
	?>
</body>
</html>