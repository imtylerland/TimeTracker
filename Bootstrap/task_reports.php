<?php
require_once(__DIR__.'/../include.php');

session_start();

open_html("Task Reports");

echo '<h1>Task Reports</h1>';

clientProjectTaskDropdownForm('report');

taskReports();

close_html();

?>