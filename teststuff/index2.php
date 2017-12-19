<?php 
    require_once('data/ImageRepository.php');
    require_once('data/Exceptions.php');

    $ids = [];

    try {
        $repo = new ImageRepository();
        $ids = $repo->getAllImageIds();
    } catch (DatabaseException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php foreach($ids as $id): ?>
        <img src="getimage.php?id=<?= $id ?>" />
        <?php endforeach; ?>
        <br/>
        <a href="uploadimage.php">Upload image</a>
    </body>
</html>
