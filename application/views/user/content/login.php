<div class="modal-header">
    <h3>Login</h3>
</div>
<div class="modal-body">
    <?php echo validation_errors(); ?>
    <?php echo form_open(); ?>
    <div class="control-group">
        <label class="control-label">Benutzername</label>
        <div class="controls">
            <?php echo form_input( array( 'name' => 'username', 'id' => 'username' ) ); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Password</label>
        <div class="controls">
            <?php echo form_password('password'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?php echo form_submit( 'submit', 'anmelden', 'class="btn btn-primary"' ); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
