<?php
namespace Walltwisters\lib\utilities;

class Security {
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
}
