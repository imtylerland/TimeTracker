<?php

//This function creates a form for a client and adds the purchased hours to the database
function addHours()
{
	echo '<h3>Select a Client</h3>';

	echo '<form action="" method="POST">';
	echo '<label>Hours Purchased:</label>';
	echo '<br>';
	echo '<input type="number" name="hours_purchased">';
	echo '<input type="date" name="purchase_date">';
	clientDropDown($_SESSION['Developer']);
	echo '</form>';

	if(isset($_POST['Client_Selected']) && isset($_POST['hours_purchased']) && isset($_POST['purchase_date']))
	{
		echo '<h4>' . $_POST['Client_Selected'] . ' purchase has been accounted for.</h4>';
		$purchase_seconds = $_POST['hours_purchased'] * 3600;
		//Add the purchased hours to the client
		$_SESSION['Developer']->getClient($_POST['Client_Selected'])->PurchaseHours($purchase_seconds, $_POST['purchase_date']);
	}
}

//This functino calls developerClientDropdownForm to select the client to be dispaled and assigns the client selected to the developer selected
function assignClient()
{
	developerClientDropdownForm('assign');

	if(isset($_POST['Client_Selected']) || isset($_SESSION['assign']['client']))
	{
		echo '<h4>' . $_SESSION['assign']['client'] . ' was selected.</h4>';

		//Assign the selected client to the developer (Creates a Client object and stores it in the Client_List). Makes an entry in the DeveloperAssignments Table
		$client_to_assign = new Client($_SESSION['assign']['client']);

		$developer_to_assign = new Developer($_SESSION['assign']['developer']);
		$developer_to_assign->assignClient($client_to_assign);

		printAssignmentsTableClient($_SESSION['assign']['developer']);
	}
}

//This function calls developerClientProjectDropdownForm to select the projects to be displayed and assigns the project selected to the developer selected
function assignProject()
{
	developerClientProjectDropdownForm('assign');

	if(isset($_POST['Project_Selected']))
	{
		echo '<h3>' . $_POST['Project_Selected'] . ' was selected</h3>';

		$developer = new Developer($_SESSION['assign']['developer']);

		$developer->assignProject( new Projects($_POST['Project_Selected']) );

		echo '<h1>Project: ' . $developer->getProject($_POST['Project_Selected'])->getProjectName() . ' was assigned </h1>';
	}
}

//This function calls developerClientProjectTaskDropdownForm to select the tasks to be displayed and assigns the task selected to the developer selected. 
function assignTask()
{
	echo '<h4>Select a Developer</h4>';

	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo '</form>';

	developerClientProjectTaskDropDownForm('assign');

	//If all of the drop downs have been selected, assign the task and print the table
	if(isset($_POST['Task_Selected']) || isset($_SESSION['assign']['task']))
	{
		echo '<h2>' . $_SESSION['assign']['task']  . ' was selected</h2>';

		//Assign the selected task to the developer (Creates a Task object and stores it in the Task_List). Makes an entry in the DeveloperAssignments Table
		$task_to_assign = new Tasks($_SESSION['assign']['task']);

		$developer_to_assign = new Developer($_SESSION['assign']['developer']);
		$developer_to_assign->assignTask($task_to_assign);

		printAssignmentsTable($_SESSION['assign']['developer']);
	}
}

//This function prints out the client reports tables if a client has been selected
function clientReport()
{
	//Form to select a client, start date, and end date
	echo '<form action="" method="POST">';
	clientDropDown($_SESSION['Developer']);
	dateSelector();
	echo "</form>";
	
	if(isset($_POST['Client_Selected']))
	{
		echo '<h2>' . $_POST['Client_Selected'] . ' was selected</h2>';

		echo '<h3>Hours Left</h3>';
		printHoursLeftTable($_POST['Client_Selected']);

		echo '<h3>Client\'s Purchases</h3>';
		printClientsPurchasesTable($_POST['Client_Selected']);

		echo '<h3>Developers Hours</h3>';
		printAggregatedTimeLogTableByClient($_POST['Client_Selected'], $_POST['startdate'], $_POST['enddate']);

		echo '<h3>Detailed Time Sheet</h3>';
		printTimeLogTableByClient($_POST['Client_Selected'], $_POST['startdate'], $_POST['enddate']);
	}
}

