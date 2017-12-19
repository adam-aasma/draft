<?php
    require_once('data/ImageRepository.php');
    require_once('data/Exceptions.php');

    $ids = [];

    try {
        $repo = new ImageRepository();
        $slider = 1;
        $ids = $repo->getImagesByCategory($slider);
    } catch (DatabaseException $e) {
        die("Connection failed: " . $e->getMessage());
    }
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/css.css" rel="stylesheet" />
        <script src="js/javascript.js" type='text/javascript'></script>
    </head>
    <script>
        imageIds = [ <?= implode(',', $ids) ?> ];
    </script>
    <body onload="initDocument()">
        <div class="grid">
       
            <img class="logo" src="img/logo.svg">  <!-- logo -->
           <nav>                                      <!-- navbar-->
            <a  href="#">kodu</a>
            <div class="dropdown"><div onclick="openDropDown()" class="wt-dropdownmenu">tooted</div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#">
                                    lemmikud
                                    </a>
                                    <a href="#">
                                        uued
                                    </a></div></div>
            <a  href="#">raamid</a>
            <a  href="#">muuseum</a>
            <span>0</span>
            <img class="shoppingcart" src="img/shoppingcart.png">
          </nav>
     
        <section class="discountbar"> <h2>-sel nädalal 20% maha</h2>  </section>
        <section id="slideshow" class="slideshow" > 
        
        
        
        </section> 
        <figure class="slideshow-gradient" ></figure>
        <div id="pictures" class="wt-section">
                
            </div>
        <section>
            
            
            
            
            
        </section>
        </div>
    </body>
</html>


