<?php
namespace Walltwisters\utilities;

class HtmlUtilities {
   
    public static function createThumbNail($productId, $name, $imageId, $attName='dontpost'){
        $thumbNail = "<div class='thumbnail'>
                    <img alt='thumbnail' src='getimage.php?id=$imageId'>
                    <input type='hidden' value='$productId' name='$attName'/>
                    <h6>$name</h6>
                 </div>";
        return $thumbNail;
    }
    
    public static function createPictureThumbNail($imageId){
        $thumbNail = "<div class='thumbnail'>
                        <img alt='thumbnail' src='getimage.php?id=$imageId'>
                    
                      </div>";
        return $thumbNail;
    }
    
    
    public static function createSpans(array $idsAndTexts, $link = false){
        if($link){
            $link = '>>';
        }
        $html = '';
        foreach ($idsAndTexts as $idsAndText) {
            $id = $idsAndText['id'];
            $text = $idsAndText['text'];
            $html .= '<span data-item=' . $id . '>' . $text .$link . '</span><br/>';
        }
        
        return $html;
    }
}
