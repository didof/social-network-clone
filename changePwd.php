<?php
require "header.php";
?>
<?php
# Error messages
$msgOld = $msgNew = $msgNew2 = "";

// if (isset($_GET["error"])) {
//     $error = $_GET["error"];
//     switch ($error) {
//         case "empty":
//             $msgOld = "Insert the last used password.";
//             $msgNew = "Choose a new password.";
//             $msgNew2 = "Repeat to confirm the new password.";
//             break;
//     }
// }
?>

<?php if (empty($_SESSION["userId"])) {
    header("Location: index.php");
    exit();
} else {
?>
    <h3>Change Password:</h3>

    <form action="includes/changePwd.inc.php" method="post">
        <input type="password" name="oldPwd" placeholder="Old Password"><span class="error"> <?php echo $msgOld ?></span> <br>
        <input type="password" name="newPwd" placeholder="New Password"><span class="error"> <?php echo $msgNew ?></span><br>
        <input type="password" name="newPwd2" placeholder="Confirm New Password"><span class="error"> <?php echo $msgNew2 ?></span><br>
        <button type="submit" name="change-submit">Change it.</button>
    </form>
<?php } ?>

<?php
require "footer.php";
?>