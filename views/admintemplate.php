<?php

require_once 'data/UserRepository.php';
require_once 'data/productRepository.php';
require_once 'library/FormUtilities.php';


class adminTemplate {
    private $content;
    private $welcome;
    private $title = "walltwisters admin";
    private $keywords = " very important SEO stuff";
    private $user;
    private $menu = array("dashboard" => "#",
                         "products" =>"/admin_products.php",
                         "products categories" => "#",
                         "slides" => "/admin_slides.php",
                         "view customer" => "#",
                         "view orders" => "#",
                         "view payments" => "#",
                         "users" => "/adduser.php",
                         "logout" => "/logout.php");
    
    public function __construct() {
        $this->setUser();
    }
    
   public function __set($name, $value) {
       $this->$name = $value;
   }
   
   private function setUser(){
      $this->user = unserialize($_SESSION['user']);
   }
   
   public function __get($name){
       return $this->$name;
   }
   
   private function setWelcome(){
       $this->setUser();
       $this->welcome = 'happy workday Dear ' . $this->user->name . '!';
       return $this->welcome;
   }
   
   public function DisplayTitel(){
       echo "<title>" . $this ->title . "</title>";
   }
   
   public function DisplayKeywords(){
       echo "<meta name='keywords' content='" . $this->keywords . "'/>";
   }
   
   public function DisplayStyles(){
       ?>
       <link href="/css/admin.css" type="text/css" rel="stylesheet">
     
       <?php
   }
   
   public function DisplayHeader(){
       ?>
       <!-- page header -->
       <div class="admin">
           <header class="header">
                    <h1>Admin Panel</h1>
                    <p><?= $this->setWelcome() ?></p>
                    <h2>user profile</h2>
            </header>
       <?php
   }
   
   public function DisplayMenu($menu){
       echo "<nav class='menu hover'>\n<ul>";
       
       foreach($menu as $key => $value) {
           $this -> DisplayLink($key, $value,
                                !$this -> IsURLCurrentPage($value));
       }
       echo "</ul>\n</nav>\n";
   }
   
   public function IsURLCurrentPage($url){
       return strpos($_SERVER['PHP_SELF'], $url) !== false;
   }
   
   public function DisplayLink($name,$url,$active=true){
       if ($active){ ?>
       <li>
           <a href="<?=$url?>">
               <span><?=$name?></span>
           </a>
       </li>
       <?php
       }
       else { ?>
           <div class="#">
               <span><?=$name?></span>
           </div>
           <?php
       }
   }
   
   public function DisplayFooter(){
       ?>
     <script type="text/javascript" src="js/admin.js"></script>
       <!-- page footer -->
      <!-- <footer>
           <p>copyrights walltwisters</p>
       </footer> -->
       <?php
   }
   
   public function Display(){
       echo "<html>\n<head>\n";
       $this -> DisplayTitel();
       $this -> DisplayKeywords();
       $this -> DisplayStyles();
       echo "<script>var initFunctionTable = [];</script>";
       echo "</head>\n<body onload='initPage()'>\n";
       $this -> DisplayHeader();
       echo '<div class="flex-wrapper">';
       $this -> DisplayMenu($this -> menu);
       echo $this->content;
       echo '</div>';
       $this -> DisplayFooter();
       echo "</body>\n</html>\n";
   }
   
}
?>
