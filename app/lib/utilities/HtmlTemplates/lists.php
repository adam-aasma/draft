<?php

?>

<div id="adminlists">
<link href="css/adminlists.css" type="text/css" rel="stylesheet">
    <div class="left">
        <select>
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
                    <th scope="col">Name</th>
                    <th scope="col">description</th>
                    <th scope="col">pictures</th>
                    <th scope="col">item details</th>
                    <th scope="col">mar/lang</th>
                    <th scopt="col">create</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a></a></td>
                    <td><span></span></td>
                    <td><div ></div></td>
                    <td><div ><a >preview</a></div></td>
                    <td><div></div></td> 
                    <td><div ></div> </td>
                    <td>
                        <div>
                            <a >addslide</a>
                            <a >addtosection</a>
                        </div></td>
                </tr>
            </tbody>
    </table>
</div>
<template>
    <tr>
        <td><a></a></td>
        <td><span></span></td>
        <td><div ></div></td>
        <td><div><a >preview</a></div></td>
        <td><div></div></td> 
        <td>
            <div >
                
                <a >addslide</a>
            </div>
        </td>
        <td><div></div></td>
    </tr>
    
</template>
