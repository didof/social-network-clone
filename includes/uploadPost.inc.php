<?php
include_once "dbh.inc.php";
session_start();

# Cancel button
if (isset($_POST["cancel-uploadPost"])) {
    header("Location: ../index.php");
    exit();
} else if (isset($_POST["draft-uploadPost"])) {
    // create draft code
    echo 'fai bozza';
} else if (isset($_POST["submit-uploadPost"])) {
    echo '<b>Upload code</b><br><br>';
    $postId = $_SESSION["userId"];
    $postTitle = $_POST["post_title"];
    $postContent = $_POST["post_content"];
    $postName = $_SESSION["userName"];
    $postDir = $_POST["post_dir"];

    $postFile = $_FILES["post_file"];
    $fileName = $postFile["name"];
    $fileType = $postFile["type"];
    $fileTmp = $postFile["tmp_name"];

    # Overwrite the name of the file with the title of the post and add the extention
    $fileExploded = explode('.', $fileName);
    $fileExtention = strtolower(end($fileExploded));
    $fileName = $postTitle . "_" . uniqid('', true) . "." . $fileExtention;
    echo '$fileName => ' . $fileName . "<br>";

    $sql = "INSERT INTO post (post_user_id, post_title, post_content, post_author, post_file_name, post_dir)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo '>Error: statement and request not associated.<br><br>';
    } else if (!mysqli_stmt_bind_param($stmt, "isssss", $postId, $postTitle, $postContent, $postName, $fileName, $postDir)) {
        echo '>Error: data not binded.<br><br>';
    } else if (!mysqli_stmt_execute($stmt)) {
        echo '>Error: query not executed.<br><br>';
    } else {
        echo '>Success: data inserted in db.<br><br>';

        # Send in tipology folder, possibly make it first
        $path = "../uploads/post/" . $postDir . "/";
        echo $path;
        echo '<br><br>';

        if (!file_exists($path)) {
            mkdir("../uploads/post/" . $postDir, 0777);
            echo "The directory {$postDir} was successfully created.<br><br>";
        } else {
            echo "The directory {$postDir} exists.<br><br>";
        }

        // Set a personal folder for this user
        $userPath = $path . $postName . "/";
        echo $userPath . '<br>';

        if (!file_exists($userPath)) {
            mkdir($userPath, 0777);
            echo "The directory {$userPath} was successfully created.<br><br>";
        } else {
            echo "The directory {$userPath} exists.<br><br>";
        }

        #Thus, insert into folder the pic
        $fileDestination = $userPath . $fileName;
        move_uploaded_file($fileTmp, $fileDestination);

        mysqli_stmt_close($stmt);
        header("Location: ../index.php?success=uploadPost");
        exit();
    } // success inserting data in db
} // close submit-upload