//This is the function for the Clock In page
function clock()
{
	clientProjectTaskDropdownForm('currentLog');

	if(isset($_POST['Task_Selected']) || isset($_SESSION['currentLog']['task']))
	{
		echo '<h2>' . $_SESSION['currentLog']['task']  . ' was selected</h2>';

		echo '<h3>Clock In</h3>';

		clockForm($_SESSION['Developer'], $_SESSION['currentLog']['task']);

		printTimeSheetTableByTask($_SESSION['currentLog']['task']);
	}
}

//This function deletes DeveloperASsignments and Team Assignments for a specified client.
function deleteClientForm()
{
	echo '<form action="" method="POST">';
	clientDropDown($_SESSION['Developer']);
	echo '</form>';

	if($_POST['Client_Selected'])
	{	
		$client_to_delete = new Client( $_POST['Client_Selected'] );
		$_SESSION['Developer']->deleteClient( $client_to_delete );
		echo '<h3>' . $client_to_delete->getClientname() . ' was deleted.</h3>';
	}
}

//This function deletes DeveloperAssignments and Team assignments for a specified task.
function deleteTaskForm()
{
	clientProjectTaskDropDownForm('delete');

	if(isset($_POST['Task_Selected']))
	{
		$task_to_delete = new Tasks( $_POST['Task_Selected'] );
		$_SESSION['Developer']->deleteTask( $task_to_delete );
		echo '<h3>' . $task_to_delete->getTaskName() . ' was deleted.</h3>';
	}
}

//This function deletes DeveloperAssignments and Team Assignments for a specificied project
function deleteProjectForm()
{
	clientProjectDropDownForm('delete');

	if(isset($_POST['Project_Selected']))
	{
		$project_to_delete = new Projects( $_POST['Project_Selected'] );
		$_SESSION['Developer']->deleteProject( $project_to_delete );
		echo '<h3>' . $project_to_delete->getProjectName() . ' was deleted.</h3>';
	}
}

//This function prints out the developer reports tables if a developer and date have been selected
function developerReports()
{
	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo "</form>";

	if(isset($_POST['Developer_Selected']) || isset($_SESSION['report']['developer']))
	{
		if(isset($_POST['Developer_Selected']))
			$_SESSION['report']['developer'] = $_POST['Developer_Selected'];

		echo '<form action="" method="POST">';
		dateSelector();
		echo '<br>';
		echo '<input type="submit" value="Build Report">';
		echo '</form>';

		if(isset($_POST['startdate']) && isset($_POST['enddate']))
		{
			echo '<h2>' . $_SESSION['report']['developer'] . ' was selected</h2>';
			printAggregatedTimeLogTableByDeveloper($_SESSION['report']['developer'], $_POST['startdate'], $_POST['enddate']);
			printTimeLogTableByDeveloper($_SESSION['report']['developer'], $_POST['startdate'], $_POST['enddate']);
		}
	}
}

//This function calls editClientForm after a client has been selected
function editClient()
{
	echo '<form action="" method="POST">';
	echo '<h2>Select a Client</h2>';
	clientDropDown($_SESSION['Developer']);
	echo '</form>';

	if(isset($_POST['Client_Selected']))
		editClientForm($_SESSION['Developer'], $_POST['Client_Selected']);
}

//This function prints the tables and forms and also calls functions that modify the developer to edit a record within timesheet
function editTimeSheet()
{
	echo '<form action="" method="POST">';
	dateSelector();
	echo '<br>';
	echo '<input type="submit" class="btn btn-primary" value="Submit">';
	echo '</form>';

	//If a new time out has been created, update the tables
	if((isset($_POST['startdate']) && isset($_POST['enddate'])) || (isset($_SESSION['edit']['startdate']) && isset($_SESSION['edit']['enddate']) ))
	{
		if(isset($_POST['startdate']) && isset($_POST['enddate']))
		{
			$_SESSION['edit']['startdate'] = $_POST['startdate'];
			$_SESSION['edit']['enddate'] = $_POST['enddate'];
		}

		echo '<h2>' . $_SESSION['Developer']->getUsername() . '\'s Time Sheet</h2>';

		echo '<form action="" method="POST">';
		editTimeLogTableByDeveloper($_SESSION['Developer']->getUsername(), $_SESSION['edit']['startdate'], $_SESSION['edit']['enddate']);
		echo '<input type="submit" value="Edit Time Sheet">';
		echo '</form>';
	}

	//If a new time out has been created, update the tables
	if(isset($_POST['TimeLogID']) || isset($_SESSION['edit']['timelogid']))
	{
		if(isset($_POST['TimeLogID']))
			$_SESSION['edit']['timelogid'] = $_POST['TimeLogID'];

		echo '<form action="" method="POST">';
		editTimeLogByID($_SESSION['edit']['timelogid']);
		echo '<input type="submit" value="Edit Time Log">';
		echo '</form>';
	}

	if(isset($_POST['TimeOut']))
	{
		//The posted time out contains a "T" between the date and time. substr_replace will remove the "T" and replace it with " ""
		$_POST['TimeOut'] = substr_replace($_POST['TimeOut'], " ", 10, 1);
		echo '<h4>New Time Out: </h4><i>' . $_POST['TimeOut'] . '</i>';

		//Update the developers new time out
		$_SESSION['Developer']->updateTimeSheet($_SESSION['edit']['timelogid'], $_POST['TimeOut'] );

		echo '<h2>The Time Sheet has been updated, Refresh to view updated sheet.</h2>';
	}
}

