<?php
/*
Name: demo_functions.php
Description: has all the functions needed for the demo in one place
Programmers: Brent Zucker, Jon Self (editted)
Dates: (3/12/15,
Names of files accessed: include.php
Names of files changed:
Input:
Output:
Error Handling:
Modification List:

4/8/2015: Selective edits
*/
require_once(__DIR__.'/../include.php');

//This function echos the links on the home page
function echoHomePageLinks()
{
	echo '<ul>';
	echo '<li><h3><a href="ClockinDemo/clock.php">Clock Into Work</a></li>';
	echo '<li><h3><a href="ReportsDemo/select_report.php">View Reports</a></li>';
	echo '<li><h3><a href="ManageDevelopersDemo/manage_developers.php">Manage Developers</a></li>';
	echo '<li><h3><a href="ManageClientsDemo/manage_clients.php">Manage Clients</a></li>';
	echo '<li><h3><a href="MyAccountDemo/MyAccount.php">My Account</a></li>';
	echo '<li><h3><a href="edit_time_sheet.php">Edit Time Sheet</a></li>';
	echo '<li><h3><a href="ManageClientsDemo/view_client_profiles.php">View Client Profiles</a></li>';
	echo '</ul>';
}

//This function echos the links on the manage clients page
function echoManageClientsLinks()
{
	echo '<ul>';
	echo '<li><h3><a href="purchase_hours.php">Add Purchased Hours</a></li>';
	echo '<li><h3><a href="new_client.php">New Client</a></li>';
	echo '<li><h3><a href="new_project.php">New Project</a></li>';
	echo '<li><h3><a href="new_task.php">New Task</a></li>';
	echo '<li><h3><a href="edit_client.php">Edit Client</a></li>';
	echo '<li><h3><a href="edit_project.php">Edit Project</a></li>';
	echo '<li><h3><a href="edit_task.php">Edit Task</a></li>';
	echo '<li><h3><a href="delete_client.php">Delete Client</a></li>';
	echo '<li><h3><a href="delete_project.php">Delete Project</a></li>';
	echo '<li><h3><a href="delete_task.php">Delete Task</a></li>';
	echo '</ul>';
	echo <<<END
	<br>
	<a href='../home.php'>Back to Home</a>
END;
}

//This function echos the links on the manage developers page
function echoManageDevelopersLinks()
{
	echo '<ul>';
	echo '<li><h3><a href="create_developer.php">Create Developer</a></li>';
	echo '<li><h3><a href="assign_client.php">Assign Client</a></li>';
	echo '<li><h3><a href="assign_project.php">Assign Projects</a></li>';
	echo '<li><h3><a href="assign_task.php">Assign Task</a></li>';
	echo '<li><h3><a href="unassign_client.php">Unassign Client</a></li>';
	echo '<li><h3><a href="unassign_project.php">Unassign Projects</a></li>';
	echo '<li><h3><a href="unassign_task.php">Unassign Task</a></li>';
	echo '<li><h3><a href="view_all_assignments.php">View All Assignments</a></li>';
	echo '</ul>';
echo <<<END
	<br>
	<a href='../home.php'>Back to Home</a>
END;
}

//This function echos the links on the my account page
function echoMyAccountLinks()
{
	echo '<ul>';
	echo '<li><h3><a href="info.php">Update info</a></li>';
	echo '<li><h3><a href="email.php">Update email</a></li>';
	echo '<li><h3><a href="password.php">Update password</a></li>';
	echo '<li><h3><a href="avatar.php">Update avatar</a></li>';
	echo '<li><h3><a href="alerts.php">Update alerts</a></li>';
	echo '<li><h3><a href="delete_account.php">Delete account</a></li>';
	echo '</ul>';
	echo <<<END
		<br>
		<a href='../home.php'>Back to Home</a>
END;
}

/* Functions below warn the user with alerts.
 *
 */

//This function consumes an amount of days and returns clients who have less days left on their contracts
function warningExpiringContracts($minimum_days_left)
{
	foreach($_SESSION['Developer']->getClientList() as $client)
		if($minimum_days_left >= $client->getContractDaysLeft())
			echo $client->getClientname() . " has " . $client->getContractDaysLeft() . " days left on its contract.<br>";
}

//This function consumes an amount of hours and returns clients who have less hours left on their contract
function warningLowHours($minimum_time_left)
{
	foreach($_SESSION['Developer']->getClientList() as $client)
		if($minimum_time_left >= $client->getHoursLeft())
			echo $client->getClientname() . " has " . $client->getTimeLeftFormatted() . "" . " much time left on their contract.<br>";
}

/* Functions that create dropdown selectors
 *
 */

//This function consumes the superUser echos a dropdwon selector for the Teams in the Database
function teamDropDown($superUser)
{
	$teams = $superUser->getTeams();

	echo '<select name="Team_Selected">';
	foreach($teams as $t)
		echo '<option value="' . $t . '">' . $t . '</option>';
	echo '</select>';
	echo '<input type="submit" value="Submit">';
}

