<div class="modal-header">
    <h3>Registrieren</h3>
</div>
<div class="modal-body">
    <?php echo validation_errors(); ?>
    <?php if(isset($error)) {
        echo $error;
    } ?>
    <?php echo form_open('user/usermanagement/register', array('id' => 'register_form')); ?>
    <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
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
        <label class="control-label" for="inputPassword">Passwort</label>
        <div class="controls">
            <?php echo form_password( 'password' ); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <input type="checkbox"> Remember me
            </label>
            <?php echo form_submit( 'submit', 'registrieren' ); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
