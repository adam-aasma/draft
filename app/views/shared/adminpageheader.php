<?php

?>
<html>
    <head>
        <title><?= $title ?></title>
        <meta name="keywords" content="<?= $keywordContent ?>">
        <link href="/css/Controller.css" type="text/css" rel="stylesheet">
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
                    <?php foreach($menu as $name => $url) : ?>
                        <li><a href="<?= $url ?>"><span><?= $name ?></span></a></li>
                    <?php endforeach; ?>
                    </ul>
                </nav>
                <div class="desktop">

        