<?php
include "dbh.inc.php";
session_start();

# If blocked, send email to admin
if (isset($_POST["submit-admin"])) {
    header("Location: ../sendEmail.php");
    exit();
} else {

    # First of all, did the user reached this point via URL?
    if (!isset($_POST["submit-delete"])) {
        header("Location: ../deleteUser.php");
    } else {

        # First of all, authorisation:
        $userId = $_SESSION["userId"];

        $username = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT * FROM users WHERE idUsers=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $account = mysqli_fetch_assoc($result);

        # Check username
        $checkUid = ($username == $account["uidUsers"] ? true : false);
        # Check password
        $checkPwd = password_verify($password, $account["pwdUsers"]);

        if (!($checkUid && $checkPwd)) {
            $attempts = $_SESSION["attemptsDelete"];
            $attempts--;
            $_SESSION["attemptsDelete"] = $attempts;
            if ($attempts <= 0) {
                header("Location: ../deleteUser.php?error=block");
            } else {
                header("Location: ../deleteUser.php?error=attempts_{$attempts}");
                exit();
            }
        } else {
            # In order to delete-list:

            # 1 - cancel associated profile image in folder uploads
            # 2 - cancel associated row on table profileimg
            # 3 - cancel row on talbe users

            // # 1 -select the name of the pic
            $sql = "SELECT * FROM profileimg WHERE userId=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $array = mysqli_fetch_assoc($result);
            $picName = $array["name"];
            mysqli_stmt_close($stmt);
            echo '$picName => ' . $picName . '<br><br>';
            echo "<img src='../uploads/default_profile_img.jpg'><br>";

            if ($picName !== "default_profile_img.jpg") {

                $path = "../uploads/" . $picName;
                echo '$path => ' . $path . '<br><br>';

                if (!unlink($path)) {
                    echo '>Error: couldn\'t delete the pic from the folder.';
                } else {
                    echo '>Success: The file was delete from the folder.';
                }
            }
            # 2 - delete the row on table profileimg
            $sql = "DELETE FROM profileimg WHERE userId=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            if (mysqli_stmt_execute($stmt)) {
                echo '>Success: the row in table imageprofile is been delete successfully.';
            } else {
                echo '>Success: I couldn\'t delete the pic_data from db.';
            }
            mysqli_stmt_close($stmt);

            # 3 - cancel row on talbe users
            $sql = "DELETE FROM users WHERE idUsers=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            if (mysqli_stmt_execute($stmt)) {
                echo '>Success: the row in table users is been delete successfully.';
            } else {
                echo '>Success: I couldn\'t delete the pic_data from db.';
            }
            mysqli_stmt_close($stmt);
            session_unset();
            session_destroy();

            header("Location: ../index.php");
        } //closing of authorisation
    } //closing of if(isset($_POST["submit-delete"]))
} // close of if(isset($_POST["submit-admin]))
