<?php
require_once 'data/UserRepository.php';
require_once 'library/security.php';
require_once 'checkauth.php';



if (count($_POST) && (!Security::filled_out($_POST)) || (isset($_POST['password'], $_POST['contpassword']) && $_POST['password'] !== $_POST['contpassword'])) {
    throw new Exception('you have not filled out the form correctly');
    
    die();
}
    $userrepo = new UserRepository();
    $privileges = new Privileges();
    $user = unserialize($_SESSION['user']);
    if ($user->privileges === 0 ){
        $desktop = 'you are not allowed to add users';
        die();
    }
    if (security::filled_out($_POST)){
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $privilege = $_POST['privileges'];
    $countryid = $_POST['country'];
    $country = new Country();
    $country->id = $countryid;
    $user = new User(0, $name, $lastname,$username, $privilege, $country);
    $user->password = $password;
    $user = $userrepo->AddUser($user);
    
    }

    
$privileges = $userrepo->GetPrivileges();
$privOptions = '';
foreach($privileges as $priv) {
    $val = $priv->id;
    $text = $priv->privileges;
    $privOptions .= "<option value='" . $val . "'>" . $text . "</option>";
}     
$countryOptions = '';
$countries = $userrepo->GetCountries();
foreach($countries as $country) {
    $val = $country->id;
    $text = $country->country;
    $countryOptions .= "<option value='" . $val . "'>" . $text . "</option>";
}

require_once 'views/admintemplate.php';
$homepage = new adminTemplate();
$content = $homepage -> content = '<form method="post" action="adduser.php">
            <div class="fieldset-wrapper">
                    <fieldset>
                        <legend>add user</legend>
                        <p class="field field-text">
                            <label for="adding-name">Name:</label>
                            <input type="text" name="name" id="adding-name">
                        </p>
                        <p class="field field-text">
                            <label for="adding-lastname">Lastname:</label>
                            <input type="text" name="lastname" id="adding-lastname">
                        </p>
                        <p class="field field-text">
                            <label for="username" >Username:</label>
                            <input type="text" name="username" id="username">
                        </p>
                        <p class="field field-text">
                            <label for="adding-password">Password:</label>
                            <input type="password" name="password" id="addingpassword">
                        </p>
                        <p class="field field-text">
                            <label for="control-password">Repeat password:</label>
                            <input type="password" name="contpassword" id="control-password">
                        </p>
                        <p class="field field-text>
                            <label for="set-country">Country</label>
                            <select name="country">
                            ' . $countryOptions . '
                            </select>
                        </p>
                        <p class="field field-text>
                            <label for="set-privileges">Privileges</label>
                            <select name="privileges">
                            ' . $privOptions . '
                            </select>
                        </p>
                        <button type="submit" name="submit" value="#">submit</button>
                        <br>
                    </fieldset>
                </div>
        </form>';
$homepage -> Display();
 ?>      
 
        
          
              



