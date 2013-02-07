<?php $this->load->view('user/components/page_head'); ?>

<body>

<div class="modal show" role="dialog">
    <?php $this->load->view($subview); ?>
    <div class="modal-footer">
        &copy; <?php echo date('Y'); ?>
    </div>
</div>

<?php $this->load->view('user/components/page_foot'); ?>