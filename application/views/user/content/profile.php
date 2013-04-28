<?php
/**
 * Profile Template
 * Layout for userprofile and editing profile
 *
 * @author: Janina Imberg
 * @version: 1.0
 * @date: 27.04.2013
 *
 */
?>
<?php if( isset( $message ) ): ?>
    <div id="profile-edit-success"><?php echo $message; ?></div>
<?php endif; ?>
<div class="container profile">
    <div class="arrow"></div>
    <div class="profile-wrapper">
        <div class="profile-head">
            <div id="avatar"><!-- dummy pic --></div>
            <h1><?php echo $user->username; ?></h1>
            <h2 class="subtitle"><?php echo $profile->rank; ?></h2>
        </div>
        <div class="profile-content">
            <?php echo validation_errors(); ?>
            <?php echo form_open( 'user/updateProfile' ); ?>
            <?php echo form_hidden( array( 'user_id' => $user->id ) ); ?>
            <h2>Benutzerdaten</h2>
            <ul id="profile-form">
                <li>
                    <h3>Vorname</h3>
                    <?php echo form_input( array( 'name' => 'firstname', 'id' => 'firstname', 'value' => $profile->firstname ) ); ?>
                </li>
                <li>
                    <h3>Nachname</h3>
                    <?php echo form_input( array( 'name' => 'lastname', 'id' => 'lastname', 'value' => $profile->lastname ) ); ?>
                </li>
                <li>
                    <h3>Passwort</h3>
                    <?php echo form_password( array( 'name' => 'password', 'id' => 'password', 'value' => $user->password ) ); ?>
                </li>
            </ul>
            <hr />
            <h2>Gesamtübersicht</h2>
            <ul class="profile-summary">
                <li>
                    <h3>Punkte</h3>
                    <div><?php echo $profile->points; ?></div>
                </li>
                <li>
                    <h3>Aufgaben</h3>
                    <!-- TODO: GET NUMBER OF QUEST -->
                    <div><?php echo $profile->points; ?></div>
                </li>
            </ul>
            <hr />
            <div id="profile-delete">
                <?php echo form_button( array( 'name' => 'profile-delete', 'id' => 'profile-delete', 'content' => 'profil löschen' ) ); ?>
            </div>
            <div id="profile-submit">
                <?php echo form_submit( 'submit', 'speichern' ); ?>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
