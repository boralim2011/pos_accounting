<div class="col-lg-12">
    <div class="col-lg-5" style="margin: 0 auto; float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong><?php echo $title; ?></strong></div>
            <div class="panel-body">
                <?php
                echo form_open('users/forgetpassword', 'forgetpassword');
                echo $this->ms->getMessage();
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-user"></i></span>';
                echo form_input('username', '', 'id="username" class="form-control" required style="width: 100%;" placeholder="Username..."');
                echo '</div>';
                echo '<div class="form-group input-group" style="width: 100%;">';
                echo '<span class="input-group-addon input-sm" style="width: 40px;"><i class="fa fa-envelope"></i></span>';
                echo form_input('email', '', 'id="email" class="form-control" required style="width: 100%;" placeholder="Email..."');
                echo '</div>';

                echo form_submit('forget-password', $this->lang->line('send'), 'class="btn btn-primary"');
                echo nbs(3);
                echo '<small>';
                echo anchor('users/register', $this->lang->line('register'));
                echo nbs();
                echo '|';
                echo nbs();
                echo anchor('users/login', $this->lang->line('login'));
                echo '</small>';
                echo form_close();
                ?>
            </div>
        </div>
    </div
</div>
</div>
