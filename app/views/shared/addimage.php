<?php
$legend;
?>
<style>
  @import url("/css/general/addpicturestyles.css");
</style>
<fieldset id='addPictures' class="border left">
    <legend><?= $legend ?></legend>
    <div id="addImageDiv" class="row">
        <form method="POST" action="addpicture" id="picture"></form>
        <div class="trio left">
            <input type="file" />
        </div>
        <div id='imageCheckBoxWrapper' class="duo left">
        <?php $idx = 0; foreach($imageCategories as $category) : ?>
            <?php $checked = !$idx ? 'checked' : ''; ?>
            <div >
                <label><?= $category->category ?></label> 
                <input class="right" type="radio" name="category" value="<?= $category->id ?>"<?= $checked ?>/>
            </div>
            <?php $idx++ ?>
        <?php endforeach; ?>
        </div>
        <div class="right">
            <button type="submit" id="submit" value="save" form="picture">Add</button>
        </div>
    </div>
    <template id='createImageAddedRow'>
        <div class='row'>
            <span class='duo left'></span>
            <button class='right'>Delete</button>
        </div>
    </template>
    <script>
        var imageCategoriesNames = <?= json_encode($json_imageCategories) ?>
    </script>
    <script src="/js/general/addimage.js" type="text/javascript"></script>
</fieldset>
