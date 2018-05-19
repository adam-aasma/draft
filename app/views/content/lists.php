<?php
$title;
$keywordContent;
require_once __DIR__ . '/../shared/adminpageheader.php';

require_once 'templates/productlist.php';
require_once 'templates/sectionlist.php';
?>

<div id="adminlists">
<link href="/css/adminlists.css" type="text/css" rel="stylesheet">
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

<!--TODO AJAX DEPENDENCY NOT YET LOADED, LOADED NORMALLY WITH FOOTER????
TEMPORARY SOLUTION LOADING IT HERE-->
<script type="text/javascript" src="/js/general/template.js"></script>
<script type="text/javascript" src="/js/general/ajax.js"></script>
<script src="/js/page/adminlists.js" type="text/javascript"></script>
<script src="/js/general/section.js" type="text/javascript"></script>

<?php
require_once __DIR__ . '/../shared/adminpagefooter.php';
