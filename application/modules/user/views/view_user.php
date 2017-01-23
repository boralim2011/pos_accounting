<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($title)? $title:'View User'; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> home</a></li>
    <li><a href="#user"><i class="fa fa-user"></i> Manage User</a></li>
    <li class="active"><?php echo isset($title)? $title:'New User'; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <form id="user-form" role="form" action="<?php echo $url;?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="box-header with-border">
                <h3 class="box-title">User Info:</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">

                <input type="hidden" id="user_id" name="user_id" value="<?php echo isset($user->user_id)? $user->user_id:0;?>"/>

                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <img src="<?php echo $user->photo_path;?>" align="left" style="width: 100px; height: 100px; margin: 0 15px 5px 0;"/>

                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Member of</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-share-alt"></i>
                            </div>
                            <input type="text" class="form-control" id="user_group" name="user_group" placeholder="Enter user group name" value="<?php echo isset($user->user_group_name)?$user->user_group_name:''; ?>">

                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="user_name">User Name</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter user name" value="<?php echo $user->user_name; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="email">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $user->email; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="password">Password</label>
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa  fa-lock"></i>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="<?php echo $user->password; ?>" <?php if(isset($readonly)) echo 'readonly="readonly"';?> >
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa  fa-lock"></i>
                            </div>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter password" value="<?php echo $user->password; ?>" <?php if(isset($readonly)) echo 'readonly="readonly"';?>  >
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="is_active">&nbsp;</label>
                        <div>
                            <label><input type="checkbox" class="minimal form-control" id="is_active" name="is_active"  <?php if($user->is_active==1) echo 'checked="checked"';?>  > Active</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label>All Roles</label>
                                <select id="user_roles_base" name="user_roles_base[]" multiple="multiple" class="form-control" data-placeholder="Select Groups"  style="width: 100%; height: 136px; overflow: auto;">
                                    <?php
                                        if(isset($user_roles) && is_array($user_roles)){
                                            foreach($user_roles as $ug){
                                                $append = true;
                                                if(isset($permission)){
                                                    foreach($permission as $p){
                                                        if($p->user_role_id==$ug->user_role_id){
                                                            $append = false;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if(!$append) continue;
                                            ?>
                                        <option value="<?php echo $ug->user_role_id;?>"><?php echo $ug->user_role_name;?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                                <div>
                                    <label>&nbsp;</label>
                                </div>
                                <div class="btn-group-vertical small-inline">
                                    <button type="button" class="btn btn-default btn-flat" id="btn-nexts"><i class="fa fa-angle-double-right"></i></button>
                                    <button type="button" class="btn btn-default btn-flat" id="btn-next"><i class="fa fa-angle-right"></i></button>
                                    <button type="button" class="btn btn-default btn-flat" id="btn-prev"><i class="fa fa-angle-left"></i></button>
                                    <button type="button" class="btn btn-default btn-flat" id="btn-prevs"><i class="fa fa-angle-double-left"></i></button>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label>Seleted Roles</label>
                                <select id="user_roles" name="user_roles[]" multiple="multiple" class="form-control" data-placeholder="Select Groups"  style="width: 100%; height: 136px; overflow: auto;">
                                    <?php
                                        if(isset($user_roles) && is_array($user_roles)){
                                            foreach($user_roles as $ug){
                                                $append = false;
                                                if(isset($permission)){
                                                    foreach($permission as $p){
                                                        if($p->user_role_id==$ug->user_role_id){
                                                            $append = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if(!$append) continue;
                                            ?>
                                        <option value="<?php echo $ug->user_role_id;?>"><?php echo $ug->user_role_name;?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="#user" class="btn btn-default"><i class="fa fa-close"></i> Cancel </a>
            </div>
        </form>

    </div><!-- /.box -->

    <div id = "message" >
        <?php if(isset($message)) $this->show_message($message); ?>
    </div>

</section><!-- /.content -->


<script type="text/javascript">

    $(document).ready(function()
    {
        //Initialize Select2 Elements
        //$(".select2").select2();

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        //date picker
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });


    });
</script>

<script type="text/javascript">
    $(document).ready(function()
    {
        $(document).off("click", "#btn-nexts");
        $(document).on("click", "#btn-nexts", function(event){
            event.preventDefault();
            var options = $("#user_roles_base").html();
            $("#user_roles").append(options);
            $("#user_roles_base").empty();
        });
        $(document).off("click", "#btn-next");
        $(document).on("click", "#btn-next", function(event){
            event.preventDefault();
            var options = $("#user_roles_base option:selected");
            $("#user_roles").append(options);
            $("#user_roles_base").remove(options);
        });

        $(document).off("click", "#btn-prevs");
        $(document).on("click", "#btn-prevs", function(event){
            event.preventDefault();
            var options = $("#user_roles").html();
            $("#user_roles_base").append(options);
            $("#user_roles").empty();
        });
        $(document).off("click", "#btn-prev");
        $(document).on("click", "#btn-prev", function(event){
            event.preventDefault();
            var options = $("#user_roles option:selected");
            $("#user_roles_base").append(options);
            $("#user_roles").remove(options);
        });

    });
</script>



