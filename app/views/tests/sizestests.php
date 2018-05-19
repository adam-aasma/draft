<?php

use Walltwisters\lib\repository\ItemSizeRepository;
use Walltwisters\lib\repository\ItemMaterialRepository;
use Walltwisters\lib\model\ItemSize;
use Walltwisters\lib\repository\ItemPrintTechniqueRepository;

$itemSizeRepo = new ItemSizeRepository();
$itemMaterialRepo = new ItemMaterialRepository();
$itemPrintTechniquRepo = new ItemPrintTechniqueRepository();

$material = \Walltwisters\lib\model\ItemMaterial::create('evenNewer');
$size = ItemSize::create("newsize", '');
$technique = Walltwisters\lib\model\ItemPrintTechnique::create('new technique', '');


$sizes = $itemSizeRepo->ifRowExistsReturnElseCreate($size);
$materials = $itemMaterialRepo->ifRowExistsReturnElseCreate($material);
$technique = $itemPrintTechniquRepo->ifRowExistsReturnElseCreate($technique);

if(!$sizes){
    $sizeres = 0;
} else {
    $res = $sizes[0]->id;
}

if($materials){
    $materialStatus = 'ok';
}

if($technique){
    $technqiqueStatus = 'ok';
}


?>
<html>
    <head>
        
    </head>
    <body>
        <div>result sizes <?=$res?></div>
        <div>result material <?=$materialStatus?></div>
        <div>result technique <?=$technqiqueStatus?></div>
    </body>
</html>