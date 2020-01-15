<?php
require "header.php";
?>
<main>
    <?php

    if (!empty($_SESSION["userId"])) {
        # Logged in
    ?>
        <h3>Things you can do:</h3>
        <ul>
            <li><a href="changePwd.php">Change password</a></li>
        </ul>
        <h3>Working on:</h3>
        <ul>
            <li><a href="changeMail.php">Change e-mail</a></li>
            <li><a href="changePic.php">Change profile picture</a></li>
            <li><a href="changeColor.php">Change website color</a></li>
        </ul>
        <h3>I want to:</h3>
        <ul>
            <li>Create a search bar</li>
            <li>Create a visualizer like Amazon with shopping list</li>
            <li>Create a visualizer like Amazon with shopping list</li>
            <li>Create a repository for different extentions</li>
        </ul>
    <?php
    } else {
    ?>
        <!-- Not logged in -->
        Welcome to my social network.
    <?php
    }
    if (empty($_SESSION["userId"])) {
        echo '<a href="signup.php">Signup</a>';
    } else {
    }
    require "displayError.php";
    ?>
</main>

<?php
require "footer.php";
?>