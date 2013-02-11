<?php $this->load->view('user/components/page_head'); ?>

<div id="topbar">
    <ul id="main-nav" class="nav">
        <li><?php echo anchor('user/usermanagement', 'Spiel'); ?></li>
        <li><?php echo anchor('user/usermanagement/edit', 'Profil'); ?></li>
        <li><?php echo anchor('user/usermanagement/history', 'Verlauf'); ?></li>
    </ul>
    <h6>Willkommen <?php echo $user->username; ?></h6>
    <?php echo anchor('user/usermanagement/logout', '<i class="icon-off"></i> logout'); ?>
</div>

<div class="subview-container">
    <?php $this->load->view($subview); ?>
</div>

<?php $this->load->view('user/components/page_foot'); ?>