//This function gets passed a Developer and echos a dropdwon selector for the Developer's developer List
function developerDropDown($developer)
{
	$developers = $developer->getDevelopers();

	echo '<select name="Developer_Selected">';
	foreach($developers as $d)
		echo '<option value="' . $d->getUsername() . '">' . $d->getUsername() . '</option>';
	echo '</select>';
	echo '<input type="submit" value="Submit">';
}

//This function gets passed a Developer and echos a dropdwon selector for the Developer's Client List
function clientDropDown($Developer)
{
	echo '<select name="Client_Selected" class="form-control client-select" id="sel1" >';

	if(!isset($_POST['Client_Selected']))
		foreach($Developer->getClientList() as $client)
			echo '<option value="' . $client->getClientname() . '">' . $client->getClientname() . '</option>';
	else
		foreach($Developer->getClientList() as $client)
			if($_POST['Client_Selected'] == $client->getClientname())
				echo '<option selected="selected" value="' . $client->getClientname() . '">' . $client->getClientname() . '</option>';
			else
				echo '<option value="' . $client->getClientname() . '">' . $client->getClientname() . '</option>';

	echo '</select>';
	echo '<br>';
	echo '<input type="submit" class="btn btn-primary" value="Submit" >';
	echo '<br>'; 

}

//This function gets passed a developer and client, and echos a dropdown selector for the list of projects that are assigned to that developer and that client
function projectDropDown($developer, $clientname)
{
	echo '<select name="Project_Selected">';

	foreach($developer->getClientsProjectsAssigned($clientname) as $p)
		echo '<option value="' . $p->getProjectID() . '">' . $p->getProjectName() . '</option>';

	echo '</select>';
	echo '<input type="submit" value="Submit">';
}

//This function gets passed a developer and a projectid, and echos a dropdown selector for the list of tasks that are assigned to that developer and that project
function taskDropDown($developer, $projectid)
{
	echo '<select name="Task_Selected">';
	foreach($developer->getProjectsTasksAssigned($projectid) as $t)
		echo '<option value="' . $t->getTaskID() . '">' . $t->getTaskName() . '</option>';
	echo '</select>';
	echo '<input type="submit" value="Submit">';
}

/* These functions help with the javascript drop down selectors
 *
 */

//This function echos 3 javascript functions that read php encoded with json
function jsFunctions()
{
	echo '<script>';

	//This function get the developer projects array from php
	echo 'function getDeveloperProjects()';
	echo '{';
				//Get developer project list from php
	echo 	'var developer_projects = ' . json_encode( projectListToArray( $_SESSION["Developer"]->getProjectList() )) . ';';
	echo 	'return developer_projects;';
	echo '}';

	echo 'function getDeveloperTasks()';
	echo '{';
				//Get developer task list from php
	echo 	'var developer_tasks = ' . json_encode( taskListToArray( $_SESSION['Developer']->getTaskList() )) . ';';
	echo 	'return developer_tasks;';
	echo '}';

	//This function gets the array of clients with their projects array from php (clientName => projectArray)
	echo 'function getClientProjects()';
	echo '{';
				//get the clients project lists
	echo 	'var client_project_array = ' . json_encode( clientListToArrayOfProjectLists() ) . ';';
	echo 	'return client_project_array;';
	echo '}';

	echo '</script>';
}

//This function converts a list of client objects to an array list of client project arrays
function clientListToArrayOfProjectLists()
{
	$client_project_array = array();
	foreach($_SESSION['Developer']->getClientList() as $clientObject)
		$client_project_array[ $clientObject->getClientname() ] = projectListToArray( $clientObject->getProjects() );
		
	return $client_project_array;
}

//This function converts a list of task objects to an assoicative array of tasks
function taskListToArray($taskObjectList)
{
	$task_array = array();

	foreach($taskObjectList as $taskObject)
		$task_array[ $taskObject->getTaskID() ] = $taskObject->getTaskName();

	return $task_array;
}

//This function converts a list of project objects to an associative array of projects
function projectListToArray($projectObjectList)
{
	$project_array = array();

	foreach($projectObjectList as $projectObject)
	{
		$project_array[ $projectObject->getProjectID() ] = array();
		$project_array[ $projectObject->getProjectID() ]['ProjectName'] = $projectObject->getProjectName();
		$project_array[ $projectObject->getProjectID() ]['TaskList'] = taskListToArray( $projectObject->getTaskList() );
	}
		
	return $project_array;
}

/* These functions query the date
 *
 */

