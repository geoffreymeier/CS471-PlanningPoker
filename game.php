<!DOCTYPE php>
<!--DATA-->
<?php
$numPlayers = $_POST['numplayers'];
$velocity = $_POST['velocity'];
$stories = trim($_POST['stories']);
$stories = str_replace("\r", "", $stories);
$storiesArray = explode("\n", $stories);
$storiesArray = array_filter($storiesArray, 'trim');
$currentplayer = 0;
$currentstory = 0;
$isPrevDisabled = true;
$isNextDisabled = true;

// We need to check if the cookie even exists before setting it, or we get an error
if(isset($_COOKIE['isRevoting'])) {
	$isRevoting = $_COOKIE['isRevoting'];
} else {
	$isRevoting = false;
}


$cardSets = array(
  array(0,1,2,3,5,8,13,21,34,55,89),
  array(0,0.5,1,2,3,5,8,13,20,40,100),
  array("XXS","XS","S","M","L","XL","XXL"),
  array(0,1,2,4,8,16,32,64)
);
$cardSetChosen = 0;
if ($_POST['cardset'] == 'modfibonacci') $cardSetChosen = 1;
else if ($_POST['cardset'] == 'tshirts') $cardSetChosen = 2;
else if ($_POST['cardset'] == 'powers') $cardSetChosen = 3;

if(isset($_COOKIE['restartable'])) {
	if ($_COOKIE['restartable'] == true) {
	  $numPlayers = $_COOKIE['numplayers'];
	  $velocity = $_COOKIE['velocity'];
	  $storiesArray =  json_decode($_COOKIE['storiesArray']);
	  $cardSetChosen = $_COOKIE['cardSetChosen'];
	}
}

$nextPlayerButtonName = ($numPlayers == 1) ? "See Results" : "Next Player";

if (sizeof($storiesArray) > 1) $isNextDisabled = false;
?>

<html>

<head>
  <title>Planning Poker</title>
  <link rel="stylesheet" href="styles.css">
  <script src="resources.js"></script>
</head>

<body>
  <header>
    <h1>Planning Poker<hr></h1>
  </header>

  <main>
  <div id="content">
    <!--LEFT SIDE OF THE SCREEN-->
    <div class="split left">
      <!--TOPLEFT-->
      <div class="ltop">
        <h2>
          <div id="currentStoryTitle">

            <?php echo "Story: &nbsp$storiesArray[0] | Player ",$currentplayer+1; ?>

          </div>
        </h2>

        <button type="button" class="bluebutton" id="prevbutton" name="prevbutton" onclick="prevButton()"
        <?php echo $isPrevDisabled?'disabled':''; ?>>
        Previous Story</button>
        <button type="button" class="bluebutton" id="nextbutton" name="nextbutton" onclick="nextButton()"
        <?php echo $isNextDisabled?'disabled':''; ?>>
        Next Story</button>
        <button type="button" id="resetbutton" onclick="rcButton()">Reset Card</button>
        <button type="button" id="nextplayerbutton" onclick="nextPlayerButton()">
          <?php echo $nextPlayerButtonName; ?></button>

          <br><br><br>
          <?php
          for ($x = 1; $x <= $numPlayers; $x++) {
            echo "<img src='b4selectcard.PNG' id='playercardImage$x'  height='80' alt=''> Player $x&nbsp&nbsp&nbsp";
          }
          ?>
        </div>

        <!--BOTTOMLEFT-->
        <div class="lbottom">
          <div>
            <label class="pushdown">Cards</label>
          </div>
          <?php
          for ($j = 0; $j <= sizeof($cardSets[$cardSetChosen])-1; $j++) {
              echo "<button class='card' id='c$j' onclick='cardselect($j)'>" . $cardSets[$cardSetChosen][$j] . "</button>";
          }
          ?>
          <button class="card" id="noClueCard" onclick="cardselect(-1)">?</button>


        </div>
      </div>

      <!--RIGHT SIDE OF THE SCREEN-->
      <div class="split right">
        <!--TOPRIGHT-->
        <div class="rtop">
          <div id="storynumsect">
            <label class="pushdown">.</label><br>
            <label>STORY #</label>
            <h1 id="currentStoryHeader"><?php echo strval($currentstory+1) . "/" . sizeof($storiesArray) ?></h1>
          </div>

          <div id="blacksect">
            <div><label id="s">Story</label></div>
          </div>

          <?php
            for ($j = 0; $j <= sizeof($storiesArray)-1; $j++) {
              echo "<div class='belowblacksect' id='story" . $j . "'><p>" . $storiesArray[$j] . "</p></div>";
            }
          ?>
        </div>

        <!--BOTTOMRIGHT-->
        <div class="rbottom">
          <button type="button" id="addstorybutton">+ Add Story</button><br>
          <button type="button" class="bluebutton" id="endbutton">End Game</button>
        </div>
      </div>
    </div>
    </main>
  </body>

