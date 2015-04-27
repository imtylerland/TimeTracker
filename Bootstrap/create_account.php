<?php
require_once(__DIR__.'/../include.php');

session_start();

if( isset($POST['joinTeam']) )
{
	//check to see if credentials match
}
elseif( isset($_POST['createTeamName']) && isset($_POST['createTeamCode']) )
{
	//check to see if team name is taken
	if( count(returnRowByTeam($_POST['createTeamName'])) == 0 )
	{
		$hashed_team_code = hash('ripemd128',$password);
	}
	else
		echo '<h6>Team Name Taken</h6>';
}

echo '<h4>Join Team</h4>';
echo '<form action="" method="POST">';
echo '<label>Team Name</label>';
echo '<input type="text" name="joinTeamName">';
echo '<label>Team Code</label>';
echo '<input type="password" name="createTeamCode">';
echo '<input type="submit" name="joinTeam" value="Join Team">';
echo '</form>';

echo '<h4>Create Team</h4>';
echo '<form action="" method="POST">';
echo '<label>Team Name</label>';
echo '<input type="text" name="createTeamName">';
echo '<label>Team Code</label>';
echo '<input type="password" name="createTeamCode">';
echo '<input type="submit" name="createTeam" value="Create Team">';
echo '</form>';

echo 'done';
?>