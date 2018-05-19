<?php
namespace Walltwisters\lib\repository\exceptions; 

class DatabaseException extends \Exception {
    //put your code here
    
    function __construct(string $message) {
        parent::__construct($message);
    }
}
