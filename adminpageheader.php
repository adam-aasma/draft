
<html>
    <head>
        <title><?= $title ?></title>
        <meta name="keywords" content="<?= $keywordContent ?>">
        <link href="css/AdminBase.css" type="text/css" rel="stylesheet">
        <link href="<?=$css ?>" type="text/css" rel="stylesheet"> -->
       <!--  <link href="css/admin.css" type="text/css" rel="stylesheet"> 
        <!--<script>var initFunctionTable = [];</script> -->
    </head>
    <body id="bodyLoad">
        <div id="jsAdmin" class="admin">
            <header class="header">
                <h1>Admin Panel</h1>
                <p>happy workday Dear <?= $user->name ?>!</p>
                <h2>user profile</h2>
            </header>
            <div class="flex-wrapper">
                <nav class='menu hover'>
                    <ul>
                    <?php foreach($menu as $key => $value) {
                        DisplayLink($key, $value, !IsURLCurrentPage($value));
                    } ?>
                    </ul>
                </nav>
                <div class="desktop">

        