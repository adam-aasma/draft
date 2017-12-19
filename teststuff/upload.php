<?php
    require_once('data/ImageRepository.php');
    require_once('data/Exceptions.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getImageData() {
    $file = $_FILES["imagefile"];
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["imagefile"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = true;
            $filepath = $_FILES["imagefile"]["tmp_name"];
            $mime = $check["mime"];
            $size = $_FILES["imagefile"]["size"];
            
            return [ 'filepath' => $filepath, 'mime' => $mime, 'size' => $size ];
       } else {
            return false;
       }
    }
}

$imageData = getImageData();
$name = $_POST['imagename'];
$category = $_POST['category'];

if ($imageData === false) {
    echo 'no image';
    die();
}

$repo = new ImageRepository();
try {
    $repo->addImage($imageData['filepath'], $imageData['size'], $imageData['mime'], $name, $category);
} catch(Exception $e) {
    die($e->getMessage());
}
?>