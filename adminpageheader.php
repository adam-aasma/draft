<?php
use Walltwisters\model\User;
use Walltwisters\model\Country;

    require_once 'data/UserRepository.php';
    require_once 'checkauth.php';
    $user = unserialize($_SESSION['user']);
    $menu = array("dashboard" => "#",
                "products" =>"/admin_products.php",
                "printer" => "addprinter.php",
                "slides" => "/admin_slides.php",
                "view customer" => "#",
                "view orders" => "#",
                "view payments" => "#",
                "users" => "/adduser.php",
                "logout" => "/logout.php");

    function IsURLCurrentPage($url){
       return strpos($_SERVER['PHP_SELF'], $url) !== false;
    }
   
    function DisplayLink($name,$url,$active=true){
        if ($active){
            echo "<li><a href='$url'><span>$name</span></a></li>";
        } else {
            echo "<div class='#'><span>$name</span></div>";
        }
    }

?>
<html>
    <head>
        <title><?= $title ?></title>
        <meta name="keywords" content="<?= $keywordContent ?>">
        <link href="/css/admin.css" type="text/css" rel="stylesheet">
        <script>var initFunctionTable = [];</script>
    </head>
    <body id="bodyLoad">
        <div class="admin">
            <header class="header">
                <h1>Admin Panel</h1>
                <p>happy workday Dear <?= $user->name ?>!</p>
                <h2>user profile</h2>
            </header>
            <div class="flex-wrapper">
                <nav class='menu hover'>
                    <ul>
                    <?php foreach($menu as $key => $value) {
                        DisplayLink($key, $value, !IsURLCurrentPage($value));
                    } ?>
                    </ul>
                </nav>
                <div class="desktop">

        