//This function echos 2 inputs for a form. A startdate and an enddate.
function dateSelector()
{
	$today = date('Y-m-d');

	echo '<br>Start Date:<br>';

	//Saves the date in the in selector view
	if(!isset($_POST['startdate']))
		echo '<input type="date" name="startdate" value="2015-01-01">';
	else
		echo '<input type="date" name="startdate" value="' . $_POST['startdate'] . '">';

	echo '<br>End Date:<br>';

	if(!isset($_POST['enddate']))
		echo '<input type="date" name="enddate" value="' . $today. '">';
	else
		echo '<input type="date" name="enddate" value="' . $_POST['enddate'] . '">';
}

/* These functions print out profiles 
 *
 */

function getClientProfile($clientName)
{
	echo '<h2>' . $clientName . '</h2>';

	//Print Client Contact information
	echo '<h3>Contact Info</h3>';
	printClientContactTable($clientName);

	//Print Client Contract Information
	echo '<h3>Contract Info</h3>';
	echo '<h4>Hours Left</h4>';
	printHoursLeftTable($clientName);

	echo '<h4>Client\'s Purchases</h4>';
	printClientsPurchasesTable($clientName);

	//Projects 
	echo '<h3>Projects</h3>';
	printProjects($clientName);

	//Tasks
	echo '<h3>Tasks</h3>';
	printTasks($clientName);

	//Assigned Developers
	echo '<h3>Assigned Developers</h3>';
	printDevelopersAssignedToClient($clientName);

	//Grouped Developers by Time
	echo '<h3>Developers Time Sheet</h3>';
	printAggregatedTimeLogTableByClient($clientName,0,0);
}

/* Forms dependent on Developer Selection
 *
 */

//This function consumes a session variable to store values in and echos forms based on preceding selections.
function developerClientDropdownForm($session_variable)
{
	echo '<h2>Select a Developer</h2>';

	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo '</form>';

	if(isset($_POST['Developer_Selected']) || isset($_SESSION["$session_variable"]['developer']))
	{
		//Check if Developer selected has been changed
		if(isset($_POST['Developer_Selected']) && $_SESSION["$session_variable"]['developer'] != $_POST['Developer_Selected'])
		{
			unset($_SESSION["$session_variable"]['client']);
		}

		if(isset($_POST['Developer_Selected']))
			$_SESSION["$session_variable"]['developer'] = $_POST['Developer_Selected'];

		echo '<h4>' . $_SESSION["$session_variable"]['developer'] . ' was selected</h4>';

		echo '<h2>Select a Client</h2>';

		echo '<form action="" method="POST">';

		//Select a Client that is assigned to the team
		if($session_variable == 'assign')
			clientDropDown( new Team( $_SESSION['Developer']->getTeam() ) );
		elseif($session_variable == 'unassign')
			clientDropDown( new Developer($_SESSION["$session_variable"]['developer']) );
		else 
			clientDropDown( new Developer($_SESSION["$session_variable"]['developer']) );

		echo '</form>';

		if(isset($_POST['Client_Selected']))
			$_SESSION["$session_variable"]['client'] = $_POST['Client_Selected'];
	}
}

//This function consumes a session variable to store values in and echos forms based on preceding selections.
function developerClientProjectDropdownForm($session_variable)
{
	echo '<h2>Select a Developer</h2>';

	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo '</form>';

	//If a Developer has been selected Load the next Drop Down
	if(isset($_POST['Developer_Selected']) || isset($_SESSION["$session_variable"]['developer']))
	{
		//If the developer selection is changed
		if(isset($_POST['Developer_Selected']) && $_POST['Developer_Selected'] != $_SESSION["$session_variable"]['developer'])
		{
			unset($_SESSION["$session_variable"]['Client_Selected']);
			unset($_SESSION["$session_variable"]['Project_Selected']);
		}

		if(isset($_POST['Developer_Selected']))
			$_SESSION["$session_variable"]['developer'] = $_POST['Developer_Selected'];

		echo '<h2>' . $_SESSION["$session_variable"]['developer'] . ' was selected.</h2>';

		clientProjectDropdownForm($session_variable);
	}
}

//This function consumes a session variable to store values in and echos forms based on preceding selections.
function developerClientProjectTaskDropdownForm($session_variable)
{
	//If a Developer has been selected Load the next Drop Down
	if(isset($_POST['Developer_Selected']) || isset($_SESSION["$session_variable"]['developer']))
	{
		//If the developer selection is changed
		if(isset($_POST['Developer_Selected']) && $_POST['Developer_Selected'] != $_SESSION["$session_variable"]['developer'])
		{
			unset($_SESSION["$session_variable"]['client']);
			unset($_SESSION["$session_variable"]['project']);
			unset($_SESSION["$session_variable"]['task']);
		}

		if(isset($_POST['Developer_Selected']))
			$_SESSION["$session_variable"]['developer'] = $_POST['Developer_Selected'];

		echo '<h2>' . $_SESSION["$session_variable"]['developer'] . ' was selected.</h2>';

		clientProjectTaskDropdownForm("$session_variable");
	}
}

