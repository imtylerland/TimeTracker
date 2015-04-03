<?php
require_once(__DIR__.'/../include.php');
require_once(__DIR__.'/page_functions.php');

session_start();

//Stores the client selected in the 'currentLog' session variable
$_SESSION['currentLog']['client'] = $_POST['Client_Selected'];

html_header("Demo Login");

echo "<div id='box'>";

echo '<h1>' . $_SESSION['Developer']->getUsername() . ' is logged in</h1>';

echo '<h2>' . $_POST['Client_Selected'] . ' was selected</h2>';
echo '<h3>Select a Project</h3>';

echo '<form action="select_task.php" method="POST">';

projectDropDown($_SESSION['Developer'], $_POST['Client_Selected']);

echo '</form>';

echo '</div>';

html_footer();
?>
