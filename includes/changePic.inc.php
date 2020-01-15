<?php
if (!empty($_POST["pic-submit"])) {
    header("Location: ../index.php?upload=goAway");
    exit();
} else {

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
        header("Location: ../changePic.php?upload=fileSize&maxSize=$maxSize");
        exit();
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
            header("Location: ../changePic.php?upload=extNotAllowed&ext=$fileExtention&allowed=$allowedExtentionsString");
            exit();
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
            $fileNewName = uniqid('', true) . '.' . $fileExtention;
            echo '$fileNewName => ' . $fileNewName . '<br><br>';

            # Addressing to folder
            $fileDestination = '../uploads/' . $fileNewName;
            move_uploaded_file($fileTmp_name, $fileDestination);
            echo '>Success: The file was uploaded successfully.';
            header("Location: ../changePic.php?upload=success&fileName=$fileName");
            exit();
        }
    }
}