function homePage()
{
	/*
	 * Keep all content in the div #page-content-wrapper
	 */
	echo '<main id="page-content-wrapper">'; 
	echo '<div class="col-lg-9 main-box">';

	//Custom Greeting Message
	if(localtime(time(), true)['tm_hour'] < 11 && localtime(time(), true)['tm_hour'] > 3)
		echo '<h1>Good Morning ' . $_SESSION['Developer']->getContact()->getFirstname() . '!</h1>';
	elseif(localtime(time(), true)['tm_hour'] > 11 && localtime(time(), true)['tm_hour'] < 16)	
		echo '<h1>Good Afternoon ' . $_SESSION['Developer']->getContact()->getFirstname() . '</h1>';
	elseif(localtime(time(), true)['tm_hour'] > 16)	
		echo '<h1>Good Evening ' . $_SESSION['Developer']->getContact()->getFirstname() . '</h1>';
	else
		echo '<h1>Welcome back ' . $_SESSION['Developer']->getContact()->getFirstname() . '!</h1>';

	if(localtime(time(), true)['tm_hour'] > 12)	
		echo '<h5>The current time is ' . localtime(time(), true)['tm_hour'] % 12 . ":" . localtime(time(), true)['tm_min'] . ' pm</h5>';
	else 
		echo '<h5>The current time is ' . localtime(time(), true)['tm_hour'] % 12 . ":" . localtime(time(), true)['tm_min'] . ' am</h5>';

	//If they have clocked in before
	if(count($_SESSION['Developer']->getTimeLog()) > 0)
	{
		$last_timeObject = $_SESSION['Developer']->getTimeLog()[ count($_SESSION['Developer']->getTimeLog()) - 1 ];
		echo '<h3>You last clocked out on ' . (new DateTime($last_timeObject->getTimeOut()))->format('l F jS Y') . (new DateTime($last_timeObject->getTimeOut()))->format(' \a\t g:ia') . '.</h3>';
		echo '<h3>You were working on ' . $last_timeObject->getClientname() . ', ' . (new Projects ($last_timeObject->getProjectId()))->getProjectName() . ', ' . (new Tasks ($last_timeObject->getTaskId()))->getTaskName() . '.</h3>';

		//Load Client Profile Page of last clock in
		getClientProfile($last_timeObject->getClientname());
	}

	echo '</div>';

	alertBox();

	open_footer();

	echo '</div>';
	echo '</div>';
	echo '</div>'; 
	   
	echo '</main>';
}

function loginPage()
{
	//If submit has been pressed and its a bad login load the error otherwise load the normal page
	if(isset($_POST['submit']))
	{
		if(!checkLogin($_POST['username'], $_POST['password']))
		{
			open_login("Login");
			getWrongLoginError();
			close_login();

		}
	}
	else
	{	
		open_login("Login");
		close_login();
	}
}

