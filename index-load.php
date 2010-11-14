<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>2010 Elections - Social Predictions</title>
<link href="elections.css" type="text/css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script src="./js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="./js/elections.js" type="text/javascript"></script>
</head>

<body>
<div id="header">
	<ul>
    	<li><a href="#" class="active Senate">Senate</a></li>
        <li><a href="#" class="Governor">Governor</a></li>
    </ul>
</div>

<div id="wrapper">     
    <div id="column-left"> 
        <div class="pad"> 
            <p> 
                Which is a better predictor of a candidate's success: their Facebook fans, or their Twitter followers?
            </p> 
            <h1>
            	What If <span id="predictor">Social Media</span><br />
                Chose the Winners?<br />
            </h1>
            <h3 class="racetype">2010 <span id="racetype">Senate</span> Elections</h3>
            
            <ul id="change-predictor">
            	<li><a href="#" class="Twitter">Twitter</a></li>
                <li><a href="#" class="Facebook">Facebook</a></li>
            </ul>
            <div id="stats">
            	<div class="pad">
                	<p> 
                    	According to <span class="predictor">Twitter</span>,<br /> 
                        &nbsp; &nbsp; &nbsp; &nbsp; out of <span class="number-of-races">0</span> races:
                    </p> 
                	<div class="stats-won stats-won-R">Republicans win <span>0</span></div>
                	<div class="stats-won stats-won-D">Democrats win <span>0</span></div>
                	<div class="stats-won stats-won-I">Independents win <span>0</span></div>
                    <div class="accuracy"><span class="predictor">Twitter</span> chose correctly<br /><span class="times">0</span> times (<span class="percent">0</span>%)</div>
                </div>
            </div>
            
            <div class="credits">
            	<div class="pad">
                	<p>
                    	Created by Ben Gundersen. (<a href="http://www.bengundersen.com">Hire me!</a>)<br />
                        Data updated 2010-Nov-01.<br />
                        <br />
                        <small><a rel="license" href="http://creativecommons.org/licenses/by/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/80x15.png" /></a><br /><a rel="license" href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0</a>.</small>
                    </p>
                </div>
            </div>
        </div>
    </div> 
    <div id="column-right">
        <div class="pad">
        
        <p> 
        	Click on a column heading to sort within a race. The incumbent is shown in <strong>bold</strong>.<br />
            This page will be updated, with winners marked, on Nov. 3.
        </p>            

        <?php
            $conn = mysql_connect('mysql305.hostexcellence.com', 'bengund_elect', 'elect2010g7B') or die('Error connecting to MySQL');
            mysql_select_db('bengund_elections');
            $query  = "SELECT DISTINCT FirstName, LastName, Affiliation, Incumbent, Won, TwitterID, FacebookID, Female, StateFullName, R.RaceType, R.RaceID,";
            $query .= " (SELECT MetricValue FROM Metrics MM WHERE MM.MetricType = 'TW' AND MM.CandidateID = C.CandidateID ORDER BY MM.RetrieveDate DESC LIMIT 1) AS Followers,";
            $query .= " (SELECT MetricValue FROM Metrics MM WHERE MM.MetricType = 'FB' AND MM.CandidateID = C.CandidateID ORDER BY MM.RetrieveDate DESC LIMIT 1) AS Fans,";
            $query .= " (SELECT Followers + Fans) AS Total";
            $query .= " FROM Candidates C LEFT JOIN Races R ON R.RaceID = C.RaceID LEFT JOIN States S ON R.State = S.StateAbbrev";
            $query .= " ORDER BY R.State, R.RaceID";
            
            $result = mysql_query($query);
            $lastRow = 0;
            $place = 0;
            
            print "<div class='empty'><table><tbody>\n";
            while($row = mysql_fetch_array($result))
            {
                if( $lastRow != $row['RaceID'] )
                {
                    print "</tbody></table></div>\n\n";
                    print "<div class='race race-" . $row['RaceType'] . "'>\n";
                    print "<h2>" . $row['StateFullName'] . "</h2>\n";
                    print "<table>";
                    print "<thead><tr><th class='full-name'>Candidate</th><th class='followers'>Followers</th><th class='fans'>Fans</th><th class='total'>Total</th></thead><tbody>\n";
                    $lastRow = $row['RaceID'];
                }
                print "<tr class='incumbent-".$row['Incumbent']." affiliation-".$row['Affiliation']." winner-".$row['Won']."'>\n";
                print "<td class='full-name'  >" . $row['LastName'] . ", " . $row['FirstName'] . " (".$row['Affiliation'].")</td>\n";
                print "<td class='followers'  ><a href='http://www.twitter.com/" . $row['TwitterID'] . "' title='@" . $row['TwitterID'] . "'>" . $row['Followers'] . "</a></td>\n";
				if(is_numeric($row['FacebookID'])) {
	                print "<td class='fans'       ><a href='http://www.facebook.com/pages/id/" . $row['FacebookID'] . "' title='" . $row['FacebookID'] . "'>" . $row['Fans'] . "</td>\n";
				} else {
	                print "<td class='fans'       ><a href='http://www.facebook.com/" . $row['FacebookID'] . "' title='" . $row['FacebookID'] . "'>" . $row['Fans'] . "</td>\n";
				}
                print "<td class='total'      >" . $row['Total'] . "</td>\n";
                print "</tr>";
            }
            print "</tbody></table></div>";
            
            mysql_close($conn);	
        ?>
        </div>
    </div><!-- end column-right -->
</div><!-- end wrapper -->

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17715434-3']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script> 

</body>
</html>
