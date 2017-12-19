<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';

$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';

$content = $homepage -> content = '<div class="admin_products">
            <a href="addproduct2.php">add product</a>
            <a>delete product</a>
            <a>list products</a>
        </div>';
 

$homepage -> Display();

 ?>
 
 