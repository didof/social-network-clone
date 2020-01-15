<?php require "header.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
</head>

<body>
    <?php
    # Error msg generator
    $holderUsername = "Username";
    $holderUsernameError = "john_doe";
    $holderMail = "E-mail";
    $holderMailError = "ukillmydog@ikillyou.com";
    $msg = "";

    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case 'empty':
                $holderUsername = $holderUsernameError;
                $holderMail = $holderMailError;
                break;
            case 'invalidEmail/uid':
                $holderUsername = $holderUsernameError;
                $holderMail = $holderMailError;
                break;
            case 'invalidEmail':
                $holderMail = $holderMailError;
                break;
            case 'invalidUid':
                $holderUsername = $holderUsernameError;
                break;
            case 'passwordCheck':
                $msg = "The passwords do not correspond. Try again.";
                break;
            case 'uidTaken':
                $msg = "The username is alreaty taken. Choose another.";
                break;
        }
    }
    ?>

    <h1>Signup</h1>
    <form action="includes/signup.inc.php" method="post">
        <?php
        if (isset($_GET["uid"])) {
            echo '<input type="text" name="uid" value="' . $_GET["uid"] . '"><br>';
        } else {
            echo '<input type="text" name="uid" placeholder="' . $holderUsername . '"><br>';
        } ?>
        <?php
        if (isset($_GET["mail"])) {
            echo '<input type="text" name="mail" value="' . $_GET["mail"] . '"><br>';
        } else {
            echo '<input type="text" name="mail" placeholder="' . $holderMail . '"><br>';
        } ?>
        <input type="password" name="pwd" placeholder="Password"><br>
        <input type="password" name="pwd2" placeholder="Repeat password">
        <button type="submit" name="signup-submit">Signup</button>
        <?php
        echo $msg;
        ?>
    </form>
</body>

</html>

<?php require "footer.php" ?>