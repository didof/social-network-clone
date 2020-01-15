<?php

session_start(); // deve essere vivo per poter essere ucciso
session_unset(); // cancella tutta i value salvati nella superglobal
session_destroy();

header("Location: ../index.php?logout=success");
exit();
