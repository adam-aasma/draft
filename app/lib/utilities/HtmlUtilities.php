<?php
namespace Walltwisters\lib\utilities;

class HtmlUtilities {
    
    private $instance;
    
    public function __construct(){
        $this->instance = [
            'begin' => '',
            'attribute' => [],
            'end' => ''
        ];
    }
    
   
   
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
    
    public function setElement($element){
        $this->instance['begin'] = '<' . $element . ' ';
        $this->instance['end'] = '</' . $element . '>';
    }
    
    public function setattributes($attributes){
        foreach($attributes as $attribute => $value){
            $this->instance['attribute'][$attribute] = $value;
        }
    }
    
    public function setContent($content) {
        $this->instance['content'] = $content;
    }
   
    public function getElement(){
        $html = 
            $this->instance['begin'] .
            $this->createStringOfAttributes() .
            $this->instance['content'] .
            $this->instance['end'];
        return $html;
    }
    
    private function createStringOfAttributes(){
        $html = '';
        foreach($this->instance['attribute'] as $attribute => $value){
            $html .= $attribute . "='" . $value . "' ";
        }
        $html .= " >";
        return $html;
        
    }
}
