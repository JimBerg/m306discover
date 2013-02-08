<div class="modal-header">
    <h3>Registrieren</h3>
</div>

<div class="modal-body">
    <?php echo validation_errors(); ?>
    <?php if ( isset( $error ) ) : ?><span><?php echo $error; ?></span><?php endif; ?>

    <?php echo form_open('user/usermanagement/register', array('id' => 'register_form')); ?>
    <div class="control-group">
        <label class="control-label" for="email">Email</label>
        <div class="controls">
            <?php echo form_input(array('name' => 'email', 'id' => 'email')); ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="username">Username</label>
        <div class="controls">
            <?php echo form_input(array('name' => 'username', 'id' => 'username')); ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="password">Passwort</label>
        <div class="controls">
            <?php echo form_password( 'password' ); ?>
        </div>
    </div>

    <!-- PASSWORD CONFIRM -->

    <input type="hidden" id="position-lat" name="position-lat" />
    <input type="hidden" id="position-lng" name="position-lng" />
    <?php echo form_submit( 'submit', 'registrieren' ); ?>

    <?php echo form_close(); ?>
</div>
