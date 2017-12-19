<?php

require_once 'domain/Customer.php';
require_once 'data/OrderRepository.php';

$details = 'no details';
if (isset($_POST['firstname']) &&
  isset($_POST['lastname']) &&
  isset($_POST['address']) &&
  isset($_POST['zipcode']) &&
  isset($_POST['city']) &&
  isset($_POST['country']) &&
  isset($_POST['email']) &&
  isset($_POST['phonenumber'])) {
    $customer = new Customer($_POST['firstname'],
        $_POST['lastname'],
        $_POST['address'],
        $_POST['zipcode'],
        $_POST['city'],
        $_POST['country'],
        $_POST['email'],
        $_POST['phonenumber']);
    
    $details = $customer->getFirstName() . '<br>' .
            $customer->getLastName(). '<br>' .
            $customer->getAddress(). '<br>' .
            $customer->getZipCode(). '<br>' .
            $customer->getCity(). '<br>' .
            $customer->getCountry(). '<br>' .
            $customer->getEmail(). '<br>' .
            $customer->getPhoneNumber(). '<br>';
    
    try {
    $repo = new OrderRepository();
    $repo->insertCustomer($customer);
    
    $details = 'Customer with details: ' . $details . ' has been inserted in the DB';
    } catch (Exception $e) {
        $details = "WE failed: " . $e->getMessage();
    }
  }
else echo "not completely entered";


    echo <<<_END
<html>
  <head>
    <title> test </title>
    <link href="css/checkout.css" rel="stylesheet"/>
  </head>
    <body>
your details: $details
            
     <div class="wrapper">
        <h1>Logotype </h1>
        <h2>transport</h2>
        <form method="post" action="checkout.php">
           omniva ( 2,4€):
           <input type="radio" delivery="order:php">
           </br>
           Itella ( 2,4€):
           <input type="radio" delivery="order:php">
           </br>
       
          Eesnimi:
          <input type="text" name="firstname">

             
</br>
          Perekonnanimi:
          <input type="text" name="lastname">
            
</br>
          Aadres:
         <input type="text" name="address">
             
</br>
          Postikood:
         <input type="text" name="zipcode">
             
</br>
          Linn:
         <input type="text" name="city">
             
</br>
          Maa:
         <input type="text" name="country">
             
</br>
          Email:
         <input type="text" name="email">
            
</br>
          Telefoninumber:
         <input type="text" name="phonenumber">
</br>
             <input type="submit">

      </form>

</div>
    </body>

</html>
 
_END;
        
        
        
?>
