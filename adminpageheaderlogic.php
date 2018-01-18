<?php


use Walltwisters\model\User;
use Walltwisters\model\Country;

    require_once 'data/UserRepository.php';
    require_once 'checkauth.php';
    $user = unserialize($_SESSION['user']);
    $menu = array("dashboard" => "#",
                "products" =>"/listproducts.php",
                "printer" => "addprinter.php",
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