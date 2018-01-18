<?php

$titel = 'listslides';
$keywordContent = '';
require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';
$marketHtml = '';
$languageHtml = '';
$Html = '';
?>
<table class="admintables" id="">
        <caption>
            <h2>Sections</h2>
            <div class="tableheader">
                <form action="listproducts.php" method="post" enctype="multipart/form-data">
                    <select name="market">
                        <?=$marketHtml?>
                    </select>
                    <select name="language">
                        <?=$languageHtml?>
                    </select>
                </form>
            </div>
        </caption>
        <colgroup>
            <col class="col-id">
            <col class="col-name">
            <col class="col-description">
            <col class="col-pictures">
            <col class="col-actions">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Titel</th>
                <th scope="col">Salesline</th>
                <th scope="col">Pictures</th>
                <th scopt="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?=$Html?>
        </tbody>
</table>
<?php $script = '';
require_once 'adminpagefooter.php'; ?>
