<?php
session_start();

$numPlayers = $_POST['numplayers'];
$velocity = $_POST['velocity'];
$stories = trim($_POST['stories']);
$storiesArray = explode("\n", $stories);
$storiesArray = array_filter($storiesArray, 'trim');

?>
<html>
<head>
    <title>Game | Planning Poker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>Values Saved:</div>
    <?php
        echo "<div>Number of Players = $numPlayers</div>";
        echo "<div>Velocity = $velocity</div>";
        echo "<div>Stories:</div>";
        foreach ($storiesArray as $story) {
            echo"<div>&nbsp&nbsp&nbsp$story</div>";
        }
    ?>
</body>
</html>