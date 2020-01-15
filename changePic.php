<?php require "header.php";
include_once "includes/dbh.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" lang="en">
    <title>change profile picture</title>
</head>

<body>
    <?php
    # is this user already associated with a profile photo? If yes, show it
    // the Id it's already stored in $_SESSION["userId"]
    $sql = "SELECT * FROM profileimg WHERE userId=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo '>Error: Something went wrong.';
    } else {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["userId"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $info = mysqli_fetch_assoc($result);
        $status = $info["status"];
        if ($status == 0) {
            # There is not an uploaded photo, then do not show anything
        } else {
            # There is already an uploaded photo, show it
            echo "<img src='uploads/" . $info["name"] . "' height='300px' width='300px'>";
        }
    }
    ?>
    <p>Click below to select a picture from your pc.</p>

    <!-- Upload/change picture -->
    <form action="includes/changePic.inc.php" method="post" enctype="multipart/form-data">
        <!-- enctype specifica come il data del form dovrebbe essere codificato -->
        <input type="file" name="file"><br>
        <button type="submit" name="pic-submit">Upload picture</button>
    </form>

    <!-- Delete picture -->
    <form action="includes/deletePic.inc.php" method="post">
        <button type="submit" name="pic-delete">Delete picture</button>
    </form>

    <?php
    if (!empty($_GET["upload"])) {
        switch ($_GET["upload"]) {
            case "fileError":
                echo '<p class="error"><br>>Error: There was a problem during the upload.</p>';
                break;
            case "fileSize":
                $max = $_GET["maxSize"];
                $max = $max / 1000;
                echo '<p class="error">>Error: The file is too big; the maximum is size is ' . $max . 'Mb.</p>';
                break;
            case "extNotAllowed":
                echo '<p class="error"><br>>Error: This extention (.' . $_GET["ext"] . ') is not supported;
                You can only upload a .' . $_GET["allowed"] . ' file. </p>';
                break;
            case "success":
                echo '<p class="success"><br>>Success: The file (' . $_GET["fileName"] . ') was uploaded successfully.</p>';
                break;
        }
    }




    ?>
</body>

</html>

<?php require "footer.php" ?>