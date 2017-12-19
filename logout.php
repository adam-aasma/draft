<?php

if (isset($_POST["submit"])) {
    session_start();
    session_unset();
    session_destroy();
    header("location: login_admin.php");
    die();
}


require_once 'checkauth.php';
require_once 'views/admintemplate.php';

$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';

$content = $homepage -> content = '<div class="desktop"><form method="post" action="login_admin.php">
           <fieldset class="logout">
           <button type="submit" name="submit" value="Logout">Logout</button>
           </fieldset>
           </form></div>';

$homepage -> Display();

?>
  





