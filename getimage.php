<?php
use Walltwisters\data\ImageRepository;

    require_once('data/ImageRepository.php');
    require_once('data/Exceptions.php');

    $id = $_GET['id'];
    $repo = new ImageRepository();
    $image = $repo->getImage($id);

    header('Content-Type: ' . $image['mime']);
//header('Content-Length: ' . $size);
    echo $image['data'];
    die();

?>
