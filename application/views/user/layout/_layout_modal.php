<?php $this->load->view( 'user/layout/_header' ); ?>
<div id="wrapper">
    <div id="header">
    </div>
    <div id="dialog" class="modal show" role="dialog" >
        <?php $this->load->view( $subview ); ?>
    </div>
</div>
<?php $this->load->view( 'user/layout/_footer' ); ?>