<!--javascript for button onclicks-->
  <script  src="resources.js"></script>
  <script>
    let currentStory = <?php echo $currentstory; ?>;
    let storiesArray = <?php echo json_encode($storiesArray); ?>;
    let currentPlayer = <?php echo $currentplayer; ?>;
    let isRevoting = <?php echo json_encode($isRevoting); ?>==="true";
    const NUM_PLAYERS = <?php echo $numPlayers; ?>;
    const VELOCITY = <?php echo $velocity; ?>;
    const cardSetChosen = <?php echo json_encode($cardSetChosen); ?>;
    const cardSets = <?php echo json_encode($cardSets); ?>;
    let playerCardChoosenIndex = initArray(NUM_PLAYERS,storiesArray.length);
    let playerAnswers = (!isRevoting) ? initArray(NUM_PLAYERS,storiesArray.length) : JSON.parse(getCookie("results"));
 
    if (NUM_PLAYERS>1) {
      document.getElementById("nextplayerbutton").disabled = true;
    }

    function nextButton() {
      if (currentStory!==storiesArray.length-1) {
        currentStory++;
        updateUI();
      }
    }

    function prevButton() {
      if (currentStory!==0) {
        currentStory--;
        updateUI();
      }
    }

    function rcButton() {
      playerCardChoosenIndex[currentPlayer][currentStory] = null;
      playerAnswers[currentPlayer][currentStory] = null;
      updateUI();
    }

    // Controls the functions of the next player button
    function nextPlayerButton() {
      // Validate votes
      if (currentPlayer===NUM_PLAYERS-1) {
        //TODO: go to results page
        let results = JSON.stringify(playerAnswers);
        let sa = JSON.stringify(storiesArray);
        document.cookie = "results="+results;
        document.cookie = "numplayers="+NUM_PLAYERS;
        document.cookie = "velocity="+VELOCITY;
        document.cookie = "storiesArray="+sa;
        document.cookie = "cardSetChosen="+cardSetChosen;
        window.location.href="results.php";
      }
      else {
        currentPlayer++;
        currentStory = 0;
        updateUI();
      }
    }

    // This function will update the UI based on the current values of currentPlayer, currentStory, etc.
    function updateUI() {
      /***  UPDATE THE RIGHT SIDEBAR ***/
      for (let i=0; i<storiesArray.length; i++) {
        let storyid = "story" + i;
        document.getElementById(storyid).style.backgroundColor = (i===currentStory) ? "white" : "#EAEAEA";
      }
      document.getElementById("currentStoryHeader").innerHTML = (currentStory+1).toString() + "/" + storiesArray.length;

      /*** UPDATE THE NAVBAR ***/
      document.getElementById("currentStoryTitle").innerHTML = `Story: &nbsp${storiesArray[currentStory]}
      | Player ${currentPlayer+1}`;

      // Previous Story Button
      if (currentStory==0)
        document.getElementById("prevbutton").disabled = true;
      else
        document.getElementById("prevbutton").disabled = false;

      // Next Story Button
      if (currentStory==storiesArray.length-1)
        document.getElementById("nextbutton").disabled = true;
      else
        document.getElementById("nextbutton").disabled = false;

      // Next Player Button
      let allowNextPlayer = true;
      for (let i=0; i<storiesArray.length; i++) {
        if (playerAnswers[currentPlayer][i]==undefined) {
          allowNextPlayer = false;
          break;
        }
      }
      if (allowNextPlayer == true)
        document.getElementById("nextplayerbutton").disabled = false;
      else
        document.getElementById("nextplayerbutton").disabled = true;
      if (currentPlayer===NUM_PLAYERS-1)
        document.getElementById("nextplayerbutton").innerHTML = "See Results";
      else
        document.getElementById("nextplayerbutton").innerHTML = "Next Player";

      // Card Display
      for (let i=1; i<=NUM_PLAYERS; i++) {
        let imgid = "playercardImage" + i;
        document.getElementById(imgid).src = (playerAnswers[i-1][currentStory] == null) ?
         "b4selectcard.PNG" : "hiddencard.PNG";
      }
      for (let i=0; i < cardSets[cardSetChosen].length; i++) {
        let cid = "c" + i;
        document.getElementById(cid).style.backgroundColor =
        (playerCardChoosenIndex[currentPlayer][currentStory] != i) ? "#0572DC" : "188DFF";
      }
      document.getElementById("noClueCard").style.backgroundColor =
      (playerCardChoosenIndex[currentPlayer][currentStory] != -1) ? "#F3F3F3" :  "white";

    }

    // Inputs the selected card into the result array
    function cardselect(value) {
      playerCardChoosenIndex[currentPlayer][currentStory] = value;
      playerAnswers[currentPlayer][currentStory] = (value != -1) ? cardSets[cardSetChosen][value] : "?";
      updateUI();
    }
  </script>
</html>


