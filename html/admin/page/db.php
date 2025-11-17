<?php
	header('Content-Type: text/html; charset=utf-8'); // utf-8인코딩

	include __DIR__ . '/config.php';

	$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
	$db->set_charset("utf8");

	function mq($sql)
	{
		global $db;
		return $db->query($sql);
	}
?>