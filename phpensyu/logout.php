<?php
session_start();

$_SESSION = [];

/* Cookieに保存したセッションIDを削除 */
setcookie(session_name(), "", time() - 1);

session_destroy();

/* login.phpに遷移する */
header("Location: login.php");

