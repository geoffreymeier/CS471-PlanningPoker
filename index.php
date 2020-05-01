<!DOCTYPE php>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="resources.js"></script>
    <script>
    $(document).ready(function(){
        $("#getStarted").click(function(){
            let page = "lobby.php #content";
            $("main").fadeOut(function() {
                $("main").load(page);
            }).fadeIn();
        });
    });

    </script>
    <title>Planning Poker</title>
</head>

<body>
    <header>
        <h1>Planning Poker<hr></h1>
    </header>

    <main>
    <div id="content">
        <p>Welcome to Planning Poker! This tool will help your scrum team vote on user stories and see detailed results, as well as make revoting on stories easier than ever. Click the button below to get started!</p>
        <p><em>Note: please make sure you have cookies enabled... Otherwise, the game will not work correctly.</em></p>
        <p id="getStarted">Let's get started!</p>
    </div>
    </main>

    <script type="text/javascript">
resetCookies();

function handleChange() {
    var nP = document.getElementById("numplayers").value;
    var vel = document.getElementById("velocity").value;
    var area = document.getElementById("stories");
    var arrayOfStories = area.value.replace(/\r\n|\n|\r/g,"\n");
    arrayOfStories.split("\n");
    if (nP < 1 || nP > 20) {
        alert("Value should be between 1 - 20");
        return false;
    }
    if (vel < 1) {
      alert("Velocity should be greater than 0");
      return false;
    }
    if (typeof arrayOfStories == "undefined"
                        || arrayOfStories == null
                        || arrayOfStories == null
                        || arrayOfStories <= 0){
      alert("User stories cannot be empty!");
      return false;
    }
   return true;
}
</script>
    
</body>

</html>