/* Forms dependent on Client Selection
 *
 */

//This function consumes a session name variable and a developer name and displays dropdowns for a client and a project
function clientProjectDropDownForm($session)
{
	echo '<form action="" method="POST">';
	echo '<h2>Select a Client</h2>';

	//if this is an assignmnet load the teams assignments not the developers
	if($session == 'assign')
		clientDropDown( new Team( $_SESSION['Developer']->getTeam() ) );
	elseif($session == 'unassign')
		clientDropDown( new Developer($_SESSION['unassign']['developer']) );
	else
		clientDropDown($_SESSION['Developer']);
		
	echo '</form>';

	if(isset($_POST['Client_Selected']) || isset($_SESSION["$session"]['Client_Selected']))
	{
		if(isset($_POST['Client_Selected']))
		{
			$_SESSION["$session"]['Client_Selected'] = $_POST['Client_Selected'];
			unset($_SESSION["$session"]['project']);
		}

		echo '<h2>' . $_SESSION["$session"]['Client_Selected'] . ' was selected.</h2>';

		echo '<form action="" method="POST">';
		echo '<h2>Select a Project</h2>';

		//if this is an assignmnet load the teams assignments not the developers
		if($session == 'assign') 
			projectDropDown( new Team( $_SESSION['Developer']->getTeam() ) , $_SESSION["$session"]['Client_Selected']);
		elseif($session == 'unassign')
			projectDropDown( new Developer($_SESSION['unassign']['developer']), $_SESSION["$session"]['Client_Selected']);
		else
			projectDropDown($_SESSION['Developer'], $_SESSION["$session"]['Client_Selected']);
		
		echo '</form>';
	}
}

//This function consumes a session variable to store values in and echos forms based on preceding selections.
function clientProjectTaskDropdownForm($session_variable)
{
	echo '<h3>Select a Client</h3>';
	echo '<form action="" method="POST">';
	
	//if this is an assignmnet load the teams assignments not the developers
	//Select a Client that is assigned to the team
	if($session_variable == 'assign')
		clientDropDown( new Team( $_SESSION['Developer']->getTeam() ) );
	elseif($session_variable == 'unassign')
		clientDropDown( new Developer($_SESSION["$session_variable"]['developer']) );
	else 
		clientDropDown( $_SESSION['Developer'] );
	echo "</form>";

	if(isset($_POST['Client_Selected']) || isset($_SESSION["$session_variable"]['client']))
	{
		//If the client selection is changed
		if(isset($_POST['Client_Selected']) && $_POST['Client_Selected'] != $_SESSION["$session_variable"]['client'])
		{
			unset($_SESSION["$session_variable"]['project']);
			unset($_SESSION["$session_variable"]['task']);
		}

		//Store the Client selected in the report session
		if(isset($_POST['Client_Selected']))
			$_SESSION["$session_variable"]['client'] = $_POST['Client_Selected'];

		echo '<h2>' . $_SESSION["$session_variable"]['client'] . ' was selected</h2>';

		echo '<h3>Select a Project</h3>';
		echo '<form action="" method="POST">';

		//if this is an assignmnet load the teams assignments not the developers
		if($session_variable == 'assign') 
			projectDropDown( new Team( $_SESSION['Developer']->getTeam() ) , $_SESSION["$session_variable"]['client']);
		elseif($session_variable == 'unassign')
			projectDropDown( new Developer($_SESSION['unassign']['developer']), $_SESSION["$session_variable"]['client']);
		else
			projectDropDown($_SESSION['Developer'], $_SESSION["$session_variable"]['client']);
		echo "</form>";

		if(isset($_POST['Project_Selected']) || isset($_SESSION["$session_variable"]['project']))
		{
			//If the project selection is changed
			if(isset($_POST['Project_Selected']) && $_POST['Project_Selected'] != $_SESSION["$session_variable"]['project'])
				unset($_SESSION["$session_variable"]['task']);

			//Store the project selected in the report session
			if(isset($_POST['Project_Selected']))
				$_SESSION["$session_variable"]['project'] = $_POST['Project_Selected'];

			echo '<h2>' . $_SESSION["$session_variable"]['project']  . ' was selected</h2>';

			echo '<h3>Select a Task</h3>';
			echo '<form action="" method="POST">';
			
			//if this is an assignmnet load the teams assignments not the developers
			if($session_variable == 'assign')
				taskDropDown( new Team( $_SESSION['Developer']->getTeam() ), $_SESSION["$session_variable"]['project']);
			elseif($session_variable == 'unassign')
				taskDropDown( new Developer($_SESSION['unassign']['developer']), $_SESSION["$session_variable"]['project']);
			else 
				taskDropDown($_SESSION['Developer'], $_SESSION["$session_variable"]['project']);
			echo '</form>';

			if(isset($_POST['Task_Selected']) || isset($_SESSION["$session_variable"]['task']))
			{
				//Store the project selected in the report session
				if(isset($_POST['Task_Selected']))
					$_SESSION["$session_variable"]['task'] = $_POST['Task_Selected'];
			}
		}
	}
}

