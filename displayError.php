    <?php
    if (isset($_SESSION["userId"])) { # ciò che vedi durante login
        if (isset($_GET["error"])) {
            switch ($_GET["error"]) {
                case "sql":
                    echo 'There was an error during the interaction with the db.';
                    break;
            }
        }
        if (isset($_GET["success"])) {
            switch ($_GET["success"]) {
                case "changePwd":
                    echo 'There password was updated';
                    break;
                case "login":
                    echo 'You are logged in as ' . $_SESSION["userName"] . ', by your e-mail ' . $_SESSION["userMail"];
                    break;
            }
        }
    } else if (!isset($_SESSION["userId"])) { # ciò che vedi durante logout
        if (isset($_GET["logout"])) {
            switch ($_GET["logout"]) {
                case "success":
                    echo 'Thank for using me. See you soon!';
                    break;
            }
        }
        if (isset($_GET["error"])) {
            switch ($_GET["error"]) {
                case "login":
                    echo 'The username or the password was wrong.';
                    break;
                case "sql":
                    echo 'There was a problem during the interaction with db.';
                    exit();
                case "emptyFields":
                    echo 'You should fill the login form in order to login.';
                    exit();
                case "userOrIdNotFound":
                    echo 'This username is not present inside the db.';
                    exit();
                case "wrongData":
                    echo 'I do not recognize the username or the password.';
                    exit();
                case "goAway":
                    echo 'You tried to reach a page from the URL.';
                    exit();
            }
        }
    }
    ?>