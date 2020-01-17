<?php
include_once "dbh.inc.php";

$userId = $_SESSION["userId"];

if (isset($_POST["search-submit-post"])) {
    $searchInput = $_POST["searchInput"];
    $sql = "SELECT * FROM post 
    JOIN profileimg ON post.post_user_id = profileimg.userId
    WHERE post_title LIKE ?
    OR post_author LIKE ?
    OR post_date LIKE ?
    OR post_dir LIKE ?
    ORDER BY post_date DESC";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    $searchCirca = "%{$searchInput}%";
    mysqli_stmt_bind_param($stmt, "ssss", $searchCirca, $searchCirca, $searchCirca, $searchCirca);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (is_null($result)) {
        echo 'There was no result for <i> ' . $searchInput . '</i>. Try with the title or the
    author or the data or the directory.';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="post">
        <div class="header">
            <div><img src="uploads/' . $row["name"] . '" height="40px" width="40px"></div>
            <div>' . $row["post_title"] . '</div>
            <div>' . $row["post_author"] . ' (' . $row["post_user_id"] . ')</div>';
            if ($userId == $row["post_user_id"]) {
                echo '<div><button>Comment</button></div>
                    <div><button>Modify</button></div>
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
        } // close while loop
    }
} else {
    $sql = "SELECT * FROM post 
    JOIN profileimg ON post.post_user_id = profileimg.userId
    ORDER BY post_date DESC";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<div class="post">
            <div class="header">';
            if ($row["post_dir"] == "anonymous") {
                echo '<div><img src="uploads/anon_profile_img.png" height="40px" width="40px"></div>
            <div>' . $row["post_title"] . '</div>
            <div>Anonymous</div>';
            } else {
                echo '<div><img src="uploads/' . $row["name"] . '" height="40px" width="40px"></div>
            <div>' . $row["post_title"] . '</div>
            <div>' . $row["post_author"] . ' (' . $row["post_user_id"] . ')</div>';
            }
            if ($userId == $row["post_user_id"]) {
                echo '<div><button>Comment</button></div>
                    <div><button>Modify</button></div>
            <div><a href="includes/deletePost.inc.php?idPost=' . $row["post_id"] . '"><button>Cancel</button></a></div>';
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
            echo '</div>'; // close class content
            echo '</div>'; // close this post
        } // close while loop

    } // close while
}
