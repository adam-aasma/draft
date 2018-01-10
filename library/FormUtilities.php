<?php


class FormUtilities {
    
    public static function getImageData($imagefiles){
        $productimages = [];
        foreach ($imagefiles as $imagefile){
            $filepath = $imagefile["tmp_name"];
            $mime = $imagefile["type"];
            $size = $imagefile["size"];
            $file = [ 'filepath' => $filepath, 'mime' => $mime, 'size' => $size ];
            $productimages[] = $file;
        }
        
        if ($productimages){
            return $productimages;
        } else {
            return false;
        }
    }
    
    public static function filled_out($form_vars) {
        if (empty($form_vars)){
            return false;
        }
        foreach ($form_vars as $key => $value){
            if ((!isset($key)) || ($value == '')){
                return false;
            }
            
        }
        return true;
    }
    
    public static function valid_email($address) {
        // check an email address is possibly valid
        if (preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/', $address)){
            return true;
        }  else {
            return false;
        }
    }
    
    public static function getAllOptions($objcollection, $name){
        $optionsHtml = '';
        foreach ($objcollection as $obj){
            $val = $obj->id;
            $text = $obj->$name;
            $optionsHtml .= "<option value='" . $val . "'>" . $text . "</option>";
        }
        return $optionsHtml;
            
    }
    
     public static function getAllCheckBoxes($objcollection, $propname, $inputname, $checkedIds = []){
        $html = '';
        foreach ($objcollection as $obj){
            $label = $obj->$propname;
            $checked = in_array($obj->id, $checkedIds) ? 'checked' : '';
            $html .= "<input type='checkbox' value='true' name='$inputname" . "[$obj->id]' $checked /><label>$label</label>";
        }
        return $html;
            
    }
    
    public static function getAllRadioOptions($objcollection, $propname, $inputname){
        $html = '';
        foreach ($objcollection as $obj){
            $label = $obj->$propname;
            $value = $obj->id;
            $html .="<input type='radio' name='$inputname' value='$value' class='indexValue' /><label>$label</label>";
        }
        return $html;
    }
}

