<?php
use Walltwisters\data\UserRepository;
use Walltwisters\model\Privileges;
use Walltwisters\data\RepositoryFactory;
require_once 'data/UserRepository.php';
require_once 'library/security.php';
require_once 'checkauth.php';
require_once 'data/RepositoryFactory.php';
require_once 'library/FormUtilities.php';

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
    $countries = $_POST['countries'];
    foreach ($countries as $country){
        $countriesid[] = $country;
    }
    
    $user = new User(0, $name, $lastname,$username, $privilege, $countries);
    $user->password = $password;
    $user = $userrepo->AddUser($user);
    die();
    
    }

$repositoryFactory = new RepositoryFactory();
$privileges = $repositoryFactory->privilegeRepository->getAllPrivileges();
$countries = $repositoryFactory->countryRepository->getAllCountries();
$privOptions = FormUtilities::getAllOptions($privileges, 'privileges');
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
require_once 'adminpageheader.php';
$title = 'adduser';
$keywordContent = 'very important seo';

?> 

<form method="post" action="adduser.php">

    <fieldset class="adduser">
        <legend>add user</legend>
        <p class="field-text">
            <label for="adding-name">Name:</label>
            <input type="text" name="name" id="adding-name">
        </p>
        <p class="field-text">
            <label for="adding-lastname">Lastname:</label>
            <input type="text" name="lastname" id="adding-lastname">
        </p>
        <p class="field-text">
            <label for="username" >Username:</label>
            <input type="text" name="username" id="username">
        </p>
        <p class="field-text">
            <label for="adding-password">Password:</label>
            <input type="password" name="password" id="addingpassword">
        </p>
        <p class="field-text">
            <label for="control-password">Repeat password:</label>
            <input type="password" name="contpassword" id="control-password">
        </p>
        <p class="field-text">
            <label for="set-country">Country</label>
            <select multiple name="countries[] id="set-country">
            <?= $countryOptions ?>
            </select>
        </p>
        <p class="field field-text">
            <label for="set-privileges">Privileges</label>
            <select name="privileges" id="set-privileges">
           <?=$privOptions ?>
            </select>
        </p>
        <button type="submit" name="submit" value="#">submit</button>
        <br>
    </fieldset>
</form>      
              



