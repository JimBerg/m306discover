<?php $this->load->view('user/components/page_head'); ?>
<body>
<div class="navbar navbar-static-top navbar-inverse">
    <div class="navbar-inner">
        <ul class="nav">
            <li><?php echo anchor('user/usermanagement', 'Spiel'); ?></li>
            <li><?php echo anchor('user/usermanagement/edit', 'Profil'); ?></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Main column -->
        <div class="span9">
            <section>
                <?php $this->load->view($subview); ?>
            </section>
        </div>
        <!-- Sidebar -->
        <div class="span3">
            <section>
                <h6>Willkommen <?php echo $user->username; ?></h6>
                <?php echo anchor('user/usermanagement/logout', '<i class="icon-off"></i> logout'); ?>
            </section>
        </div>
    </div>
</div>

<?php $this->load->view('user/components/page_foot'); ?>