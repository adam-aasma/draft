<?php
?>
<template id="productsTableBody">
    <table class="admintables">
        <colgroup>
            <col class="col-idx">
            <col class="col-namex">
            <col class="col-descriptionx">
            <col class="col-picturesx">
            <col class="col-itemdetailsx">
            <col class="col-statisticsx">
            <col class="col-sectionx">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">description</th>
                <th scope="col">pictures</th>
                <th scope="col">item details</th>
                <th scope="col">mar/lang</th>
                <th scopt="col">create</th>
            </tr>
        </thead>
        <tbody id="tbody">

        </tbody>
    </table>
</template>

<template id="productListRow">
    <tr  class="tablerow">
        <td class="id"><a></a></td>
        <td class="product-name"><div></div></td>
        <td class="product-description"><div></div></td>
        <td class="product-pictures"><div><h2></h2></div></td>
        <td class="product-items"></td> 
        <td class="product-marlang"></td>
        <td>
            <div>
                <a>addslide</a>
                <a>addtosection</a>
            </div>
        </td>
    </tr>
    
</template>
<template id="productContent">
    <div>
        <h2></h2>
        <p></p>
    </div>
</template>
<template id="productPictures">
    <div class="thumbnailwrapper">
        <span></span>
        <img class="imagethumbnails" alt="missing" />
    </div>
    
</template>


