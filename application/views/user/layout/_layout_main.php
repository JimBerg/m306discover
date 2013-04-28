<?php
/**
 * Main Layout
 * display navigation
 * all subviews are embed here
 * display footer
 *
 * @author: Janina Imberg
 * @version: 1.0
 * @date: 27.04.2013
 *
 */
?>
<?php $this->load->view( 'user/layout/_header' ); ?>
<div id="overlay"></div>
<div id="wrapper">
    <div id="header">
        <div id="greetings">
            <h2>Hallo, <?php echo $user->username; ?></h2>
            <?php echo anchor( 'user/logout', 'logout'); ?>
        </div>
        <ul id="main-navigation" class="navigation">
            <li class="<?php echo Helper::activeNavigation( $this->uri->segment(2), '' ); ?>"><?php echo anchor('user/', 'Spiel'); ?></li>
            <li class="<?php echo Helper::activeNavigation( $this->uri->segment(2), 'map' ); ?>"><?php echo anchor('user/map', 'Karte'); ?></li>
            <li class="<?php echo Helper::activeNavigation( $this->uri->segment(2), 'history' ); ?>"><?php echo anchor('user/history', 'Verlauf'); ?></li>
            <li class="<?php echo Helper::activeNavigation( $this->uri->segment(2), 'profile' ); ?>"><?php echo anchor('user/profile', 'Profil'); ?></li>
        </ul>

    </div>
    <div id="content">
        <?php $this->load->view( $subview ); ?>
    </div>

</div>

<?php $this->load->view( 'user/layout/_footer' ); ?>
