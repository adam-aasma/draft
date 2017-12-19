<?php
    require_once('data/ImageRepository.php');
    require_once('data/Exceptions.php');
    
    $repo = new ImageRepository();
    $categories=$repo->getCategories();
    
    /*    [['key' => '1', 'value' => 'slider], ['key' => '2', 'value' => 'product' ]]
     * *  $a = [ '1' => 'slider', '2' => 'product']     $a['1'] === 'slider'    $a[0] === null
     * 
     *    $b = ['slider', 'product', 'hej', 'hopp']    $b[0] === 'slider'
     */
    
    
    
    
?>

<!DOCTYPE html>
<html>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="imagefile" />
    <br />
    Image name:
    <input type="text" name="imagename" />
    <br />
    Category:
    <select name="category">
        <?php foreach ($categories as $apa => $orvar) : ?>
            <option value="<?= $apa ?>"><?= $orvar ?></option>
        <?php endforeach; ?>
    </select> 
    <br />
    <input type="submit" value="Upload Image" name="submit" />
</form>

</body>
</html>