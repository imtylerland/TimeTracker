<?php
require_once(__DIR__.'/../../include.php');

session_start();

//Call in javascript file
$src =  '../../Javascript/dropdowns.js';
echo '<script src="' . $src . '"></script>';

echo '<h1>Project Reports</h1>';

projectReports();
?>