/* Print table functions
 *
 */

//This function consumes a query and table headers and prints out the results in a table
function printTable($query, $table_headers)
{
	echo '<table style="border:1px solid black; text-align:center;">';

	echo '<tr>';
	foreach($table_headers as $t_h)
		echo '<th>' . $t_h . '</th>';
	echo '</tr>';

	if($result = db_query($query))
	{
		while($row = mysqli_fetch_row($result))
		{
			echo '<tr>';
			foreach($row as $r)
				echo "<td style=\"border:1px solid black;padding:5px;\">$r</td>";
			echo '</tr>';
		}
	}
	echo '</table>';
	mysqli_free_result($result);
}


//This function consumes a taskid and echos the timeLog table for the specific task. Displays by TimeLogID Descending and limits 10 results
function printTimeSheetTableByTask($task)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, t.ProjectID, p.ProjectName, t.TaskID, Tasks.TaskName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p, Tasks WHERE (t.ProjectID = p.ProjectID AND t.ProjectID = Tasks.ProjectID) AND t.TaskID=" . $task . " ORDER BY TimeLogID DESC LIMIT 10";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'ProjectID', 'Project Name', 'TaskID', 'TaskName', 'Time In', 'Time Out', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a develper username and echos the timeLog table for the specific developer
function printTimeLogTableByDeveloper($developer, $startdate, $enddate)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, t.ProjectID, p.ProjectName, t.TaskID, Tasks.TaskName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p, Tasks WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.ProjectID AND t.ProjectID = Tasks.ProjectID) AND t.Username='" . $developer ."'";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'ProjectID', 'Project Name', 'TaskID', 'Task Name', 'Time In', 'Time Out', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a developer username and echos an aggregated view of the TimeSheet table with a sum of timespent and grouped by client names
function printAggregatedTimeLogTableByDeveloper($developer, $startdate, $enddate)
{
	$query = "SELECT t.Username, t.ClientName, SUM(t.TimeSpent) FROM TimeSheet t WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND t.Username='" . $developer ."'GROUP BY t.ClientName";

	$table_headers = array('Username', 'Client', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a client name and echos the timeLog table for the specific developer
function printTimeLogTableByClient($client, $startdate, $enddate)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND t.ClientName='" . $client ."'";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'Time in', 'Time Out', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a client name and echos an aggregated view of the TimeSheet table with a sum of timespent and grouped by client names
function printAggregatedTimeLogTableByClient($client, $startdate, $enddate)
{
	if($startdate != 0 && $endate != 0)
		$query = "SELECT t.ClientName, t.Username, SUM(t.TimeSpent) FROM TimeSheet t WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND t.ClientName='" . $client ."'GROUP BY t.Username";
	else
		$query = "SELECT t.ClientName, t.Username, SUM(t.TimeSpent) FROM TimeSheet t WHERE t.ClientName='" . $client ."'GROUP BY t.Username";

	$table_headers = array('Client', 'Username', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a projectid and echos an aggregated view of the TimeSheet table with a sum of timespent and grouped by developers names
function printAggregatedTimeLogTableByProject($project, $startdate, $enddate)
{
	$query = "SELECT t.ClientName, p.ProjectName , t.Username, SUM(t.TimeSpent) FROM TimeSheet t, Projects p WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.ProjectID) AND t.ProjectID='" . $project ."' GROUP BY t.Username";

	$table_headers = array('Client', 'Project Name', 'Username', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a projectid and echos the timeLog table for the specific project
function printTimeLogTableByProject($project, $startdate, $enddate)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, p.ProjectName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.ProjectID) AND t.ProjectID='" . $project ."'";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'Project', 'Time In', 'Time Out', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a taskid and echos an aggregated view of the TimeSheet table with a sum of timespent and grouped by developers names
function printAggregatedTimeLogTableByTask($task, $startdate, $enddate)
{
	$query = "SELECT t.ClientName, p.ProjectName, a.TaskName, t.Username, SUM(t.TimeSpent) FROM TimeSheet t, Projects p, Tasks a WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.ProjectID AND a.TaskID=t.TaskID) AND t.TaskID='" . $task ."' GROUP BY t.Username";

	$table_headers = array('Client', 'Project Name', 'Task Name', 'Username', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a taskid and echos the timeLog table for the specific task
function printTimeLogTableByTask($task, $startdate, $enddate)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, p.ProjectName, a.TaskName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p, Tasks a WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.projectID AND a.TaskID=t.TaskID) AND t.TaskID='" . $task ."'";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'Project', 'Task', 'Time In', 'Time Out', 'Time Spent');

	printTable($query, $table_headers);
}

//This function consumes a client name and echos a view of the ClientPurchases table
function printHoursLeftTable($client)
{
	$query = 'SELECT p.ClientName, c.StartDate, SUM(p.HoursPurchased), COUNT(p.PurchaseDate), c.HoursLeft FROM ClientPurchases p, Client c WHERE (c.ClientName = p.ClientName) AND c.ClientName="' . $client . '"';

	$table_headers = array('Client', 'Start Date', 'Hours Purchased', 'Purchases', 'Hours Left');

	printTable($query, $table_headers);
}

function printClientsPurchasesTable($client)
{
	$query = 'SELECT p.ClientName, p.HoursPurchased, p.PurchaseDate FROM ClientPurchases p WHERE p.ClientName="' . $client . '"';

	$table_headers = array('Client', 'Hours Purchased', 'Purchase Date');

	printTable($query, $table_headers);
}

//This function consumes a developer username and echos an Assignment table for the specific developer
function printAssignmentsTable($developer)
{
	$query = "SELECT * FROM DeveloperAssignments WHERE Username='" . $developer ."'";

	$table_headers = array('Username', 'Client/Project/Task', 'Type');

	printTable($query, $table_headers);
}

//This function consumes a developer username and echos their client Assignment table for the specific developer
function printAssignmentsTableClient($developer)
{
	$query = "SELECT Username, ClientProjectTask FROM DeveloperAssignments WHERE Type='Client' AND Username='" . $developer ."'";
	$table_headers = array('Username', 'Clients');

	printTable($query, $table_headers);
}

//This function consumes a developer username and echos their Project Assignment table for the specific developer
function printAssignmentsTableProject($developer)
{
	$query = "SELECT Username, ProjectName FROM DeveloperAssignments, Projects WHERE ClientProjectTask=ProjectID AND Type='Project' AND Username='" . $developer ."'";

	$table_headers = array('Username', 'Projects');

	printTable($query, $table_headers);
}

//This function consumes a developer username and echos their Task Assignment table for the specific developer
function printAssignmentsTableTask($developer)
{
	$query = "SELECT Username, TaskName FROM DeveloperAssignments, Tasks WHERE ClientProjectTask=TaskID AND Type='Task' AND Username='" . $developer ."'";

	$table_headers = array('Username', 'Tasks');

	printTable($query, $table_headers);
}

function printClientContactTable($client)
{
	$query = "SELECT * FROM ClientContact WHERE ClientName='$client'";
	$table_headers = array('Client Name', 'First Name', 'Last Name', 'Phone', 'Email', 'Address', 'City', 'State');
	printTable($query, $table_headers);
}

function printProjects($client)
{
	$query = "SELECT ProjectName, Description FROM Projects WHERE ClientName='$client'";
	$table_headers = array('Project Name', 'Description');
	printTable($query, $table_headers);
}

function printTasks($client)
{
	$query = "SELECT TaskName, Description FROM Tasks WHERE ClientName='$client'";
	$table_headers = array('Task Name', 'Description');
	printTable($query, $table_headers);
}

function printDevelopersAssignedToClient($client)
{
	$query = "SELECT Username FROM DeveloperAssignments WHERE (Type='Client') AND ClientProjectTask='" . $client ."'";
	$table_headers = array('Developer');
	printTable($query, $table_headers);
}

/* The below functions print editable tables.
 *
 */

//This function consumes a query and table headers and prints out the results in a table
function printTableEditColumn($query, $table_headers)
{
	echo '<table style="border:1px solid black; text-align:center;">';

	echo '<tr>';
	foreach($table_headers as $t_h)
		echo '<th>' . $t_h . '</th>';
	echo '</tr>';

	if($result = db_query($query))
	{
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo '<tr>';
			foreach($row as $r)
				echo "<td style=\"border:1px solid black;padding:5px;\">$r</td>";

			echo '<td>';
			//Print the radio button
			echo '<input type="radio" name="TimeLogID" value="' . $row['TimeLogID'] . '">';
			echo '</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
	mysqli_free_result($result);
}

//This function consumes a develper username, startdate, and enddate and echos an editable table for the specific developers time sheet
function editTimeLogTableByDeveloper($developer, $startdate, $enddate)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, t.ProjectID, p.ProjectName, t.TaskID, Tasks.TaskName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p, Tasks WHERE (t.TimeIn BETWEEN '$startdate' AND '$enddate') AND (t.ProjectID = p.ProjectID AND t.ProjectID = Tasks.ProjectID) AND t.Username='" . $developer ."'";

	$table_headers = array('TimeLogID', 'Username', 'Client', 'ProjectID', 'Project Name', 'TaskID', 'Task Name', 'Time In', 'Time Out', 'Time Spent', 'Edit');

	printTableEditColumn($query, $table_headers);
}

//This function prints out a 1 row table that allows you to edit the selected row via text boxes
function editTable($query, $table_headers)
{
	echo '<table style="border:1px solid black; text-align:center;">';

	echo '<tr>';
	foreach($table_headers as $t_h)
		echo '<th>' . $t_h . '</th>';
	echo '</tr>';

	if($result = db_query($query))
	{
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo '<tr>';
			foreach($row as $k=>$v)
			{
				echo '<td style=\"border:1px solid black;padding:5px;\">';
				if($k == 'TimeLogID' || $k == 'Username' || $k == 'ClientName' || $k == 'ProjectName' || $k == 'TaskName' || $k == 'TimeIn')
					echo '<label>' . $v . '</label>';
			}

			//In order to have the timeout be the default value in the datetime selector, a "T" must be before the time
			echo '<input type="datetime-local" name="TimeOut" min="' . substr_replace($row['TimeIn'], "T", 10, 1) . '" value="' . substr_replace($row['TimeOut'], "T", 10, 1) . '">';
			echo '<input type="number" name="TimeSpent" value="' . $row['TimeSpent'] . '">';
			echo '</td>';

			echo '</tr>';
		}
	}
	echo '</table>';
	mysqli_free_result($result);
}

//This function creates a query for a row in timesheet that has a matching time log. It then calls edit table
function editTimeLogByID($timeLogID)
{
	$query = "SELECT t.TimeLogID, t.Username, t.ClientName, p.ProjectName, Tasks.TaskName, t.TimeIn, t.TimeOut, t.TimeSpent FROM TimeSheet t, Projects p, Tasks WHERE (t.TaskID=Tasks.TaskID AND t.ProjectID=p.ProjectID) AND t.TimeLogID='" . $timeLogID ."'";
	$table_headers = array('TimeLogID', 'Username', 'Client', 'Project Name', 'Task Name', 'Time In', 'Time Out', 'Time Spent');

	editTable($query, $table_headers);
}

/*
 *
 */

//This function consumes a developer and a taskid, echos a clockin/clockout form and handles the forms action (recording the developer's clockin/clockout)
function clockForm($developer, $taskid)
{
	//If the clockin button was submitted, record the time
	if(isset($_POST['clockin']))
		$developer->clockIn($taskid);

	//If the clockout button was submitted, record the time
	if(isset($_POST['clockout']))
		$developer->clockOut();

	echo '<form action="" method="POST">';
	echo '<input type="submit" name="clockin" value="Clock In">';
	echo '<input type="submit" name="clockout" value="Clock Out">';
	echo '</form>';
}

//This function echos the contact input fields for a form.
function echoContactInput()
{
	echo<<<END
	<br>Firstname:<br>
	<input type="text" name="firstname">
	<br>Lastname:<br>
	<input type="text" name="lastname">
	<br>Phone:<br>
	<input type="text" name="phone">
	<br>Email:<br>
	<input type="text" name="email">
	<br>Address:<br>
	<input type="text" name="address">
	<br>City:<br>
	<input type="text" name="city">
	<br>State:<br>
	<select name="state">
	<option value="">Select your state</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="GU">Guam</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="PR">Puerto Rico</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
	</select>
	<br>
END;
}

//This function echos a form to update a pre existing client by changing the client's contact information via editClient method
function editClientForm($developer)
{

	$teamError = $clientError = $firstnameError = $lastnameError = $phoneError = $emailError = $addressError = $cityError = $stateError = "";

	echo '<form action="" method="POST">';
	echo '<h2>Select a Client</h2>';
	clientDropDown($developer);
	echo '</form>';

	$client;
	if(isset($_POST['Client_Selected']))
	{
		$client = $_POST['Client_Selected'];
	}
	if (isset($_POST['Client_Selected']))
	{
		if (isset($_POST['editClientSubmit']))
		{
			$validated = false;
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(!empty($_POST['firstname'] && $_POST['lastname'] && $_POST['phone'] && $_POST['email'] && $_POST['address'] && $_POST['city'] && $_POST['state']))
				{
					$firstname = $_POST['firstname'];
					$lastname = $_POST['lastname'];
					$phone = $_POST['phone'];
					$email = $_POST['email'];
					$address = $_POST['address'];
					$city = $_POST['city'];
					$state = $_POST['state'];
					$validated= true;
				}
				//isset($_POST['clientname']) && isset($_POST['startdate'])
				if($validated)
				{
					$developer->editClient($client, $firstname, $lastname, $phone, $email, $address, $city, $state);
					echo "<h1> $client was updated!</h1>";
				}
			}
		}
		echo '<form action="" method="POST">';
		echoContactInput();
		echo <<<END
		<br/>
		<input type="submit" name="editClientSubmit" value="Edit Client">
		<input type="hidden" name="Client_Selected" value="$client">
		</form>
		<br>
		<a href="manage_clients.php">Back</a>
END;
	}
}
//BELOW:
//UNFINISHED -- NEEDS WORK

function removeProjectForm($session, $developer)
{
	echo '<form action="" method="POST">';
	echo "<h2>Select a Client</h2>";
	clientDropDown($developer);
	echo"</form>";

	if(isset($_POST['Client_Selected']) || isset($_SESSION[$session]['Client_Selected']))
	{
		if(isset($_POST['Client_Selected']))
			$_SESSION[$session]['Client_Selected'] = $_POST['Client_Selected'];

		echo '<h2>' . $_SESSION[$session]['Client_Selected'] . ' was selected.</h2>';

		echo "Select a Project";
		echo '<form action="" method="POST">';
		projectDropDown($developer, $_SESSION[$session]['Client_Selected']);
		echo "</form>";

		if(isset($_POST['Project_Selected']))
		{
			$_SESSION[$session]['Project_Selected'] = $_POST['Project_Selected'];

			echo '<h2>' . $_SESSION[$session]['Project_Selected'] . ' was selected.</h2>';

			echo '<h2>Do you want to delete this project?</h2>';
			echo '<form action="" method="POST">';
			echo '<input type="Submit" name="removeproject" value="Delete">';
			echo '</form>';
		}

		if(isset($_POST['removeproject']))
		{

			echo '<h1>' . $_POST['Project_Selected'] . ' is deleted</h1>';
			$desc = $developer->getDescription();
			deleteProject($_SESSION['Client_selected'], $_SESSION['Project_selected'], $desc);
		}
		}
}
function removeTaskForm($session, $developer)
{
	echo '<form action="" method="POST">';
	echo "<h2>Select a Client</h2>";
	clientDropDown($developer);
	echo"</form>";

	if(isset($_POST['Client_Selected']) || isset($_SESSION[$session]['Client_Selected']))
	{
		if(isset($_POST['Client_Selected']))
			$_SESSION[$session]['Client_Selected'] = $_POST['Client_Selected'];

		echo '<h2>' . $_SESSION[$session]['Client_Selected'] . ' was selected.</h2>';

		echo "Select a Project";
		echo '<form action="" method="POST">';
		projectDropDown($developer, $_SESSION[$session]['Client_Selected']);
		echo "</form>";

		if(isset($_POST['Project_Selected']))
		{
			$_SESSION[$session]['Project_Selected'] = $_POST['Project_Selected'];

			echo '<h2>' . $_SESSION[$session]['Project_Selected'] . ' was selected.</h2>';

			echo "Select a Task";
			echo '<form action="" method="POST">';
			taskDropDown($developer, $_SESSION[$session]['Project_Selected']);
			echo '</form>';

		if(isset($_POST['Task_Selected']))
		{
			$_SESSION[$session]['Task_Selected'] = $_POST['Task_Selected'];

			echo '<h2>' . $_SESSION[$session]['Task_Selected'] . ' was selected.</h2>';

			echo '<h2>Do you want to delete this task?</h2>';
			echo '<form action="" method="POST">';
			echo '<input type="Submit" name="removetask" value="Delete">';
			echo '</form>';
		}

		if(isset($_POST['removetask']))
		{

			echo '<h1>' . $_POST['Task_Selected'] . ' is deleted</h1>';
			deleteTask($_SESSION['Client_selected'], $_SESSION['Project_selected'], $_SESSION['Task_selected']);
		}
		}
}
}
function delClient($session, $developer)
{
	echo '<form action="" method="POST">';
	echo '<h2>Remove a Client</h2>';
	clientDropDown($developer);
	echo '</form>';

	if(isset($_POST['Client_Selected']) || isset($_SESSION[$session]['Client_Selected']))
	{
		if(isset($_POST['Client_Selected']))
			$_SESSION[$session]['Client_Selected'] = $_POST['Client_Selected'];

		echo '<h2>' . $_SESSION[$session]['Client_Selected'] . ' was deleted.</h2>';

		deleteClient($_SESSION[$session]['Client_Selected']);
	}
}
/*
 * The below functions deal with printing to excel
 */

//this function prevents corruption of the excel file
function cleanData(&$str)
{
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}
//this function can handle a single query to the database and prints the result in an excel spreadsheet
function excel($query)
{
	// filename for download
	$filename = "website_data_" . date('Ymd') . ".xls";

	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");

	$flag = false;
	$result = db_query($query) or die('Query failed!');
	while($row = mysqli_fetch_assoc($result))
	{
		if(!$flag) {
			// display field/column names as first row
			echo implode("\t", array_keys($row))."\r\n";
			$flag = true;
		}
		array_walk($row, 'cleanData');
		echo implode("\t", array_values($row))."\r\n";
	}
}
?>