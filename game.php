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
$nextPlayerButtonName = "Next Player";

// This is where the answers will be stored, we should use array_push to add to it. this would allow for
// the array to have a changeable size even if we wanted to add a story in the middle.
$answers = array();

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

<head>
  <title>Lobby | Planning Poker</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <h1>Planning Poker<hr></h1>
  </header>

  <main>
    <!--LEFT SIDE OF THE SCREEN-->
    <div class="split left">
      <!--TOPLEFT-->
      <div class="ltop">
        <h2>
          <div id="currentStoryTitle">

            <?php
            echo "Story: &nbsp$storiesArray[0]";
            ?>
          </div>
        </h2>

        <button type="button" class="bluebutton" id="prevbutton" name="prevbutton" onclick="prevbutton()"
        <?php echo $isPrevDisabled?'disabled':''; ?>>
        Previous Story</button>
        <button type="button" class="bluebutton" id="nextbutton" name="nextbutton" onclick="nextbutton()"
        <?php echo $isNextDisabled?'disabled':''; ?>>
        Next Story</button>
        <button type="button" id="resetbutton">Reset Cards</button>
        <button type="button" id="nextplayerbutton"><?php echo $nextPlayerButtonName; ?></button>

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
              echo "<button class='card'>" . $cardSets[$cardSetChosen][$j] . "</button>";
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
    </main>
  </body>



<!--javascript for button onclick-->
  <script>
  var currentstory = <?php echo json_encode($currentstory); ?>;
  var storiesArray = <?php echo json_encode($storiesArray); ?>;

    function nextbutton() {
      if(currentstory < storiesArray.length-1) {
        if(currentstory==0) {
          document.getElementById("prevbutton").disabled = false;
          document.getElementById("prevbutton").style.backgroundColor="#0572DC";
        } else if(currentstory >= storiesArray.length-2) {
          document.getElementById("nextbutton").disabled = true;
          document.getElementById("nextbutton").style.backgroundColor="#ABABAB";
        }
        var storyid = "story" + currentstory.toString();
        document.getElementById(storyid).style.backgroundColor = "#EAEAEA";
        currentstory++;
        document.getElementById("currentStoryTitle").innerHTML = 'Story: &nbsp'
        + storiesArray[currentstory];
        document.getElementById("currentStoryHeader").innerHTML = (currentstory+1).toString()
        + "/" + storiesArray.length;
        storyid = "story" + currentstory.toString();
        document.getElementById(storyid).style.backgroundColor = "white";
      } else {
        document.getElementById("nextplayerbutton").innerHTML = "See Results";
      }
    }

    function prevbutton() {
      if(currentstory > 0) {
        if(currentstory==storiesArray.length-1) {
          document.getElementById("nextbutton").disabled = false;
          document.getElementById("nextbutton").style.backgroundColor="#0572DC";
          if (<?php echo json_encode($numPlayers); ?> > 1
            && <?php echo json_encode($currentplayer); ?> < <?php echo json_encode($numPlayers); ?> - 1)
             document.getElementById("nextplayerbutton").innerHTML = "Next Player";
        } else if(currentstory <= 1) {
          document.getElementById("prevbutton").disabled = true;
          document.getElementById("prevbutton").style.backgroundColor="#ABABAB";
        }
        var storyid = "story" + currentstory.toString();
        document.getElementById(storyid).style.backgroundColor = "#EAEAEA";
        currentstory--;
        document.getElementById("currentStoryTitle").innerHTML = 'Story: &nbsp'
        + storiesArray[currentstory];
        document.getElementById("currentStoryHeader").innerHTML = (currentstory+1).toString()
        + "/" + storiesArray.length;
        storyid = "story" + currentstory.toString();
        document.getElementById(storyid).style.backgroundColor = "white";
      }
    }
  </script>
