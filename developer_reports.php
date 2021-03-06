<?php
/*
 Name: developer_reports.php
 Description: shows the report for a certain developer for user-picked dates
 Programmers: Ryan Graessle, Brent Zucker
 Dates: (4/18/15, 5/1/15)
 Names of files accessed: include.php
 Names of files changed:
 Input: developer (dropdown), start date (date), end date (date)
 Output: table showing the user's information as well as their timelogs
 Error Handling:
 Modification List:
 4/18/15-Initial code up
 4/19/15-Migrated reports
 4/30/15-Moved file to main folder, fixed css/js links
 */

require_once(__DIR__.'/include.php');

session_start();

if(isset($_POST['selected']) && isset($_POST['toExcel']))
{
	if($_POST['report'] == 'AggregatedTimeLogTableByDeveloper')
		printAggregatedTimeLogTableByDeveloper($_POST['selected'], $_POST['startdate'], $_POST['enddate'], 'csv');
	elseif($_POST['report'] == 'TimeSheet')
		printTimeLogTableByDeveloper($_POST['selected'], $_POST['startdate'], $_POST['enddate'], 'csv');
}

open_html("Developer Reports");

echo '<main id="page-content-wrapper">'; 
echo '<div class="col-lg-12 main-box">';

echo '<div class="jumbotron">';
echo '<div class="page-header">';
echo '<h1>Developer Reports</h1>';
echo '</div>';

developerReports();

echo '</div>';

echo '</div>';

echo '</main>';

close_html();
?>