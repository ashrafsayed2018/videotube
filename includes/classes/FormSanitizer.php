<?php 

class FormSanitizer{
    
    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText); // remove tags 
        $inputText  = str_replace(" ","", $inputText); // remove space 
        $inputText = strtolower($inputText); // make all letters lowercase
        $inputText = ucfirst($inputText); // make first letter uppercase 

        return $inputText;
    }

    public static function sanitizeFormUsername($inputText) {
        $inputText = strip_tags($inputText); // remove tags 
        $inputText  = str_replace(" ","", $inputText); // remove space 

        return $inputText;
    }
    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText); // remove tags 

        return $inputText;
    }
    public static function sanitizeFormEmail($inputText) {
        $inputText = strip_tags($inputText); // remove tags 
        $inputText  = str_replace(" ","", $inputText); // remove space 

        return $inputText;
    }

}