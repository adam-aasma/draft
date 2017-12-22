<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';

$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';

$content = $homepage -> content = '<div class="desktop products">
            <a href="addslide.php">add slide</a>
            <a>delete slide</a>
            <a>list slides</a>
        </div>';
 

$homepage -> Display();

 ?>

