<?php
	session_start();
	$yearmonthday = $_POST["yearmonthday"];
	$yearmonthday_split=explode('-', $yearmonthday);
	$yearclicked = $yearmonthday_split[0];
	$monthclicked = $yearmonthday_split[1];
	$dayclicked = $yearmonthday_split[2];

	$_SESSION['yearclicked'] = $yearclicked;
	$_SESSION['monthclicked'] = $monthclicked;
	$_SESSION['dayclicked'] = $dayclicked;
?>