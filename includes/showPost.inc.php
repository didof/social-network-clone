<?php
include_once "dbh.inc.php";

$userId = $_SESSION["userId"];

$sql = "SELECT * FROM post ORDER BY post_date DESC";
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        echo '<div class="post">
        <div class="header">
            <div>' . $row["post_title"] . '</div>
            <div>' . $row["post_author"] . ' (' . $row["post_user_id"] . ')</div>';
        if ($userId == $row["post_user_id"]) {
            echo '<div><button>Modify</button></div>
            <div><a href="includes/deletePost.inc.php?idPost=' . $row["id"] . '"><button>Cancel</button></a></div>';
        } else {
            echo '<div></div>
            <div></div>';
        }
        echo '</div>'; // close class header
        echo '<div class="content">';
        if ($row["post_file_name"] == "") {
        } else {
            $imageAuthor = $row["post_author"];
            $imageDir = $row["post_dir"];
            $imageName = $row["post_file_name"];
            $imagePath = "uploads/post/" . $imageDir . "/" . $imageAuthor . "/" . $imageName;
            echo '<img src="' . $imagePath . '" width="100px" height="100px">';
        }
        echo $row["post_content"] . '<br><br>';
        echo '<i>Posted on ' . $row["post_date"] . ' in /' . $row["post_dir"];
    } // close if not set picture
    echo '</div>'; // close class content
    echo '</div>'; // close this post
} // close while
