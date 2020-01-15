<?php
require "header.php";
?>
<main>
    <?php

    if (!empty($_SESSION["userId"])) {
        # Logged in
    ?>
        <div class="grid">
            <div class="empty">empty</div>
            <div class="searchBar">
                <input type="search" placeholder="Not active yet.">
            </div>
            <div class="profilePic">
                <img src="uploads/default_profile_img.jpg" height="100px" width="100px">
                <?php
# ci serve una tabella per le immagini così composta:
// id userId name_pic status
// in questo div serve un if($status == 0) allora mostra default altrimenti seleziona dal database
// il nome della foto e caricala


                ?>








            </div>
            <div class="infoUser">
                info of the user.
            </div>
            <div class="canDo">
                <h3>Things you can do:</h3>
                <ul>
                    <li><a href="changePwd.php">Change password</a></li>
                </ul>
            </div>
            <div class="workingOn">
                <h3>Working on:</h3>
                <ul>
                    <li><a href="changeMail.php">Change e-mail</a></li>
                    <li><a href="changePic.php">Change profile picture</a></li>
                    <li><a href="changeColor.php">Change website color</a></li>
                </ul>
            </div>
            <div class="wantTo">
                <h3>I want to:</h3>
                <ul>
                    <li>Create a search bar</li>
                    <li>Create a visualizer like Amazon with shopping list</li>
                    <li>Create a visualizer like Amazon with shopping list</li>
                    <li>Create a repository for different extentions</li>
                </ul>
            </div>
            <div class="photos">
                show photos.
            </div>
        </div>
    <?php
    } else {
    ?>
        <!-- Not logged in -->
        Welcome to my social network.
        echo '<a href="signup.php">Signup</a>';
    <?php
    }

    require "displayError.php";
    ?>
</main>

<?php
require "footer.php";
?>