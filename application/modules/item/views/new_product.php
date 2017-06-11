<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($title)? $title:'New Item'; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> home</a></li>
    <li><a href="#item"><i class="fa fa-barcode"></i> Manage Item</a></li>
    <li class="active"><?php echo isset($title)? $title:'New Item'; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <form id="item-form" role="form" action="<?php echo $url;?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="box-header with-border">
                <h3 class="box-title">Item Info:</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="image" name="image" value="<?php echo $item->image;?>" />
                        <img src="<?php echo $item->get_image();?>" align="left" style="width: 100px; height: 100px; margin: 0 15px 5px 0;"/>
                        <div style="display: inline-block;">
                            <label for="file">Select Image</label>
                            <input type="file" id="file">
                            <p class="help-block">Image file only! </p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="item_type_id">Item Type</label>
                        <div class="input-group">
                            <select class="select2" id="item_type_id" name="item_type_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($item->item_type_id!=0){?>
                                    <option value="<?php echo $item->item_type_id; ?>" selected="selected"><?php echo $item->item_type_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-item_type" data-toggle="tooltip" title="New Item Type">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <input type="hidden" id="item_id" name="item_id" value="<?php echo $item->item_id;?>" />

                        <label for="item_code">Item Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Enter item name" value="<?php echo $item->item_code; ?>">
                        </div>
                    </div>                   
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter item name" value="<?php echo $item->item_name; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="item_name_kh">Item Name(Khmer)</label>
                            <input type="text" class="form-control" id="item_name_kh" name="item_name_kh" placeholder="Enter item name" value="<?php echo $item->item_name_kh; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="barcode">Barcode</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Enter item name" value="<?php echo $item->barcode; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" placeholder="Enter item name" value="<?php echo $item->model; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="selling_price">Selling Price</label>
                        <input type="text" class="form-control" id="selling_price" name="selling_price" placeholder="Enter selling price" value="<?php echo $item->selling_price; ?>">
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="kg">Weight</label>
                        <input type="text" class="form-control" id="kg" name="kg" placeholder="Enter weight" value="<?php echo $item->kg; ?>">
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="item_group_id">Item Group</label>
                        <div class="input-group">
                            <select class="select2" id="item_group_id" name="item_group_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($item->item_group_id!=0){?>
                                    <option value="<?php echo $item->item_group_id; ?>" selected="selected"><?php echo $item->item_group_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-item_group" data-toggle="tooltip" title="New Item Group">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="item_class_id">Item Class</label>
                        <div class="input-group">
                            <select class="select2" id="item_class_id" name="item_class_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($item->item_class_id!=0){?>
                                    <option value="<?php echo $item->item_class_id; ?>" selected="selected"><?php echo $item->item_class_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-item_class" data-toggle="tooltip" title="New Item Class">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="maker_id">Maker</label>
                        <div class="input-group">
                            <select class="select2" id="maker_id" name="maker_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($item->maker_id!=0){?>
                                    <option value="<?php echo $item->maker_id; ?>" selected="selected"><?php echo $item->maker_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-maker" data-toggle="tooltip" title="New Maker">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="unit_id">Unit</label>
                        <div class="input-group">
                            <select class="select2" id="unit_id" name="unit_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($item->unit_id!=0){?>
                                    <option value="<?php echo $item->unit_id; ?>" selected="selected"><?php echo $item->unit_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-unit" data-toggle="tooltip" title="New Unit">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="default_lot_id">Default Lot</label>
                        <select class="select2" id="default_lot_id" name="default_lot_id" data-placeholder="--Select--" style="width: 100%;">
                            <option></option>
                            <?php if($item->default_lot_id!=0){?>
                                <option value="<?php echo $item->default_lot_id; ?>" selected="selected"><?php echo $item->default_lot_name;?></option>
                            <?php }?>
                        </select>

                    </div>

                </div>
            </div>

            <div class="box-footer">
                <button type="button" class="btn btn-primary" name="submit" id="submit"><i class="fa fa-save"></i> Submit </button>
                <a href="#item" class="btn btn-default"><i class="fa fa-close"></i> Cancel </a>
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

        $("#item_type_id").select2({
            ajax: {
                url: "<?php echo base_url()?>item_type/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        parent_id:1
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

        $("#item_group_id").select2({
            ajax: {
                url: "<?php echo base_url()?>item_group/get_combobox_items",
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

        $("#item_class_id").select2({
            ajax: {
                url: "<?php echo base_url()?>item_class/get_combobox_items",
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

        $("#maker_id").select2({
            ajax: {
                url: "<?php echo base_url()?>maker/get_combobox_items",
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

        $("#unit_id").select2({
            ajax: {
                url: "<?php echo base_url()?>unit/get_combobox_items",
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

        $("#default_lot_id").select2({
            ajax: {
                url: "<?php echo base_url()?>warehouse/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        is_warehouse:0
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

            var form = $("#item-form");
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
                    //alert(data);
                    if(data==521){
                        go_to_login();
                    }
                    else
                    {
                        if(data.success===true){
                            $("#item_id").val(data.model.item_id);
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


<?php $this->load->view('item_type/new_item_type'); ?>
<?php $this->load->view('item_group/new_item_group'); ?>
<?php $this->load->view('item_class/new_item_class'); ?>
<?php $this->load->view('maker/new_maker'); ?>
<?php $this->load->view('unit/new_unit'); ?>