//This function echos a form to create a new Client and calls the createClient method which stores the info in the database.
function newClientForm($developer)
{
	$teamError = $clientError = $dateError = $firstnameError = $lastnameError = $phoneError = $emailError = $addressError = $cityError = $stateError = "";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!empty($_POST['firstname'] && $_POST['lastname'] && $_POST['phone'] && $_POST['address'] && $_POST['city'] && $_POST['state']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$position = $_POST['position'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$city = $_POST['city'];
			$state = $_POST['state'];
		}
		else
			$username = $password = $position = $firstname = $lastname = $phone = $email = $address = $city = $state = "";

		if(empty($_POST['clientname']))
				$clientError = "Missing";
		else
			$client = $_POST['clientname'];

		if(empty($_POST['startdate']))
			$dateError = "Please select a date.";
		else
			$startdate = $_POST['startdate'];

		if($client != "" && $startdate != "")
		{
			$developer->newClient($client, $startdate, $_POST['firstname'], $_POST['lastname'], $_POST['phone'], $_POST['email'], $_POST['address'], $_POST['city'], $_POST['state']);
			echo "<h1> $client was created!</h1>";
		}
	}

	echo <<<END
	<form id="developer_form" action="" method="POST">
	<br>Client Name: <font color="red">*</font><br>
	<input type="text" name="clientname">
	<font color='red'> $clientError</font>
	<br>StartDate: <font color="red">*</font><br>
	<input type="date" name="startdate">
	<font color='red'> $dateError</font>
END;
	echoContactInput();
	echo <<<END
	<input type="submit" name="Submit" value="Create Client">
	<br><font color="red">* Required fields.</font>
	</form>
	<br>
END;
}

//This function echos a form to create a new Developer and calls the createEmployee method which stores the info in the database.
function newDeveloperForm($developer)
{
	$teamError = $usernameError = $positionError = $passwordError = $firstnameError = $lastnameError = $phoneError = $emailError = $addressError = $cityError = $stateError = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!empty($_POST['firstname'] && $_POST['lastname'] && $_POST['phone'] && $_POST['address'] && $_POST['city'] && $_POST['state']))
		{
			$token = hash('ripemd128', $_POST['password']);
		
			$username = $_POST['username'];
			$password = $token;
			$position = $_POST['position'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$city = $_POST['city'];
			$state = $_POST['state'];
		}
		else
			$username = $password = $position = $firstname = $lastname = $phone = $email = $address = $city = $state = "";
    
	    if (empty($_POST['username']))
	        $usernameError = "Missing";
		elseif(strlen($_POST['username']) < 3)
			$usernameError = "Username needs to be at least 3 characters long";
	    else
	        $username = $_POST['username'];

	    if ($_POST['position'] == "")
	        $positionError = "Please select your position.";
	    else
	        $position = $_POST['position'];

		if (empty($_POST['password']))
        	$passwordError = "Missing";
		elseif(strlen($_POST['password']) < 4)
			$passwordError = "Password needs to be at least 5 characters long";
	    else
	        $password = $token;

		if($username != "" && $position != "" && $password)
		{
			createEmployee($developer->getTeam(), $username, $position, $password, $firstname, $lastname, $phone, $email, $address, $city, $state);
			echo "<h1> Developer created!</h1>";
		}
	}

	echo '<form id="developer_form" action="" method="POST"><br>';

	//teamDropDown($_SESSION['SuperUser']);

	echo <<<END
	<br>Username: <font color="red">*</font><br>
	<input type="text" name="username"> <font color="red">$usernameError</font>
	<br>Password: <font color="red">*</font><br>
	<input type="password" name="password"> <font color="red">$passwordError</font>
	<br>Position: <font color="red">*</font><br>
	<select name="position">
		<option value="">Select your position</option>
		<option value="Project Manager">Project Manager</option>
		<option value="Developer">Developer</option>
	</select>
	<font color="red">$positionError</font>
	<br>
END;

	echoContactInput();

	echo <<<END
	<br><input type="submit" name="Submit" value="Create Developer">
	<br><font color="red">* Required fields.</font>
	</form>
	<br>
	<a href="manage_developers.php">Back to Manage Developers</a>
END;
}

