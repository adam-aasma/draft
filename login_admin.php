<?php
require __DIR__ . '/vendor/autoload.php';

use Walltwisters\repository\UserRepository;

require_once 'library/security.php';

$error = '';
if (security::filled_out($_POST)) {
    try {
        $userName = $_POST["username"];
        $password = $_POST["password"];
        $repo = new UserRepository();
        $user = $repo->LoginUser($userName,$password);
        if ($user) {
            session_set_cookie_params(600);
            session_start();
            $_SESSION['user'] = serialize($user);
            header("Location: admin_panel.php");
            die();
        } else {
            session_start();
            session_unset();
            session_destroy();
        }    
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}

 ?>
    <html>
        <head>
            <title> test </title>
            <link href="css/login_admin.css" rel="stylesheet"/>
        </head>
    <body>
        <div style="color: red;"><?= $error ?></div>
        <div class="wrapper">
            <form method="post" action="login_admin.php">
                <fieldset>
                    <legend>login</legend>
                    <p class="field-text">
                        <label>Username:</label>
                        <input type="text" name="username">
                    </p>
                    <p class="field-text">
                        <label>Password:</label>
                        <input type="password" name="password">
                    </p>
                    <button type="submit" name="submit" value="Login">SUBMIT</button>
                </fieldset>
            </form>
      
        </div>
    </body>
</html>

<?php
 
 ?>




