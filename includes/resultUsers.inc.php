<?php

if (!isset($_POST["search-submit"]) or empty($_POST["searchInput"])) {

    # The search button was not used, thus show all Users
    $sql = "SELECT * FROM users";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $thatUserId = $row["idUsers"];
        $thatUserUsername = $row["uidUsers"];
        // echo '$thatUserId => ' . $thatUserId . '<br>';
        // echo '$thatUserId => ' . $thatUserUsername . '<br><br>';

        $sqlImg = "SELECT * FROM profileimg WHERE userId=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sqlImg);
        mysqli_stmt_bind_param($stmt, "i", $thatUserId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        $ThatUserStatus = $row["status"];
        // echo '$ThatUserStatus => ' . $row["status"] . '<br><br>';

        echo '<div>';
        if ($row["status"] == 0) {
            echo '<img src="uploads/default_profile_img.jpg" height="50px" width="50px">';
            echo '<b>' . $thatUserUsername . '</b><br><a href="#">Visit</a>';
        } else {
            echo '<img src="uploads/' . $row["name"] . '" height="50px" width="50px">';
            echo '<b>' . $thatUserUsername . '</b><br><a href="#">Visit</a>';
        }
        echo '</div>';
    }
} else {
    $searchInput = mysqli_real_escape_string($conn, $_POST["searchInput"]);
    $sql = "SELECT * FROM users
    WHERE uidUsers LIKE ?
    OR emailUsers LIKE ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    # Since the data is LIKE, i have to re-write them
    $searchCirca = "%{$searchInput}%";
    mysqli_stmt_bind_param($stmt, "ss", $searchCirca, $searchCirca);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $thatUserUsername = $row["uidUsers"];
        $thatUserId = $row["idUsers"];

        $sqlImg = "SELECT * FROM profileimg WHERE userId=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sqlImg);
        mysqli_stmt_bind_param($stmt, "i", $thatUserId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $ThatUserStatus = $row["status"];

        echo '<div>';
        if ($row["status"] == 0) {
            echo '<img src="uploads/default_profile_img.jpg" height="50px" width="50px">';
            echo '<b>' . $thatUserUsername . '</b><br><a href="#">Visit</a>';
        } else {
            echo '<img src="uploads/' . $row["name"] . '" height="50px" width="50px">';
            echo '<b>' . $thatUserUsername . '</b><br><a href="#">Visit</a>';
        }
        echo '</div>';
    }
}
