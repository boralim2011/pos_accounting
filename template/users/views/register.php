<div class="col-lg-12">
    <div class="col-lg-10" style="margin: 0 auto; float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong><?php echo $title; ?></strong></div>
            <div class="panel-body">
                <?php
                echo form_open('users/register', 'register');
                echo $this->ms->getMessage();
                ?>
                <div class="col-lg-5">
                    <label>General Information</label>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>
                        <?php echo form_input('firstname', set_value('firstname', ''), 'id="firstname" class="form-control" required style="width: 100%;" placeholder="First name..."'); ?>
                    </div>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>
                        <?php echo form_input('lastname', set_value('lastname', ''), 'id="lastname" class="form-control" required style="width: 100%;" placeholder="Last name..."'); ?>
                    </div>
                    <div class="form-group" style="width: 100%;">
                        <label>Gender</label>
                        <label class="radio-inline">
                            <input type="radio" checked="" value="Female" name="gender" <?php echo set_radio('gender', 'Female'); ?> /> Female
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="Male" name="gender" <?php echo set_radio('gender', 'Male'); ?> /> Male
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="Other" checked="checked"  name="gender" <?php echo set_radio('gender', 'Other'); ?> /> Other
                        </label>
                    </div>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-envelope"></i></span>
                        <?php echo form_input('email', set_value('email', ''), 'id="email" class="form-control" required style="width: 100%;" placeholder="Email..."'); ?>
                    </div>
                    <div class="form-group input-group" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-phone"></i></span>
                        <?php echo form_input('phone', set_value('phone', ''), 'id="phone" class="form-control" style="width: 100%;" placeholder="Phone number..."'); ?>
                    </div>
                    <div class="form-group input-group" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-link"></i></span>
                        <?php echo form_input('url', set_value('url', ''), 'id="url" class="form-control" style="width: 100%;" placeholder="Website..."'); ?>
                    </div>
                    <div class="form-group input-group" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-home"></i></span>
                        <?php $atts = array('name'=>'address','id'=>'address','class'=>'form-control','style'=>'width: 100%;','rows'=>'2','placeholder'=>'Detail your address...'); ?>
                        <?php echo form_textarea($atts,set_value('address', '')); ?>
                    </div>
                </div>
                <div class="col-lg-2">
                </div>
                <div class="col-lg-5">
                    <label>Login Information</label>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>
                        <?php echo form_input('username', set_value('username', ''), 'id="username" class="form-control" required style="width: 100%;" placeholder="Username..."'); ?>
                    </div>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-key"></i></span>
                        <?php echo form_password('password', set_value('password', ''), 'id="password" class="form-control" required style="width: 100%;" placeholder="Password..."'); ?>
                    </div>
                    <div class="form-group input-group has-error" style="width: 100%;">
                        <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-key"></i></span>
                        <?php echo form_password('c-password', set_value('c-password', ''), 'id="c-password" class="form-control" required style="width: 100%;" placeholder="Confirm password..."'); ?>
                    </div>
                    <p>
                        Your information will not be public!<br />
                        We hate spam!
                    </p>
                </div>
                <div style="clear:both;"></div>
                <div class="col-lg-12">
                    <?php
                    echo form_submit('register', $this->lang->line('register'), 'class="btn btn-primary"');
                    echo nbs(3);
                    echo '<small>';
                    echo anchor('users/login', $this->lang->line('login'));
                    echo nbs();
                    echo '|';
                    echo nbs();
                    echo anchor('users/forgetpassword', $this->lang->line('forget_password'));
                    echo '</small>';
                    echo form_close();
                    ?>
                </div>

            </div>
        </div>
    </div
</div>
</div>
