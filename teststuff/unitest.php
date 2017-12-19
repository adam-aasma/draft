<?php

require_once '../data/productRepository.php';

$test = new ProductRepository();

$arrays = array('item1' => array(1,2,3),
                'item2' => array(1,2,3),
                'item3' => array(1,2,3)
);
$comb = $test->combinations($arrays);
var_dump($comb);
/*foreach($comb as $com){
    foreach($com as $co){
        
    }
    echo 'insert into db' . $com;
} */


