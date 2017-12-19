<?php
require_once 'checkauth.php';
require_once 'views/admintemplate.php';

$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';

$content = $homepage -> content = '<div class="desktop"><img src="/img/logo.svg" alt="logotype" /></div>';

$homepage -> Display();

?>