<!DOCTYPE php>
<html>

<head>
    <title>Lobby | Planning Poker</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Planning Poker
            <hr>
        </h1>
    </header>

    <main>
        <div id="lobbytitle">
            <h2>Create a New Game</h2>
            <p>Fill in the criteria below to define the parameters for your game.</p><br>
        </div>

        <div id="lobbyform">
            <form method="POST" action="game.php">
                <label for="numplayers">Number of players: </label><br>
                <!--I defined the max number of players as 20; however, we can change this if need be-->
                <input type="number" id="numplayers" name="numplayers" min="1" max="20" placeholder="e.g. 8"
                    required><br><br>
                <label for="velocity">Sprint velocity: </label><br>
                <input type="number" id="velocity" name="velocity" min="1" placeholder="e.g. 21" required><br><br>

                <label for="cardset">Card set:</label><br>
                <input type="radio" name="cardset" id="fibonacci" value="fibonacci" required>
                <label for="fibonacci">Fibonacci - (0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89)</label><br>
                <input type="radio" name="cardset" id="modfibonacci" value="modfibonacci" required>
                <label for="modfibonacci">Modified Fibonacci - (0, ½, 1, 2, 3, 5, 8, 13, 20, 40, 100)</label><br>
                <input type="radio" name="cardset" id="tshirts" value="tshirts" required>
                <label for="tshirts">T-Shirts - (XXS, XS, S, M, L, XL, XXL)</label><br>
                <input type="radio" name="cardset" id="powers" value="powers" required>
                <label for="powers">Powers of 2 - (0, 1, 2, 4, 8, 16, 32, 64)</label><br><br>

                <label for="stories">User Stories:</label><br>
                <textarea name="stories" id="stories" cols="60" rows="10"
                    placeholder="Enter each user story on a separate line" required></textarea><br><br>

                <input type="submit" id="submit" value="Create Game">
                <input type="reset" id="reset">
            </form>
        </div>
    </main>

</body>

</html>
