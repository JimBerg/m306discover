<?php
/**
 * Index Template
 * Start page - shows next quest
 *
 * @author: Janina Imberg
 * @version: 1.0
 * @date: 27.04.2013
 *
 */
?>

<div class="container profile">
    <div class="arrow"></div>
    <div class="profile-wrapper">
        <div class="profile-head">
            <div id="quest-icon"><!-- dummy pic --></div>
            <h1><?php echo $user->username; ?></h1>
            <h2 class="subtitle"><?php echo 'Deine nächste Aufgabe:' ?></h2>
        </div>
        <div class="profile-content">
            <h3><?php echo $quest->quest; ?></h3>
        </div>
    </div>
</div>