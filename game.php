<!DOCTYPE php>
<!--DATA-->
<?php
session_start();
$numPlayers = $_POST['numplayers'];
$velocity = $_POST['velocity'];
$stories = trim($_POST['stories']);
$storiesArray = explode("\n", $stories);
$storiesArray = array_filter($storiesArray, 'trim');
$currentplayer = 0;
$currentstory = 0;
$isPrevDisabled = true;
$isNextDisabled = true;
$isRevoting = $_COOKIE['isRevoting'];

if ($numPlayers == 1) $nextPlayerButtonName = "See Results";

if (sizeof($storiesArray) > 1) $isNextDisabled = false;
$cardSets = array(
  array(0,1,2,3,5,8,13,21,34,55,89),
  array(0,0.5,1,2,3,5,8,13,20,40,100),
  array("XXS","XS","S","M","L","XL","XXL"),
  array(0,1,2,4,8,16,32,64)
);
$cardSetChosen = 0;
if ($_POST['cardset'] == 'modfibonacci') $cardSetChosen = 1;
if ($_POST['cardset'] == 'tshirts') $cardSetChosen = 2;
if ($_POST['cardset'] == 'powers') $cardSetChosen = 3;

$_SESSION['numplayers'] = $_POST['numplayers'];
$_SESSION['velocity'] = $_POST['velocity'];
$_SESSION['storiesArray'] = $storiesArray;
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
        <button type="button" id="resetbutton">Reset Cards</button>
        <button type="button" id="nextplayerbutton" onclick="nextPlayerButton()">Next Player</button>

          <br><br><br>
          <?php
          for ($x = 1; $x <= $numPlayers; $x++) {
            echo "<img src='hiddencard.PNG'  height='60' alt=''> Player $x&nbsp&nbsp&nbsp";
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
              echo "<button class='card' onclick='cardselect($j)'>" . $cardSets[$cardSetChosen][$j] . "</button>";
          }
          ?>
          <button class="card" id="noClueCard">?</button>
        
            
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
          <button type="button" id="endbutton">End Game</button>
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
    const cardSetChosen = <?php echo json_encode($cardSetChosen); ?>;
    const cardSets = <?php echo json_encode($cardSets); ?>;
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
    
    // Controls the functions of the next player button
    function nextPlayerButton() {
      debugger;
      if (currentStory===storiesArray.length-1) {
        // Validate votes
        for (let i=0; i<storiesArray.length; i++) {
          if (playerAnswers[currentPlayer][i]==undefined) {
            alert(`No vote recorded for Story ${i+1}: ${storiesArray[i]}. Please vote before moving to the next player.`);
            return;
          }
        }

        if (currentPlayer===NUM_PLAYERS-1) {
          // go to results page
          let results = JSON.stringify(playerAnswers);
          document.cookie = "results="+results;
          window.location.href="results.php";
        }
        else {
          currentPlayer++;
          currentStory = 0;
          updateUI();
        }
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
      document.getElementById("currentStoryTitle").innerHTML = `Story: &nbsp${storiesArray[currentStory]} | Player ${currentPlayer+1}`;
      
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
      if (currentStory==storiesArray.length-1)
        document.getElementById("nextplayerbutton").disabled = false;
      else 
        document.getElementById("nextplayerbutton").disabled = true;
      if (currentPlayer===NUM_PLAYERS-1) 
        document.getElementById("nextplayerbutton").innerHTML = "See Results";
      else
        document.getElementById("nextplayerbutton").innerHTML = "Next Player";
    }

    // Inputs the selected card into the result array
    function cardselect(value) {
        playerAnswers[currentPlayer][currentStory] = cardSets[cardSetChosen][value];
        alert("you chose: " + cardSets[cardSetChosen][value]);
    }
  </script>
</html>
