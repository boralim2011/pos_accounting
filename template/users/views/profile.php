<div class="col-lg-12 row">
    <div class="col-lg-4">
        <?php
//        $atts = array(
//            'src' => ADMIN_TEMPLATE_PATH . ADMIN_TEMPLTE_NAME . '/imgs/user-profile-photo.png',
//            'alt' => getFirstname() . 'Profile',
//            'width'=>'100%'
//        );
//        echo img($atts);
        ?>
        <h3><?php echo getFirstname() . ' ' . getLastname(); ?></h3>
        <p class=''>
            <?php echo getUserGender(); ?><br />
            <?php echo getUserPhone(); ?><br />
            <?php echo getUserEmail(); ?><br />
            <?php echo getUserUrl(); ?><br />
        </p>
        <address>
            <?php echo getUserAddress(); ?><br /> 
        </address>
        <?php
        echo anchor('users/updateprofile', $this->lang->line('update_profile'), 'class="btn btn-primary"');
        echo nbs(2);
        echo anchor('users/changepassword', $this->lang->line('change_password'), 'class="btn btn-default"');

        echo br(2);
        echo anchor('http://www.pichnil.com/cpanel', $this->lang->line('cpanel_login'), 'class="btn btn-default"');
        echo nbs(2);
        echo anchor('http://mydomain.pichnil.com/', $this->lang->line('domain_login'), 'class="btn btn-default"');
        ?>
    </div>
    <div class="col-lg-8 row">
        <div class="col-lg-6" style='float:right;'>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <i class="fa fa-cloud fa-5x"></i>
                        </div>
                        <div class="col-xs-6 text-right">
                            <p class="announcement-heading">$5</p>
                            <p class="announcement-text">1 GB Disk space</p>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer announcement-bottom">
                        <div class="row">
                            <div class="col-xs-6">
                                View Details
                            </div>
                            <div class="col-xs-6 text-right">
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <i class="fa fa-cloud fa-5x"></i>
                        </div>
                        <div class="col-xs-6 text-right">
                            <p class="announcement-heading">$7</p>
                            <p class="announcement-text">2 GB Disk space</p>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer announcement-bottom">
                        <div class="row">
                            <div class="col-xs-6">
                                View Details
                            </div>
                            <div class="col-xs-6 text-right">
                                <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>