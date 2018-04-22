<?php
 ?>
<div id="section">
    <link href="css/section2.css" type="text/css" rel="stylesheet">
    <template id="sectionUpper">
        <h1 data-item="titel">titel</h1>
        <div id="upperwrapper" class=" border">
            <figure id="leftsection" class="border">
                    
                    <div id="gradient">
                        <div id="content">
                            <h2 data-item ="salesline">firstline</h2>
                            <h3 data-item ="salesline2">secondline</h3>
                            <span>button</span>
                        </div>
                    </div>
                
            </figure>
            <figure id="rightsection" class=" border">
                
            </figure>

        </div>
    </template>
    <template id="sectionLower">
        <div id="lowerwrapper" class="border">
            <div class="productpicture border">
                <div class="upperwrapper border">
                   <img src="" class="border" /> 
                </div>
                <div class="lowerwrapper border">
                    <h2>name</h2>
                    <h2>price</h2>
                    <span>buy</span>
                </div>
            </div>
            <div class="productpicture border">
                <div class="upperwrapper border">

                </div>
                <div class="lowerwrapper border">
                    <h2>name</h2>
                    <h2>price</h2>
                    <span>buy</span>
                </div>
            </div>
            <div class="productpicture border">
                <div class="upperwrapper border">

                </div>
                <div class="lowerwrapper border">
                    <h2>name</h2>
                    <h3>price</h3>
                    <span>buy</span>
                </div>
            </div>
            <div class="productpicture border">
                <div class="upperwrapper border">
                    <img src="" class="border" />

                </div>
                <div class="lowerwrapper border">
                    <h2>name</h2>
                    <h3>price</h3>
                    <span>buy</span>

                </div>
            </div>
        </div>
    </template>
    <template id="gradientPic">
        <img src="" id="leftpic"/>
        <div id="gradient">
            <div id="content">
                <h2 data-item ="salesline">firstline</h2>
                <h3 data-item ="salesline2">secondline</h3>
                <span>button</span>
            </div>
        </div>
    </template>
    <template id ="bigPic">
        <img/>
    </template>
</div>
<script src="js/general/section.js" type="text/javascript"></script>
