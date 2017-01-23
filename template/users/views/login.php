<div class="col-lg-12">
    <div class="col-lg-5 login" style="margin: 0 auto; float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong><?php echo $title; ?></strong></div>
            <div class="panel-body">
                <?php
                echo form_open('users/login', 'login');
                echo $this->ms->getMessage();
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>';
                echo form_input('username', '', 'id="username" class="form-control" required style="width: 100%;" placeholder="Username..."');
                echo '</div>';
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-key"></i></span>';
                echo form_password('password', '', 'id="password" class="form-control" required style="width: 100%;" placeholder="Password..."');
                echo '</div>';
                echo '<div class="checkbox">';
                echo form_checkbox('remember', '1', '', 'id="remember"');
                echo form_label('Remember me', 'remember');
                echo '</div>';
                echo form_submit('login', $this->lang->line('login'), 'class="btn btn-primary"');
                echo nbs(3);
                echo '<small>';
                echo anchor('users/register', $this->lang->line('register'));
                echo nbs();
                echo '|';
                echo nbs();
                echo anchor('users/forgetpassword', $this->lang->line('forget_password'));
                echo '</small>';
                echo form_close();
                ?>
            </div>
        </div>
    </div
</div>
</div>
