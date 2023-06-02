<?php

/*
 * db_connection.php
 * データベース接続情報を記載します
 */

$db_info = [
	"user" => "testuser",
	"pass" => "testpass",
	"host" => "localhost",
	"dbname" => "todo_phpapp"
];
$dsn = "mysql:host={$db_info['host']}; dbname={$db_info['dbname']}; charset=utf8";

try{
	$dbh = new PDO($dsn, $db_info["user"], $db_info["pass"]);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	die ("PDO Error:" . $e->getMessage());
}
