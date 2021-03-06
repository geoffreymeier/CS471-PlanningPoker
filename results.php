<?php
$numPlayers =  $_COOKIE['numplayers'];
$velocity =  $_COOKIE['velocity'];
$storiesArray =  json_decode($_COOKIE['storiesArray']);
//$cardset =  $_COOKIE['cardset'];

$resultsString = $_COOKIE['results'];
$results = json_decode($resultsString);

?>
<html>

<head>
    <title>Planning Poker</title>
    <link rel="stylesheet" href="styles.css">
	<script src="resources.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript"></script>
    <link href="graph_resources/jquery.dvstr_jqp_graph.css" rel="stylesheet" type="text/css"/>
    <script src="graph_resources/jquery.dvstr_jqp_graph.js" type="text/javascript"></script>

</head>

<body>
    <header>
        <h1 id="sitetitle"><a href="lobby.php">Planning Poker</a>
            <hr>
        </h1>
    </header>

    <main>
        <h2>Results</h2>
        <p>See the results of your planning poker game below.</p><br>

		<h3>Sprint Velocity:</h3>
			<p><?php echo $velocity; ?></p>
		<h3>User stories:</h3>
		<p>
			<ul>
			<?php
			for ($i = 0; $i <= sizeof($storiesArray)-1; $i++) {
				$ave = 0;
				$count = 0;
				for ($j = 0; $j <= $numPlayers-1; $j++) {
				  $ave += $results[$j][$i];
				  $count++;
				}
				$ave /= $count;
				echo "<li>" . $storiesArray[$i] . " (Average vote: " . $ave . ")</li>";
				echo "<ul>";
				for ($j = 0; $j <= $numPlayers-1; $j++) {
				  echo "<li>Player " . $j . " voted: " . $results[$j][$i] . "</li>";
				}
				echo "</ul>";
			}
			?>
			</ul>
		</p>
		<!-- <h3>Graph:</h3> -->
		<div class="graph" id="graph"></div>
		<br>

		<a class="button" onclick="revoteConfirm()">Revote</a>
		<a class="button" id="createNewGame" onclick="newGame()">Create New Game</a>
		<a class="button" onclick="restart()">Restart</a>

	</main>

</body>

<script  src="resources.js"></script>
<script>
// Set the following vars as constants, since we don't want to accidentally change them from this page
const NUM_PLAYERS = parseInt(getCookie('numplayers'));
const STORIES_ARRAY = JSON.parse(getCookie('storiesArray'));
const RESULTS = JSON.parse(getCookie('results'));
const CARDSET = getCookie('cardset');

// Since t-shirt sizes are incompatible with graph, display message instead of graph if that 
// card set was used
if (CARDSET !== "tshirts") 
	createGraph();
else
	document.getElementById("graph").innerHTML = "<h2 style=\"color:red\">Note: Can't display graph for t-shirt card set; pick a different card set to use this feature.</h2><br>";


// Create the results graph
function createGraph() {
	$('#graph').dvstr_graph({
		title: 'Results Graph',
		unit: 'Story Points',
		separate: true,
		graphs: getData(),
	})
}

// Create the "graphs" option for the graph
function getData() {
	let array = [];
	for (i=0; i<STORIES_ARRAY.length; i++) {
		let jsonstr = "{";
		//label
		jsonstr += `"label":"${STORIES_ARRAY[i]}",`;
		//color array
		jsonstr += `"color":[`;
		for (j=0; j<NUM_PLAYERS-1; j++) jsonstr += `"${getGraphColor(j)}",`;
		jsonstr += `"${getGraphColor(NUM_PLAYERS-1)}"],`;
		//value array
		jsonstr += `"value":[`;
		for (j=0; j<NUM_PLAYERS-1; j++) jsonstr += `${RESULTS[j][i]},`;
		jsonstr += `${RESULTS[NUM_PLAYERS-1][i]}]}`;
		array.push(JSON.parse(jsonstr));
	}
	return array;
}
  
function revote() {
    document.cookie = "restartable = 0";
    window.location.href="game.php";
}

function restart() {
  if (confirm("Are you sure you want to restart?")) {
    document.cookie = "restartable = true";
    window.location.href="game.php";
  }
}
  
function revoteConfirm() {
  if (confirm("Are you sure you want to revote?")) {
    document.cookie = "isRevoting=true";
    revote();
  }
}

function newGame() {
  if (confirm("Are you sure you want to start a new game?")) {
	resetCookies();
	window.location.href="lobby.php";
  }
}
</script>

</body>

</html>
