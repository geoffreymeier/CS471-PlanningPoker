<?php
session_start();
$numPlayers = $_SESSION['numplayers'];
$velocity = $_SESSION['velocity'];
$storiesArray = $_SESSION['storiesArray'];

// TODO: we need the answers from game.php
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
		<!-- Below is just an example for verification that the results were successfully transferred. They still need processed -->
		<p><?php echo $resultsString ?></p>

		<p>TODO: display votes for each player for each story and average vote. Will probably need to style this as well.
		</p>
		<h3>Sprint Velocity:</h3>
			<p><?php echo $velocity; ?></p>
		<h3>User stories:</h3>
		<p>
			<ul>
			<?php
			for ($i = 0; $i <= sizeof($storiesArray)-1; $i++) {
				echo "<li>" . $storiesArray[$i] . " (Average vote: " . ")</li>";
				echo "<ul>";
				for ($j = 0; $j <= $numPlayers-1; $j++) {
				  echo "<li>Player " . $j . " voted: " . "</li>";
				}
				echo "</ul>";
			}
			?>
			</ul>
		</p>
		<!-- <h3>Graph:</h3> -->
		<div class="graph" id="graph"></div>
		<br>
		<!-- 
		When creating the revote button onclick JS function, please add following line to
		execute if revoting (this will set the revoting flag for the game page):
		document.cookie = "isRevoting=true"
		-->
		<a href="game.php" class="button">Revote</a>
        <a href="lobby.php" class="button" id="createNewGame">Create New Game</a>

	</main>

</body>

<script>
// Set the following vars as constants, since we don't want to accidentally change them from this page
const NUM_PLAYERS = <?php echo json_encode($numPlayers); ?>;
const STORIES_ARRAY = <?php echo json_encode($storiesArray); ?>;
const RESULTS = JSON.parse(getCookie('results'));
const CARDSET = <?php echo json_encode($_SESSION['cardset']); ?>;

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
		debugger;
		array.push(JSON.parse(jsonstr));
	}
	return array;
}
</script>

</html>