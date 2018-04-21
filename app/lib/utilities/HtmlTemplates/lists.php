<?php

?>

<div id="adminlists">
<link href="css/adminlists.css" type="text/css" rel="stylesheet">
    <div class="left">
        <select id="listCategory">
            <option data-category="products">products</option>
            <option data-category="sections">sections</option>
            <option data-category="slides">slides</option>
        </select>
    </div>
    <div id="addNew">
        <span><a>new</a></span>
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
<script src="js/general/section.js" type="text/javascript"></script>
<template id="sectionTableBody">
    <table class="admintables">
        <colgroup>
            <col class="col-idx">
            <col class="col-pics">
            <col class="col-titel">
            <col class="col-salesline">
            <col class="col-description">
            <col class="col-products">
            <col class="col-marlang">
            <col class="col-create">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">pictures</th>
                <th scope="col">title</th>
                <th scope="col">salesline</th>
                <th scope="col">description</th>
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
    <tr class="tablerow">
        <td class="id"><a></a></td>
        <td class="section-pictures"><div></div></td> 
        <td class="section-title"><div></div></td>
        <td class="section-salesline"><div></div></td>
        <td class="section-description"><div></div></td>
        <td class="section-products"><div></div></td>
        <td class="section-marlang"></td>
        <td class="section-edit">
            <div>
                <button>delete</button>
            </div>
        </td>
    </tr>
</template>

<template id="sectionCopy">
    <div class="sectioncopy-section">
        <h2></h2>
        <span></span>
    </div>
</template>

<template id="sectionPictures">
    <div class="sectionPictures-section">
        <div class="picture-info">
            <h2></h2>
            <span></span>
        </div>
        <div class="thumbnailwrapper">
            
        </div>
    </div>
</template>

<template id="sectionProducts">
    <div class="sectionProducts-section">
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

