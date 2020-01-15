<?php require "header.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" lang="en">
    <title>change profile picture</title>
</head>

<body>
    <form action="includes/changePic.inc.php" method="post" enctype="multipart/form-data">
        <!-- enctype specifica come il data del form dovrebbe essere codificato -->
        <input type="file" name="file"><br>
        <button type="submit" name="pic-submit">Upload picture</button>
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