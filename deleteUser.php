<?php require "header.php";
include_once "includes/dbh.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" lang="en">
</head>

<body>
    <h1>Authorisation: attempts <?php echo $_SESSION["attemptsDelete"] ?></h1>
    <?php
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case "attempts_2":
                echo '>Warning: the username and/or the password is wrong. Probably you
                committed a typo; you have two attempts.';
                break;
            case "attempts_1":
                echo '>Warning: still incorrect... last guess - be sure before you make another mistake.';
                break;
            case "block":
                echo '>Alert: for security reasons, you are prevented from deleting the profile.
                To resolve please contact admin.';
                break;
        }
    } else {
        echo ' <p>We need to make sure it’s really you.</p>';
    }
    ?>

    <form action="includes/deleteUser.inc.php" method="post">
        <?php
        if (isset($_GET["error"])) {
            if ($_SESSION["attemptsDelete"] < 1) {
                echo '<p>This feature was blocked.</p>';
                echo '<input type="text" name="username" placeholder="disabled" disabled>';
                echo '<p>This feature was blocked.</p>';
                echo '<input type="text" name="password" placeholder="disabled" disabled>';
                echo '<p>Contact the admin:</p>';
                echo '<button type="submit" name="submit-admin">Send a mail to Admin</button>';
            } else {
                echo '<p>Please, digit your username below:</p>
                    <input type="text" name="username" placeholder="condemned name">
                    <p>Now, insert your password.</p>
                    <input type="text" name="password" placeholder=">Warning: the password will be readable!">
                    <p>Now, insert your password.</p>
                    <button type="submit" name="submit-delete">DELETE ME</button>';
            }
        } else {
        ?>
            <p>Please, digit your username below:</p>
            <input type="text" name="username" placeholder="condemned name">
            <p>Now, insert your password.</p>
            <input type="text" name="password" placeholder=">Warning: the password will be readable!">
            <p>Now, insert your password.</p>
            <button type="submit" name="submit-delete">DELETE ME</button>
        <?php
        }
        ?>
    </form>
</body>

</html> <?php require "footer.php" ?>