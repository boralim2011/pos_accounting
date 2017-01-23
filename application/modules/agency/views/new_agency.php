<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($title)? $title:'New Agency'; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> home</a></li>
    <li><a href="#user"><i class="fa fa-user"></i> Manage Agency</a></li>
    <li class="active"><?php echo isset($title)? $title:'New Agency'; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <form id="contact-form" role="form" action="<?php echo $url;?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="box-header with-border">
                <h3 class="box-title">Agency Info:</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="photo" name="photo" value="<?php echo $agency->photo;?>" />
                        <img src="<?php echo $agency->photo_path;?>" align="left" style="width: 100px; height: 100px; margin: 0 15px 5px 0;"/>
                        <div style="display: inline-block;">
                            <label for="file">Select logo</label>
                            <input type="file" id="file">
                            <p class="help-block">Image file only! </p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="contact_name">Agency Name</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter agency name" value="<?php echo $agency->contact_name; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="contact_name_kh">Agency Name(Khmer)</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="contact_name_kh" name="contact_name_kh" placeholder="Enter agency name" value="<?php echo $agency->contact_name_kh; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="agency_type_id">Agency Type</label>
                        <div class="input-group">
                            <select class="select2" id="agency_type_id" name="agency_type_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($agency->agency_type_id!=0){?>
                                    <option value="<?php echo $agency->agency_type_id; ?>" selected="selected"><?php echo $agency->agency_type;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-agency_type" data-toggle="tooltip" title="New Agency Type">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="phone_number">Phone Number</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" value="<?php echo $agency->phone_number; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="phone_number_2">Phone Number 2</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" class="form-control" id="phone_number_2" name="phone_number_2" placeholder="Phone number" value="<?php echo $agency->phone_number_2; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="email">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $agency->email; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="website">Website</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-wikipedia-w"></i>
                            </div>
                            <input type="text" class="form-control" id="website" name="website" placeholder="Website" value="<?php echo $agency->website; ?>">
                        </div>
                    </div>

                    <input type="hidden" id="contact_id" name="contact_id" value="<?php echo isset($agency->contact_id)? $agency->contact_id:0;?>"/>
                    <input type="hidden" id="contact_type" name="contact_type" value="<?php echo isset($agency->contact_type)? $agency->contact_type:'';?>"/>
                    <input type="hidden" id="created_date" name="created_date" value="<?php echo isset($agency->created_date)? $agency->created_date:0;?>" />
                    <input type="hidden" id="is_deletable" name="is_deletable" value="<?php echo isset($agency->is_deletable)? $agency->is_deletable:0;?>" />
                    <input type="hidden" id="contact_code" name="contact_code" value="<?php echo $agency->contact_code; ?>">

                </div>
            </div>

            <?php echo $this->load->view('contact_address');?>

            <div class="box-footer">
                <button type="button" class="btn btn-primary" name="submit" id="submit"><i class="fa fa-save"></i> Submit </button>
                <a href="#agency" class="btn btn-default"><i class="fa fa-close"></i> Cancel </a>
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

        $("#agency_type_id").select2({
            ajax: {
                url: "<?php echo base_url()?>agency_type/get_combobox_items",
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

            var form = $("#contact-form");
            var formData = new FormData(form[0]);
            var url = form.attr('action').toString();

            //var formData = new FormData();
            formData.append('submit', 1);
            // Main magic with files here
            formData.append('file', $('input[type=file]')[0].files[0]);

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
                    if(data==521){
                        go_to_login();
                    }
                    else
                    {
                        if(data.success===true){
                            $("#contact_id").val(data.model.contact_id);
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

<?php $this->load->view('contact_address_location'); ?>

<?php $this->load->view('agency_type/new_agency_type'); ?>
