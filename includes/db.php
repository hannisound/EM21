<?php
/* Database connection settings */
$host = 'localhost';
$user = 'root';
$pass = ''; //Hb90kmarl!
$db = 'em21';

define ("prefix", "em21");
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
$mysqli->set_charset("UTF8");
