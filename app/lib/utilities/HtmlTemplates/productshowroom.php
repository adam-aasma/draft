<?php

?>
<template>
    <div id="showroom">
    <link href="css/productShowRoom2.css" type="text/css" rel="stylesheet">

        <div class="showroom">
            <img src="/img/2kolmnurka.jpg"/>
            <div class="arrowbutton">
               <!-- <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button> -->
            </div> 
        </div>
        <div class="product-info">
            <div id="productname">
                <h1>productname</h1>
            </div>
            <div id="pricebutton" class="priceButton">
                <h2><!--product price --></h2>
                <button>confirm</button>
                <button id="editButton" onclick="goBackFromShowRoom()">edit</button>
            </div>
            <div id="accordionmenu">
                <span id="showroomdescription" class="nobottomborder">description</span>
                <span id="showroomsizes">sizes</span>
                <span id="showroomdelivery">delivery</span>
                <p id="productdescription">
                <!-- the product description -->
                productdescription
                </p>
                <p id="productsizes">
                    some sizes
                </p>
                <p id="productdelivery">
                    very fast delivery
                </p>
            </div>

        </div>
    </div>
    <script src="js/page/productshowroom.js" type="text/javascript"></script>
</template>