//This function consumes the name of a session variable and a developer and echos a project form and assigns and inputs that project into the database
function newProjectForm($session, $developer)
{
	echo '<form action="" method="POST">';
	echo '<h2>Select a Client</h2>';
	clientDropDown($developer);
	echo '</form>';


	if(isset($_POST['Client_Selected']) || isset($_SESSION[$session]['Client_Selected']))
	{
		if(isset($_POST['Client_Selected']))
			$_SESSION[$session]['Client_Selected'] = $_POST['Client_Selected'];

		echo '<h2>' . $_SESSION[$session]['Client_Selected'] . ' was selected.</h2>';

		$projectname = $projectError = "";

		//$_SERVER["REQUEST_METHOD"] == "POST"
		if(isset($_POST['newprojectsubmitted']))
		{
			if(empty($_POST['projectname']))
				{
					$projectError = "Missing";
				}
			else
				{
					$projectname = $_POST['projectname'];
				}
		}
		if($projectname != "")
		{
			$developer->newProject($_SESSION[$session]['Client_Selected'], $_POST['projectname'], $_POST['description']);
			echo '<h1>' . $_POST['projectname'] . ' was created!</h1>';
		}
		echo <<<END
		<form action="" method="POST">
		Project Name: <font color="red">*</font><br>
		<input type="text" name="projectname">
		<font color='red'> $projectError</font>
		<br>Description:<br>
		<input type="textarea" name="description"><br>
		<input type="Submit" name="newprojectsubmitted">
		<br><font color="red">* Required fields.</font>
		</form>
		<br>
		<a href='manage_clients.php'>Back</a>
END;
		//if(isset($_POST['projectname']))
		//{
		//	$developer->newProject($_SESSION[$session]['Client_Selected'], $_POST['projectname'], $_POST['Description']);
		//	echo '<h1>' . $_POST['projectname'] . ' was created!</h1>';
		//}
	}
}

//This function creates a from that assigns a task to the developer and calls assignTask to load the data into the database
function newTaskForm($session, $developer)
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

			echo '<form action="" method="POST">';
			echo 'Task Name: <br>';
			echo '<input type="text" name="taskname">';
			echo '<br>Description:<br>';
			echo '<input type="textarea" name="description"><br>';
			echo '<input type="Submit" name="newtasksubmitted">';
			echo '</form>';
		}

		if(isset($_POST['taskname']))
		{

			echo '<h1>' . $_POST['taskname'] . ' was created!</h1>';
			$_SESSION['Developer']->assignTask( new Tasks($_SESSION[$session]['Client_Selected'], $_SESSION[$session]['Project_Selected'], $_POST['taskname'], $_POST['description']) );
		}
	}
}

//This function prints out the project reports tables if a client, project, and dates have been selected.
function projectReports()
{
	//If a project is selected print the reports
	if(isset($_POST['Project_Selected']) || isset($_SESSION['report']['project']))
	{
		echo '<form action="" method="POST">';
		dateSelector();
		echo '<br>';
		echo '<input type="submit" value="submit">';
		echo '</form>';

		//Store the project selected in the report session
		if(isset($_POST['Project_Selected']))
			$_SESSION['report']['project'] = $_POST['Project_Selected'];

		if(isset($_POST['startdate']) && isset($_POST['enddate']))
		{
			echo '<h2>' . $_SESSION['report']['project']  . ' was selected</h2>';

			echo '<h3>Developers Hours</h3>';
			printAggregatedTimeLogTableByProject($_SESSION['report']['project'], $_POST['startdate'], $_POST['enddate']);

			echo '<h3>Detailed Time Sheet</h3>';
			printTimeLogTableByProject($_SESSION['report']['project'], $_POST['startdate'], $_POST['enddate']);
		}
	}
}

//This function prints out the task reports tables if a client, project, task, and dates have been selected.
function taskReports()
{
	if(isset($_POST['Task_Selected']) || isset($_SESSION['report']['task']))
	{
		echo '<form action="" method="POST">';
		dateSelector();
		echo '<br>';
		echo '<input type="submit" value="Build Report">';
		echo '</form>';

		if(isset($_POST['startdate']) && isset($_POST['enddate']))
		{
			echo '<h2>' . $_SESSION['report']['task']  . ' was selected</h2>';

			echo '<h3>Developers Hours</h3>';
			printAggregatedTimeLogTableByTask($_SESSION['report']['task'], $_POST['startdate'], $_POST['enddate']);

			echo '<h3>Detailed Time Sheet</h3>';
			printTimeLogTableByTask($_SESSION['report']['task'], $_POST['startdate'], $_POST['enddate']);
		}
	}
}

//This function unassigns a client from a selected developer
function unassignClient()
{
	developerClientDropdownForm('unassign');

	if(isset($_POST['Client_Selected']) || isset($_SESSION['unassign']['client']))
	{
		echo '<h4>' . $_SESSION['unassign']['client'] . ' was selected.</h4>';

		//Assign the selected client to the developer (Creates a Client object and stores it in the Client_List). Makes an entry in the DeveloperAssignments Table
		$client_to_unassign = new Client($_SESSION['unassign']['client']);

		$developer_to_unassign = new Developer($_SESSION['unassign']['developer']);
		$developer_to_unassign->unassignClient($client_to_unassign);

		printAssignmentsTableClient($_SESSION['unassign']['developer']);
	}
}

