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
    <title>Game | Planning Poker</title>
    <link rel="stylesheet" href="styles.css">
	<script src="resources.js"></script>
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
		<h3>Graph:</h3>
		<p>TODO.</p>
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

</html>