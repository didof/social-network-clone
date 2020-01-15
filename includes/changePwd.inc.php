<?php

if (!empty($_POST["change-submit"])) {
    header("Location: ../index.php");
    exit();
} else {
    # controlla se ha la password vecchia
    # chiede password nuova due volte

    # Take data
    # Error handlers
    # controllare che la password vecchia corrisponda a quella in db
    # controllare che le due password nuove siano uguali
    # update in db della nuova password

    # Take data
    $oldPwd = $_POST["oldPwd"];
    $newPwd = $_POST["newPwd"];
    $newPwd2 = $_POST["newPwd2"];

    # Error handlers
    if (empty($oldPwd) || empty($newPwd) || empty($newPwd2)) {
        header("Location: ../changePwd.php?error=empty");
        exit();
        # in questo caso, per maggior sicurezza, basta che uno degli input sia vuoto e annullo tutto
    } else {
        # Check old password
        // devo interagire con il db, quindi richiedo la connessione
        require "dbh.inc.php";
        $sql = "SELECT * FROM users WHERE uidUsers=?";
        $stmt = mysqli_stmt_init($conn);
        $stmtPrepare = mysqli_stmt_prepare($stmt, $sql);
        if (!$stmtPrepare) {
            echo 'level: mysqli_stmt_prepare => ';
            var_dump($stmtPrepare);
            echo '<br><br>';
            header("Location: ../index.php?error=sql");
            exit();
        } else {
            session_start();
            $username = $_SESSION["userName"];
            echo 'level: $_SESSION["userName"] => ' . $username . '<br><br>';

            $stmtBinded = mysqli_stmt_bind_param($stmt, "s", $username);
            echo 'level: mysqli_stmt_bind_param => ';
            var_dump($stmtBinded);
            echo '<br><br>';

            $stmtExecuted = mysqli_stmt_execute($stmt);
            echo 'level: mysqli_stmt_execute => ';
            var_dump($stmtExecuted);
            echo '<br><br>';

            $result = mysqli_stmt_get_result($stmt);
            echo 'level: mysqli_stmt_get_result => ';
            var_dump($result);
            echo '<br><br>';

            $row = mysqli_fetch_assoc($result);
            echo 'level: mysqli_fetch_assoc => ';
            var_dump($row);
            echo '<br><br>';

            if (!$row) {
                echo '>Error: There is not this username inside the db.';
                header("Location: ../index.php?error=sql");
                exit();
            } else {
                echo 'password in database => ' . $row["pwdUsers"] . ' <br>';
                echo 'OldPwd => ' . $oldPwd . '<br><br>';
                $pwdCheck = password_verify($oldPwd, $row["pwdUsers"]);
                if (!$pwdCheck) {
                    echo '>Error: The password is not right. <br><br>';
                } else if ($pwdCheck) {
                    # la pwd digitata corrisponde a quella nel db
                    echo '>Success: The input pwd equals the pwd in db. <br><br>';

                    # Error handler of New Passwords
                    if ($newPwd != $newPwd2) {
                        echo '>Error: The new passwords do not corresponds to each other. <br><hr>';
                        // header("Location: ../changePwd.php?error=passwordCheck");
                        // exit();
                    } else {
                        # Le due password coincidono, ora si sovrascrive la vecchia con la nuova
                        $sql = "UPDATE users SET pwdUsers=?";
                        $stmt = mysqli_stmt_init($conn);
                        $stmtPrepared = mysqli_stmt_prepare($stmt, $sql);
                        echo 'level: mysqli_stmt_bind_param => ';
                        var_dump($stmtBinded);
                        echo '<br><br>';

                        if (!$stmtPrepared) {
                            echo '>Error: There is not this username inside the db.';
                            header("Location: ../index.php?error=sql");
                            exit();
                        } else {
                            # Hashing della nuova password
                            $newPwdHashed = password_hash($newPwd, PASSWORD_DEFAULT);
                            echo 'La nuova password (hashed) è ' . $newPwdHashed . '<br><br>';

                            $stmtBinded = mysqli_stmt_bind_param($stmt, "s", $newPwdHashed);
                            echo 'level: mysqli_stmt_bind_param => ';
                            var_dump($stmtBinded);
                            echo '<br><br>';

                            $sqlExecuted = mysqli_stmt_execute($stmt);
                            echo 'level: mysqli_stmt_execute => ';
                            var_dump($stmtExecuted);
                            echo '<br><br>';
                            header("Location: ../index.php?success=changePwd");
                            exit();
                        }
                    }
                }
            }
        }
    }
}
