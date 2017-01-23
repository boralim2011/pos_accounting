<?php
echo form_open('users/updateprofile', 'updateprofile');
?>
<div class="col-lg-12">
    <?php echo $this->ms->getMessage(); ?>
    <div class="col-lg-5">
        <label>General Information</label>
        <div class="form-group input-group has-error" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>
            <?php echo form_input('firstname', set_value('firstname', getFirstname()), 'id="firstname" class="form-control" required="required" style="width: 100%;" placeholder="First name..."'); ?>
        </div>
        <div class="form-group input-group has-error" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>
            <?php echo form_input('lastname', set_value('lastname', getLastname()), 'id="lastname" class="form-control" required style="width: 100%;" placeholder="Last name..."'); ?>
        </div>
        <div class="form-group" style="width: 100%;">
            <label>Gender</label>
            <label class="radio-inline">
                <input type="radio"  value="Female" name="gender" <?php echo getUserGender()=='Female'?' checked="checked" ':''; echo set_radio('gender', 'Female'); ?> /> Female
            </label>
            <label class="radio-inline">
                <input type="radio" value="Male" name="gender" <?php echo getUserGender()=='Male'?' checked="checked" ':'';echo set_radio('gender', 'Male'); ?> /> Male
            </label>
            <label class="radio-inline">
                <input type="radio" value="Other" name="gender" <?php echo getUserGender()=='Other'?' checked="checked" ':''; echo set_radio('gender', 'Other'); ?> /> Other
            </label>
        </div>
        <div class="form-group input-group has-error" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-envelope"></i></span>
            <?php echo form_input('email', set_value('email', getUserEmail()), 'id="email" class="form-control" required style="width: 100%;" placeholder="Email..."'); ?>
        </div>
        <div class="form-group input-group" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-phone"></i></span>
            <?php echo form_input('phone', set_value('phone', getUserPhone()), 'id="phone" class="form-control" style="width: 100%;" placeholder="Phone number..."'); ?>
        </div>
        <div class="form-group input-group" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-link"></i></span>
            <?php echo form_input('url', set_value('url', getUserUrl()), 'id="url" class="form-control" style="width: 100%;" placeholder="Website..."'); ?>
        </div>
        <div class="form-group input-group" style="width: 100%;">
            <span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-home"></i></span>
            <?php $atts = array('name' => 'address', 'id' => 'address', 'class' => 'form-control', 'style' => 'width: 100%;', 'rows' => '2', 'placeholder' => 'Detail your address...'); ?>
            <?php echo form_textarea($atts, set_value('address', getUserAddress())); ?>
        </div>
    </div>
    <div class="col-lg-1">
    </div>
    <div style="clear:both;"></div>
    <div class="col-lg-12">
        <?php
        echo form_submit('updateprofile', $this->lang->line('update'), 'class="btn btn-primary"');
        echo nbs(3);
        echo anchor('users/profile', $this->lang->line('cancel'),'class="btn btn-default"');
        ?>
    </div>
</div>
<?php
echo form_close();
?>
