<?php

# Voglio impedire all'utente di raggiungere questa pagina digitando l'Url
if (!isset($_POST['signup-submit'])) {
    # Ha raggiunto digitando l'Url
    header("Location: ../signup.php?signup=error");
    exit();
} else {
    # Ha raggiunto premento il signup-submit
    # Mi collego al database
    require_once "dbh.inc.php";
    # Prendo il data dal signup-form
    $username = $_POST["uid"];
    $mail = $_POST["mail"];
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd2"];

    # Error handlers

    $is_mail_valid = filter_var($mail, FILTER_VALIDATE_EMAIL);
    $is_username_valid = preg_match("/^[a-zA-Z0-9]*$/", $username);
    echo 'level: $is_mail_valid => ';
    var_dump($is_mail_valid);
    # se non valida restituisce "bool(false)", se valida restituisce "string(n)"
    echo '<br><br>';

    echo 'level: $is_username_valid => ';
    var_dump($is_username_valid);
    # se non valida restituisce "int(0)", se valida restituisce "int(1)"
    echo '<br><br>';

    if (empty($username) || empty($mail) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../signup.php?error=empty");
        exit();
    } else if (!$is_mail_valid && !$is_username_valid) {
        header("Location: ../signup.php?error=invalidEmail/uid");
        exit();
    } else if (!$is_mail_valid) {
        header("Location: ../signup.php?error=invalidEmail&uid=" . $username);
        exit();
    } else if (!$is_username_valid) {
        header("Location: ../signup.php?error=invalidUid&mail=" . $mail);
        exit();
    } else if ($password !== $passwordRepeat) {
        header("Location: ../signup.php?error=passwordCheck&uid=" . $username . "&mail=" . $mail);
        exit();
    } else {
        # Controllo se esiste già un username a questo nome
        echo '>Check: the username if free to use?<hr>';

        $sql = "SELECT uidUsers FROM users WHERE uidUsers = ?";
        $stmt = mysqli_stmt_init($conn);
        // a questo livello l'acesso alle proprietà tramite var_dump() non è ancora permesso

        $stmtPrepared = mysqli_stmt_prepare($stmt, $sql);
        echo 'level: mysqli_stmt_prepare => ';
        var_dump($stmtPrepared);
        echo '<br><br>';

        if (!$stmtPrepared) {
            header("Location: ../signup.php?error=stmtPrepared");
            exit();
        } else {
            $stmtBinded = mysqli_stmt_bind_param($stmt, "s", $username);
            echo 'level: mysqli_stmt_bind_param => ';
            var_dump($stmtBinded);
            echo '<br><br>';

            if (!$stmtBinded) {
                header("Location: ../signup.php?error=stmtBinded");
                exit();
            } else {
                $stmtExecuted = mysqli_stmt_execute($stmt);
                echo 'level: mysqli_stmt_execute => ';
                var_dump($stmtExecuted);
                echo '<br><br>';

                if (!$stmtExecuted) {
                    header("Location: ../signup.php?error=stmtExecuted");
                    exit();
                } else {
                    // la richiesta è andata a buon fine
                    $stmtStore = mysqli_stmt_store_result($stmt);
                    echo 'level: mysqli_stmt_store_result => ';
                    var_dump($stmtStore);
                    echo '<br>stmt => ';
                    var_dump($stmt);
                    echo '<br><br>';

                    # Controllo quante rows con lo stesso username ci sono nel db
                    $stmtRows = mysqli_stmt_num_rows($stmt);
                    echo 'level: mysqli_stmt_store_result => ';
                    var_dump($stmtRows);
                    echo '<br><br>';
                    if ($stmtRows > 0) {
                        header("Location: ../signup.php?error=uidTaken");
                        exit();
                    } else {
                        # Username disponibile
                        echo '>Action: insert the username into the db.<hr>';

                        $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers)
                        VALUES (?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        $stmtPrepared = mysqli_stmt_prepare($stmt, $sql);
                        echo 'level: mysqli_stmt_prepare => ';
                        var_dump($stmtPrepared);
                        echo '<br><br>';
                        if (!$stmtPrepared) {
                            header("Location: ../signup.php?error=inserting");
                            exit();
                        } else {
                            # Hashing della password
                            $pwdHashed = password_hash($password, PASSWORD_DEFAULT);
                            echo '$password => ' . $password . '<br>';
                            echo '$pwdHashed: ' . $pwdHashed . '<br>';

                            $stmtBinded = mysqli_stmt_bind_param($stmt, "sss", $username, $mail, $pwdHashed);
                            echo 'level: mysqli_stmt_bind_param => ';
                            var_dump($stmtBinded);
                            echo '<br><br>';

                            $stmtExecuted = mysqli_stmt_execute($stmt);
                            echo 'level: mysqli_stmt_execute => ';
                            var_dump($stmtExecuted);
                            echo '<br><br>';

                            $stmtStored = mysqli_stmt_store_result($stmt);
                            echo 'level: mysqli_stmt_store_result => ';
                            var_dump($stmtStored);
                            echo '<br>';
                            echo 'stmt => ';
                            var_dump($stmt);
                            echo '<br><br>';
                            echo '<h3>Success</h3>';
                            header("Location: ../index.php?signup=success");
                            exit();
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn); //chiude la connessione con il db
        }
    }
}
