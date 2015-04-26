<?php
/*
 Name: client_reports.php
 Description: shows the report for a certain client for user-picked dates
 Programmers: Ryan Graessle, Brent Zucker
 Dates: (4/18/15,
 Names of files accessed: include.php
 Names of files changed:
 Input: client (dropdown), start date (date), end date (date)
 Output: table showing the user/task/time information for client's purchased hours
 Error Handling:
 Modification List:
 4/18/15-Initial code up
 4/19/15-Migrated reports
 4/20/15-Updated button style/updated purchased hours
*/

require_once(__DIR__.'/../include.php');

session_start();

isLogin();

if(isset($_POST['client']) && isset($_POST['toExcel']))
{
	if($_POST['report'] == 'HoursLeft')
		printHoursLeftTable($_POST['client'], 'csv');
	elseif($_POST['report'] == 'ClientsPurchases')
		printClientsPurchasesTable($_POST['client'], 'csv');
	elseif($_POST['report'] == 'AggregatedTimeLogTableByClient')
		printAggregatedTimeLogTableByClient($_POST['client'], $_POST['startdate'], $_POST['enddate'], 'csv');
	elseif($_POST['report'] == 'TimeLogTableByClient')
		printTimeLogTableByClient($_POST['client'], $_POST['startdate'], $_POST['enddate'], 'csv');

}

open_html("Client Reports");


echo '<main id="page-content-wrapper">'; 
echo '<div class="col-lg-9 main-box">';
echo '<div class="jumbotron">';
echo '<div class="page-header">';
echo '<h1>Client Reports</h1>';
echo '</div>';

//This function prints out the reports tables for a client
clientReport();

echo '</div>';
echo '</div>';

alertBox();

echo '</main>';

close_html();

?>