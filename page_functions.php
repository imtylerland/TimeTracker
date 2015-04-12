<?php
/*
Name: page_functions.php
Description: sets the style for the different parts of the web application
Programmers: Ryan Graessle
Dates: (3/23/15, 
Names of files accessed: include.php
Names of files changed:
Input: 
Output:
Error Handling:
Modification List: 
3/23/15-Initial code up
3/29/15-Created footer, JQuery scripts, and changed styles
4/2/15-Styled sidebar, fixed bugs
*/

require_once(__DIR__.'/include.php');


//makes the header for each page
function html_header($title) //Function to load the header of the webpage. Takes in the title of the page.
{
echo<<<_END

<html>
	<head>
		<title>$title</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="jsfunctions.js"></script>
	</head>
	
	<div class="body">
		<div class="header-wrap">
			<div class="logo">
				<a><img src="http://placehold.it/350x150"></a>
			</div>
			
			<br /><br />
			
			<div class="menu">
				<ul>
					<li><a href="demo_login.php">Home</a></li>
					<li><a href="select_client.php">Clock In</a></li>
					<li><a href="select_report.php">Reports</a></li>
					<li><a href="assign_developer.php">Manage Developers</a></li>
					<li><a href="manage_clients.php">Manage Clients</a></li>
				</ul>
			</div>
		</div>
		
		<br />
	</div>
	<body>
_END;

}

//creates sidebar
function open_sidebar()
{
echo<<<_END
<div class="sidebar-widget">
_END;
}

//closes sidebar
function close_sidebar()
{
echo<<<_END
</div>
_END;
}


//creates footer
function html_footer() //Function to load the footer of the webpage and close out the HTML.
{

$year = date("Y");

echo<<<_END
	<br />
	
	<div class="footer">
		<p>Development Management System &copy; $year</p>
	</div>

	</body>
</html>

_END;
}

?>