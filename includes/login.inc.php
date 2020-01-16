<?php

if (!isset($_POST["login-submit"])) {
    # Ha raggiunto digitando l'Url
    header("Location: ../index.php?login=error");
    exit();
} else {
    # Ha raggiundo premento il login-submit

    require "dbh.inc.php";

    $mail = $_POST["mail"]; // che l'utente può usare sia come mail che come username -> vedi $sql sotto
    $password = $_POST["password"];
    echo '$mail => ' . $mail . '<br>';
    echo '$password => ' . $password . '<br><hr>';

    # Controllo se sono stati inseriti entrambi
    if (empty($mail) || empty($password)) {
        header("Location: ../index.php?error=emptyFields");
        exit();
    } else {

        # Controllo che password e username siano esatti
        $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?";
        // il where con l'or permette di rintracciare l'user sia tramite username che tramite e-mail
        $stmt = mysqli_stmt_init($conn);
        echo '>Check: username and password are correct?<br><br>';
        $stmtPrepare = mysqli_stmt_prepare($stmt, $sql);
        if (!$stmtPrepare) {
            header("Location: ../index.php?error=sql");
            exit();
            echo 'level: mysqli_stmt_prepare => ';
            var_dump($stmtPrepare);
            echo '<br><br>';
        } else {
            $stmtBinded = mysqli_stmt_bind_param($stmt, "ss", $mail, $mail);
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

            if (!$row) { // quindi se $row è NULL, quindi se non ho il risultato a quella mail/username
                echo '>Error: There isn\'t a username or e-mail like that in db.';
                header("Location: ../index.php?error=userOrIdNotFound");
                exit();
            } else {
                $pwdCheck = password_verify($password, $row['pwdUsers']);
                if (!$pwdCheck) {
                    # Password sbagliata per questo user
                    echo '>Error: The password is not right.';
                    header("Location: ../index.php?error=wrongData");
                    exit();
                } else if ($pwdCheck) {
                    // specifico $pwdCheck == true perchè password_verify può eventualmente
                    // restituire un qualcosa che non è ne true né false, quindi voglio
                    // stringere solo per il true

                    # Il login funziona inserendo nella global $_SESSION delle variabili
                    // a cui poter fare riferimento in altre pagine
                    session_start();
                    $_SESSION["userId"] = $row["idUsers"];
                    $_SESSION["userName"] = $row["uidUsers"];
                    $_SESSION["userMail"] = $row["emailUsers"];
                    $_SESSION["attemptsDelete"] = 3;


                    header("Location: ../index.php?login=success");
                    exit();
                }
            }
        }
    }
}
