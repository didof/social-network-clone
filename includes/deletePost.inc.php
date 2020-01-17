<?php
include_once "dbh.inc.php";

# Aggiungi goaway
# vedi se c'è l'immagine, evenutalmente devi cancellarla
# cancella dal database il post richesto

$post = $_GET["idPost"];

$sql = "SELECT * FROM post WHERE id=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $post);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$image = mysqli_fetch_assoc($result);
$dir = $image["post_dir"];
$author = $image["post_author"];
$fileName = $image["post_file_name"];
echo $fileName;

if ($fileName == "") {
    # Cancel from database
    $sql = "DELETE FROM post WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $post);
    mysqli_stmt_execute($stmt);
    echo 'Cancellata solo dal database<br>';
    header("Location: ../index.php?success=deletePost");
    exit();
} else {
    $path = '../uploads/post/' . $dir . '/' . $author . '/' . $fileName;
    echo $path;
    if (!unlink($path)) {
        echo '>Error: file was not deleted.';
    } else {
        # Cancel from database too
        $sql = "DELETE FROM post WHERE id=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "i", $post);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo 'Cancellata sia dal database che dalla cartella<br>';
        header("Location: ../index.php?success=deletePost");
        exit();
    }
}
