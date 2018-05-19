<?php
?>
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

