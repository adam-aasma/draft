<?php
require __DIR__ . '/vendor/autoload.php';

use Walltwisters\repository\ImageRepository;


    $id = $_GET['id'];
    try {
        $repo = new ImageRepository();
        $image = $repo->getImage($id);

        header('Content-Type: ' . $image['mime']);
    //header('Content-Length: ' . $size);
        echo $image['data'];
        die();
    } catch (Exception $ex) {
        header("HTTP/1.0 404 Not Found");
        die();
    }

?>
