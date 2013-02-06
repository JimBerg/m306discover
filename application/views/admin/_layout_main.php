<?php $this->load->view('admin/components/page_head'); ?>
<body>
<div class="navbar navbar-static-top navbar-inverse">
    <div class="navbar-inner">
        <a class="brand" href="<?php echo site_url('admin/dashboard'); ?>"><?php echo $pagetitle; ?></a>
        <ul class="nav">
            <li class="active"><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
            <li><?php echo anchor('admin/page', 'pages'); ?></li>
            <li><?php echo anchor('admin/user', 'users'); ?></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Main column -->
        <div class="span9">
            <section>
                <h2>Hallo <?php echo $user->username; ?></h2>
                <?php $this->load->view($subview); ?>
            </section>
        </div>
        <!-- Sidebar -->
        <div class="span3">
            <section>
                <?php echo anchor('admin/user/logout', '<i class="icon-off"></i> logout'); ?>
            </section>
        </div>
    </div>
</div>

<?php $this->load->view('admin/components/page_foot'); ?>