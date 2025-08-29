<?php
include  __DIR__ . "\..\database\config.php";
class Validation
{
    public function required($inputValue ,$inputName):string
    {
        return (empty($inputValue)) ? "$inputName is required" :"" ;

    }


    public function regex($inputValue ,$inputName,$pattern){
        return preg_match($pattern,$inputValue) ? "" : "$inputName Is Invalid";
    }

    function unique($table,$column,$value)
    { 
        $query ="SELECT * FROM `$table` WHERE `$column` = `$value`";
        $config = new config;
        $result =$config->runDQL($query);
        return (empty($result)) ? "" :"This $column  is already exists ";
    }
    
    public function confirmed($inputValue ,$inputName,$valueConfirmation) : string
    {
        return ($inputValue == $valueConfirmation) ? "" : "$inputName Not confirmed";
    }




}







?>
