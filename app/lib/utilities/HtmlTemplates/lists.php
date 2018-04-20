<?php

?>

<div id="adminlists">
<link href="css/adminlists.css" type="text/css" rel="stylesheet">
    <div class="left">
        <select id="listCategory">
            <option>products</option>
            <option>sections</option>
            <option>slides</option>
        </select>
    </div>
    <div id="addNew">
        <span>new</span>
    </div>
    <div class="right">
        <h2>filters</h2>
        <select>
            <option>markets</option>
        </select>
        <select>
            <option>languages</option>
        </select>
    </div>
    
</div>
<div id="adminlistbody">
    
</div>
<script src="js/page/adminlists.js" type="text/javascript"></script>
<template id="sectionTableBody">
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
                <th scope="col">title</th>
                <th scope="col">salesline</th>
                <th scope="col">description</th>
                <th scope="col">pictures</th>
                <th scope="col">products</th>
                <th scope="col">mar/lang</th>
                <th scopt="col">create</th>
            </tr>
        </thead>
        <tbody id="tbody">

        </tbody>
    </table>
</template>

<template id="sectionListRow">
    <tr  class="tablerow">
        <td class="id"><a></a></td>
        <td class="section-title"><div></div></td>
        <td class="section-salesline"><div></div></td>
        <td class="section-description"><div><h2></h2></div></td>
        <td class="section-pictures"></td> 
        <td class="section-products"></td>
        <td class="section-marlang"></td>
        <td>
            <div>
                <p>anything else?</p>
            </div>
        </td>
    </tr>
</template>

<template id="sectionCopy">
    <div>
        <h2></h2>
        <span></span>
    </div>
</template>

<template id="sectionPictures">
    <div>
        <h2></h2>
        <span></span>
        <img class="imagethumbnails" alt="missing"/>
    </div>
</template>

<template id="sectionProducts">
    <div>
        <h2></h2>
        <span>nr of products:<i></i></span>
    </div>
</template>

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

