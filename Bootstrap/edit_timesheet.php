<?php
require_once(__DIR__.'/../include.php');

session_start();

open_html("Edit Time Sheet");

echo '<h1>Edit Time Sheet</h1>';

editTimeSheet();

close_html();
?>