//This function unassigns a project from a selected developer
function unassignProject()
{
	developerClientProjectDropdownForm('unassign');

	if(isset($_POST['Project_Selected']))
	{
		echo '<h3>' . $_POST['Project_Selected'] . ' was selected</h3>';

		$developer = new Developer($_SESSION['unassign']['developer']);

		$developer->unassignProject( new Projects($_POST['Project_Selected']) );

		echo '<h1>Project: ' . (new Projects($_POST['Project_Selected']))->getProjectName() . ' was unassigned </h1>';
	}
}

//This function unassigns a task from a selected developer.
function unassignTask()
{
	echo '<h4>Select a Developer</h4>';

	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo '</form>';

	developerClientProjectTaskDropDownForm('unassign');

	//If all of the drop downs have been selected, assign the task and print the table
	if(isset($_POST['Task_Selected']) || isset($_SESSION['unassign']['task']))
	{
		echo '<h2>' . $_SESSION['unassign']['task']  . ' was selected</h2>';

		//Assign the selected task to the developer (Creates a Task object and stores it in the Task_List). Makes an entry in the DeveloperAssignments Table
		$task_to_unassign = new Tasks($_SESSION['unassign']['task']);

		$developer_to_unassign = new Developer($_SESSION['unassign']['developer']);
		$developer_to_unassign->unassignTask($task_to_unassign);

		printAssignmentsTable($_SESSION['unassign']['developer']);

		echo '<h3>' . $task_to_unassign->getTaskName() . ' was unassigned.</h3>';
	}
}

//This function consumes a developer, echos a form to modify the developers alert settings, and handles the post by updating the developers alert settings
function updateAlertsForm($developer)
{
	if(isset($_POST['days']) && isset($_POST['hours']))
	{
		$developer->setDaysExpirationWarning( $_POST['days'] );
		$developer->setHoursLeftWarning( $_POST['hours'] );
	}

	echo '<form action="" method="POST">';
	echo '<label>Days Before a Contract Expires:</labels>';
	echo '<input type="number" name="days" value="' . $developer->getDaysExpirationWarning() . '">';
	echo '<br>';
	echo '<label>Hours Left on Contract:</label>';
	echo '<input type="number" name="hours" value="' . $developer->getHoursLeftWarning() . '">';
	echo '<br>';
	echo '<input type="submit" value="Update Alerts">';
	echo '</form>';

	if(isset($_POST['days']) && isset($_POST['hours']))
	{
		echo '<h3>Alerts has been updated.</h3>';
	}
}

//This function prints out the Client profile page
function viewClientProfiles()
{
	echo '<form action="" method="POST">';
	clientDropDown($_SESSION['Developer']);
	echo '</form>';

	if( isset($_POST['Client_Selected']) )
	{
		getClientProfile($_POST['Client_Selected']);
	}
}

function updatePassword()
{
	echo <<<END
	<br>
	<br>
	<form action="" method="POST">
	Password:
	<input type="password" name="password">
	<br>
	<br>
	<input type="Submit" name="Update" value="Update">
	</form>
END;

	if(isset($_POST['Update']))
	{
		$hashed_password = hash('ripemd128', $_POST['password']);

		updateTableByUser('Credentials', 'Password', $hashed_password, $_SESSION['Developer']->getUsername() );

		echo 'Password successfully updated!';
	}
}

function viewAllAssignments()
{
	echo '<h4>Select a Developer</h4>';
	echo '<form action="" method="POST">';
	developerDropDown($_SESSION['Developer']);
	echo '</form>';

	if(isset($_POST['Developer_Selected']))
	{
		echo '<h4>' . $_POST['Developer_Selected'] . " was selected</h4>";

		echo '<h4>Client\'s Assigned </h4>';
		printAssignmentsTableClient($_POST['Developer_Selected']);

		echo '<h4>Project\'s Assigned </h4>';
		printAssignmentsTableProject($_POST['Developer_Selected']);

		echo '<h4>Task\'s Assigned </h4>';
		printAssignmentsTableTask($_POST['Developer_Selected']);
	}
}
?>