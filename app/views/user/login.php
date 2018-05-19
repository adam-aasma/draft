<?php
//$error = $data['error'];
?>
<html>
    <head>
        <title> test </title>
        <link href="/css/pages/loginadminstyles.css" rel="stylesheet"/>
    </head>
<body>
    <div class="wrapper">
        <form method="post" action="login">
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
                <div style="color: red;"><?= $error ?></div>
            </fieldset>
        </form>

    </div>
</body>
</html>

