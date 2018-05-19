<?php

?>
<template id="productshowroomtemplate">
    <style>
        @import url("/css/pages/productshowroomstyles.css");
    </style>
    <div id="showroom">
        <div class="showroom">
            <div id="showroomimages">
            
            </div>
            <div class="arrowbutton">
               <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button>
            </div> 
        </div>
        <div class="product-info">
            <div id="productname">
                <h1></h1>
            </div>
            <div id="pricebutton" class="priceButton">
                <h2><!--product price --></h2>
                <button>confirm</button>
                <button id="editButton">edit</button>
            </div>
            <div id="accordionmenu">
                <span id="showroomdescription" class="nobottomborder">description</span>
                <span id="showroomsizes">sizes</span>
                <span id="showroomdelivery">delivery</span>
                <p id="productdescription" class="displayblock">
                
                </p>
                <p id="productsizes">
                   
                </p>
                <p id="productdelivery">
               
                </p>
            </div>
        </div>
    </div>
    <script src="/js/page/productshowroom.js" type="text/javascript"></script>
</template>
