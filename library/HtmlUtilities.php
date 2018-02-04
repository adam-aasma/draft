<?php


class HtmlUtilities {
   
    public static function createThumbNail($productId, $name, $imageId, $attName='dontpost'){
        $thumbNail = "<div class='thumbnail'>
                    <img alt='thumbnail' src='getimage.php?id=$imageId'>
                    <input type='hidden' value='$productId' name='$attName'/>
                    <h6>$name</h6>
                 </div>";
        return $thumbNail;
    }
}
