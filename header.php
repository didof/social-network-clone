<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Header</title>
</head>

<body>
    <?php if (isset($_SESSION["userId"])) { ?>
        <ul class="nav-container">
            <li>
                <form action="includes/logout.inc.php" method="post">
                    <button type="submit" name="logout-submit">Logout</button>
                </form>
            </li>
            <li><a href="index.php"><button>Home</button></a></li>
            <li>Id: <b><?php echo $_SESSION["userId"]; ?></b>, username: <b><?php echo $_SESSION["userName"]; ?></b>, <i><?php echo $_SESSION["userMail"]; ?></i></li>
        </ul>
    <?php } else if (!isset($_SESSION["userId"])) { ?>
        <form action="includes/login.inc.php" method="post">
            <input type="test" name="mail" placeholder="e-mail or username">
            <input type="password" name="password" placeholder="password">
            <button type="submit" name="login-submit">Login</button>
            <input type="checkbox" name="remember" value=0>Remember me
        </form>
    <?php } ?>
</body>

</html>