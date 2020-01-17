<?php
require "header.php";
include_once "includes/dbh.inc.php";
?>
<main>
    <?php

    if (!empty($_SESSION["userId"])) {
        # Logged in
    ?>
        <div class="grid">
            <div class="empty"></div>
            <div class="empty2">User in db:</div>
            <div class="searchBar">
                <form action="index.php" method="post">
                    <input type="text" name="searchInput" placeholder="Look for an username/e-mail">
                    <button type="submit" name="search-submit">Search user</button>
                    <button type="submit" name="search-submit-post">Search post</button>
                </form>
            </div>
            <div class="profilePic">
                <?php
                $sql = "SELECT * FROM profileimg WHERE userId=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo '<div class="profile-pic"><img src="uploads/error_profile_img.png" height="100px" width="100px"></div>';
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["userId"]);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);
                    if (!($row > 0)) {
                        # there is not the row associated with this user
                        echo '<div class="profile-pic"><img src="uploads/error_profile_img.png" height="100px" width="100px"></div>';
                    } else {
                        # there istus the row associated with this user
                        if ($row["status"] == 0) {
                            # thus, the user didn't uploaded yet
                            echo '<div class="profile-pic"><img src="uploads/default_profile_img.jpg" height="100px" width="100px"></div>';
                        } else {
                            # this, the user already uploaded the pic
                            echo "<div class='profile-pic'><img src='uploads/" . $row["name"] . "?" . mt_rand() . "' height='150px' width='200px'></div>";
                            // some browser can remember the picture and this force the user to refresh the page
                            # FIX -> add mt_rand()
                            // <img src='uploads/profile_image_user_1.jpg?218728917'>
                        }
                    }
                }
                echo 'Username: ' . $_SESSION["userName"];
                ?>
                <!-- <img src="uploads/default_profile_img.jpg" height="100px" width="100px"> -->

            </div>
            <div class="resultUsers">
                <?php include "includes/resultUsers.inc.php" ?>

            </div>
            <div class="canDo">
                <h3>Things you can do:</h3>
                <ul>
                    <li><a href="changePwd.php">Change password</a></li>
                    <li><a href="changePic.php">Set/unset profile picture</a></li>
                    <li><a href="deleteUser.php">Delete account</a></li>
                    <li><a href="index.php?order=uploadPost">Upload picture</a></li>
                </ul>
            </div>
            <div class="workingOn">
                <h3>Working on:</h3>
                <ul>
                    <li><a href="changeMail.php">Change e-mail</a></li>
                    <li><a href="changeUsername.php">Change username</a></li>
                </ul>
            </div>
            <div class="wantTo">
                <h3>I want to:</h3>
                <ul>
                    <li>Create a visit page of somebody else and leave a post</li>
                    <li>Create a visualizer like Amazon with shopping list</li>
                    <li>Allow the user to choose witch features want to see</li>
                    <li>Create a repository for different extentions</li>
                    <li>When sign up you get your own uploads repository</li>
                    <li>Different behaviour the first time you login:
                        <ul>
                            <li>Ask age -> low/bigger text size</li>
                            <li>color-blind -> appropriate colors</li>
                            <li>choose website theme</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="uploadPost">
                <?php if (!isset($_GET["order"])) : ?>
                    <form action="index.php?order=uploadPost" method="post">
                        <table class="table-post">
                            <tr>
                                <td><input text="text" disabled></td>
                                <td><button type="submit">Post</button></td>
                            </tr>

                        </table>
                    </form>
                <?php else : ?>
                    <form action="includes/uploadPost.inc.php" method="post" enctype="multipart/form-data">
                        <table class="table-post">
                            <tr>
                                <td><input text="text" name="post_title" placeholder="title"></td>
                                <td><button type="submit" name="cancel-uploadPost">Cancel</button></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="post_content" placeholder="content"></td>
                                <td><button type="submit" name="draft-uploadPost">Draft</button></td>
                            </tr>
                            <tr>
                                <td><input type="file" name="post_file" value="add photo(s)"></td>
                                <td><button type="submit" name="submit-uploadPost">Post</button></td>
                            </tr>
                            <tr>
                                <td>Repository: </td>
                                <td><select name="post_dir">
                                        <option value="myself">my self/</option>
                                        <option value="anonymous">anonymous/</option>
                                        <option value="opinion">opinion/</option>
                                        <option value="ads">ads/</option>
                                        <!-- is beein sold? -->
                                        <option value="recipes">recipes/</option>
                                    </select></td>
                            </tr>
                        </table>
                    </form>
                <?php
                endif;
                ?>
            </div>
            <div class="showPost">
                <?php require "includes/showPost.inc.php" ?>
            </div>
            <!-- if there is photo, show it in gallery, with associated this post -->

        <?php
    } else {
        ?>
            <!-- Not logged in -->
            Welcome to my social network.
            <a href="signup.php">Signup</a>
        <?php
    }
        ?>
</main>

<?php
require "footer.php";
?>