<?php
if (!empty($_POST["pic-submit"])) {
    header("Location: ../index.php?upload=goAway");
    exit();
} else {
    session_start();
    $userId = $_SESSION["userId"];
    echo '$userId => ' . $userId;
    echo '<br><br>';

    # Declare
    $maxSize = 10000000; // 10Mb
    echo 'Declare: $maxSize => ' . $maxSize . '<br><br>';

    # Get file data
    $file = $_FILES["file"];
    echo '$_FILES => ';
    var_dump($_FILES);
    echo '<br><br>';

    $fileName = $_FILES["file"]["name"];
    $fileType = $_FILES["file"]["type"];
    $fileTmp_name = $_FILES["file"]["tmp_name"];
    $fileError = $_FILES["file"]["error"];
    $fileSize = $_FILES["file"]["size"];

    echo '$fileName => ';
    var_dump($fileName);
    echo '<br>';
    echo '$fileType => ';
    var_dump($fileType);
    echo '<br>';
    echo '$fileTmp_name => ';
    var_dump($fileTmp_name);
    echo '<br>';
    echo '$fileError => ';
    var_dump($fileError);
    echo '<br>';
    echo '$fileSize => ';
    var_dump($fileSize);
    echo '<br><br>';

    # Error Handlers
    if (!$fileError === 0) {
        echo '>Error: There was a problem during the upload.';
        header("Location: ../changePic.php?upload=fileError");
        exit();
    } else if ($fileSize > $maxSize) { // 1Mb = 1000kb
        echo '>Error: The file is too big.';
        // header("Location: ../changePic.php?upload=fileSize&maxSize=$maxSize");
        // exit();
    } else {
        # The file is suitable, we can proceed

        # Get extention
        $fileExplode = explode(".", $fileName);
        echo '$fileExplode => ';
        var_dump($fileExplode);
        echo '<br>';

        $fileExtention = strtolower(end($fileExplode));
        echo '$fileExtention => ';
        var_dump($fileExtention);
        echo '<br>';

        # Define and check the accepted extentions
        $allowedExtentions = array('jpg', 'jpeg', 'png');
        $allowedExtentionsString = implode(", .", $allowedExtentions);
        if (!in_array($fileExtention, $allowedExtentions)) {
            echo '>Error: This extention (' . $fileExtention . ') is not supported.';
            // header("Location: ../changePic.php?upload=extNotAllowed&ext=$fileExtention&allowed=$allowedExtentionsString");
            // exit();
        } else {
            # Show functionality of uniqid()
            $testUniqd = uniqid();
            echo '$fileUniqd() => ' . $testUniqd . '<br>';
            $fileUniqd = uniqid('', true);
            echo '$fileUniqd(\'\', true) => ' . $fileUniqd . '<br>';
            // the second parameter (more_entropy) is optional

            # Create a new name for the uploaded file
            // Because if I submit two different files with same name.ext,
            // the latter overwrites the former
            session_start();
            $fileNewName = "profile_image_user_" .  $userId . '.' . $fileExtention;
            echo '$fileNewName => ' . $fileNewName . '<br><br>';


            # Update in the db
            include_once "dbh.inc.php";
            $newStatus = 1;
            $sql = "UPDATE `profileimg` SET `name`= ?, `status`= ? WHERE userId = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo '>Error: error during sql update';
            } else {
                mysqli_stmt_bind_param($stmt, "sii", $fileNewName, $newStatus, $userId);
                if (mysqli_stmt_execute($stmt)) {
                    echo '>Success: The image was uploaded in the database as' . $fileNewName . '<br>';
                } else {
                    echo '>Error: Opsie';
                }
            }

            # Addressing to folder
            $fileDestination = '../uploads/' . $fileNewName;
            move_uploaded_file($fileTmp_name, $fileDestination);
            echo '>Success: The file was uploaded successfully.';
            header("Location: ../changePic.php?upload=success&fileName=$fileNewName");
            exit();
        }
    }
}
