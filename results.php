<?php
session_start();
?>
<html>

<head>
    <title>Game | Planning Poker</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1 id="sitetitle"><a href="lobby.php">Planning Poker</a>
            <hr>
        </h1>
    </header>

    <main>
        <div>
            <h2>Results</h2>
            <p>See the results of your planning poker game below.</p><br>
        </div>

		<div>TODO: print each user story and voted point values. Will probably need to style this as well.
			<ul>User story 1:
				<li>3</li>
				<li>4</li>
				<li>6</li>
			</ul>
			<ul>User story 2:
				<li>10</li>
				<li>2</li>
				<li>6</li>
			</ul>
		</div>
    </main>

</body>

</html>