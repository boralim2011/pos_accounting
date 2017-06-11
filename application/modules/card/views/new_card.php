<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($title)? $title:'New Card'; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> home</a></li>
    <li><a href="#card"><i class="fa fa-id-card-o"></i> Manage Card</a></li>
    <li class="active"><?php echo isset($title)? $title:'New Card'; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <form id="card-form" role="form" action="<?php echo $url;?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="box-header with-border">
                <h3 class="box-title">Card Info:</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="card_type_id">Card Type</label>
                        <div class="input-group">
                            <select class="select2" id="card_type_id" name="card_type_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($card->card_type_id!=0){?>
                                    <option value="<?php echo $card->card_type_id; ?>" selected="selected"><?php echo $card->card_type_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-card_type" data-toggle="tooltip" title="New Card Type">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <input type="hidden" id="card_id" name="card_id" value="<?php echo $card->card_id;?>" />

                        <label for="card_number">Card Number</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Enter card number" value="<?php echo $card->card_number; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="card_name">Card Name</label>
                        <input type="text" class="form-control" id="card_name" name="card_name" placeholder="Enter card name" value="<?php echo $card->card_name; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="card_name_kh">Card Name(Khmer)</label>
                            <input type="text" class="form-control" id="card_name_kh" name="card_name_kh" placeholder="Enter card name" value="<?php echo $card->card_name_kh; ?>">
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label >Gender</label>
                        <select class="select2" id="gender" name="gender" data-placeholder="--Select--" style="width: 100%;">
                            <option></option>
                            <option value="F" <?php echo isset($card->gender) && $card->gender=="F"?'selected="selected"':''; ?> >Female</option>
                            <option value="M" <?php echo isset($card->gender) && $card->gender=="M"?'selected="selected"':''; ?> >Male</option>
                            <option value="O" <?php echo isset($card->gender) && $card->gender=="O"?'selected="selected"':''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="age_range_id">Age Range</label>
                        <div class="input-group">
                            <select class="select2" id="age_range_id" name="age_range_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($card->age_range_id!=0){?>
                                    <option value="<?php echo $card->age_range_id; ?>" selected="selected"><?php echo $card->age_range_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-age_range" data-toggle="tooltip" title="New Age Range">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label><span class="text-red">*</span> Register Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" name="register_date" id="register_date" value="<?php echo $card->register_date;?>">
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label><span class="text-red">*</span> Expired Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" name="expired_date" id="expired_date" value="<?php echo $card->expired_date;?>">
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" value="<?php echo $card->phone_number; ?>">
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="discount_rate">Discount Rate</label>
                        <input type="text" class="form-control" id="discount_rate" name="discount_rate" placeholder="Enter card name" value="<?php echo $card->discount_rate; ?>">
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="is_active">&nbsp;</label>
                        <div>
                            <label><input type="checkbox" class="minimal form-control" id="manage_stock" name="is_active" <?php echo $card->is_active==1?'checked="checked"':"";?> > Active</label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box-footer">
                <button type="button" class="btn btn-primary" name="submit" id="submit"><i class="fa fa-save"></i> Submit </button>
                <a href="#card" class="btn btn-default"><i class="fa fa-close"></i> Cancel </a>
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

        //Timepicker
        $(".timepicker").timepicker({
            showInputs: false
        });

        $("#gender").select2();

        $("#card_type_id").select2({
            ajax: {
                url: "<?php echo base_url()?>card_type/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
                //,allowClear: true
            },
            minimumInputLength: 1
        });

        $("#age_range_id").select2({
            ajax: {
                url: "<?php echo base_url()?>age_range/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
                //,allowClear: true
            },
            minimumInputLength: 1
        });


    });
</script>


<script type="text/javascript">

    $(document).ready(function()
    {
        $(document).off("click","#submit");
        $(document).on("click","#submit", function( event )
        {
            event.preventDefault();

            //alert("click submit"); return;

            var form = $("#card-form");
            var formData = new FormData(form[0]);
            var url = form.attr('action').toString();

            //var formData = new FormData();
            formData.append('submit', 1);
            // Main magic with files here
            //formData.append('file', $('input[type=file]')[0].files[0]);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType:"json",
                //async: false,
                cache: false,
                beforeSend: function(xhr){
                    //alert('Before send');
                },
                success: function(data, status, xhr)
                {
                    //alert(data);
                    if(data==521){
                        go_to_login();
                    }
                    else
                    {
                        if(data.success===true){
                            $("#card_id").val(data.model.card_id);
                            $("#address_id").val(data.model.address_id);
                        }
                        //alert(data.message);
                        show_message(data.message, $("#message"));
                    }
                },
                error: function(xhr,status,error){
                    var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                    show_message(message, $("#modal-message"));
                },
                complete: function(xhr,status){
                    //alert(status);
                },
                contentType: false,
                processData: false
            });

            return false;
        });
    });

</script>


<?php $this->load->view('card_type/new_card_type'); ?>
<?php $this->load->view('age_range/new_age_range'); ?>


