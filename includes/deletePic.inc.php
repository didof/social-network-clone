<?php

include_once "dbh.inc.php";
session_start();

$userId = $_SESSION["userId"];
echo '$userId => ' . $userId . '<br><br>';

# First of all, I assess if the pic was already deleted
$sql = "SELECT * FROM profileimg WHERE userId=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
var_dump($row);
if (!($row > 0)) {
    # Pic not present
    echo '>Error: pic is not present.<br><br>';
} else if ($row["status"] == 0) {
    echo '>Error: You are not allowed to cancel the default pic.';
    echo '<img src="uploads/default_profile_img.jpg" height="100px" width="100px">';
    header("Location: ../index.php?error=deleteDefault");
    exit();
} else {

    $fileName = "../uploads/profile_image_user_" . $userId . "*";
    # the * means that there is something after that.
    echo '$fileName => ' . $fileName . '<br><br>';

    $fileInfo = glob($fileName, GLOB_NOCHECK);
    # glob() is a function that search for a specific file that as part of the name
    # il flag GLOB_NOCHECK fa riportare il path nel caso in cui non trovi nulla
    echo 'glob($fileName) => ' . $fileInfo[0];
    // var_dump($fileInfo);
    echo '<br><br>';
    echo '>Check:<br> <img src="' . $fileInfo[0] . '" height="30px" width="30px"><br><br>';

    # Beware
    // Suppose you are using Id = 1
    // the path will be ../uploads/profile_image_user_1.jpg
    // BUT ALSO ../uploads/profile_image_user_11.jpg, ...profile_image_user_16.jpg and, etc.
    # FIX
    // just work on the first index, the lowest than the correct one
    $fileExploded = explode(".", $fileInfo[0]);
    echo '$fileExploded => ';
    var_dump($fileExploded);
    echo '<br><br>';

    $fileExt = end($fileExploded);
    echo '$fileExt => ';
    var_dump($fileExt);
    echo '<br><br>';
    // note: "end()" select the last value in array, while "current()" the first one

    $fileNamePath = "../uploads/profile_image_user_" . $userId . "." . $fileExt;
    echo '$fileNamePath => ';
    var_dump($fileNamePath);
    echo '<br><br>';

    # Delete file
    if (!unlink($fileNamePath)) {
        echo '>Error: file was not deleted.';
    } else {
        # Cancel from database too
        $sql = "UPDATE profileimg SET name=?, status=? WHERE userId=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        $default = "default_profile_img.jpg";
        $status = 0;
        mysqli_stmt_bind_param($stmt, "sbi", $default, $status, $userId);
        mysqli_stmt_execute($stmt);
        echo '>Success: file was deleted from repository and overdrived in db.';
        header("Location: ../index.php?success=delete");
        exit();
    }
}
