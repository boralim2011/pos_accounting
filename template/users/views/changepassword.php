<div class="col-lg-12">
    <div class="col-lg-5" style="margin: 0 auto; float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong><?php echo $title; ?></strong></div>
            <div class="panel-body">
                <?php
                echo form_open('users/changepassword/', 'changepassword');
                echo $this->ms->getMessage();
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-key"></i></span>';
                echo form_password('newpassword', '', 'id="newpassword" class="form-control" required style="width: 100%;" placeholder="New password..."');
                echo '</div>';
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-key"></i></span>';
                echo form_password('c-newpassword', '', 'id="c-newpassword" class="form-control" required style="width: 100%;" placeholder="Confirm new password..."');
                echo '</div>';
               
                echo form_submit('changepassword', $this->lang->line('update'), 'class="btn btn-primary"');
                echo nbs(3);
                echo '<small>';
                echo anchor('users/profile', $this->lang->line('cancel'),'class="btn btn-default"');
                echo '</small>';
                echo form_close();
                ?>
            </div>
        </div>
    </div
</div>
</div>
