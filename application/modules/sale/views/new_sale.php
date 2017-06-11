
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($title)? $title:'New Sale'; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> home</a></li>
    <li><a href="#sale"><i class="fa fa-id-sale-o"></i> Manage Sale</a></li>
    <li class="active"><?php echo isset($title)? $title:'New Sale'; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <form id="sale-form" role="form" action="<?php echo $url;?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <input type="hidden" id="journal_id" name="journal_id" value="<?php echo $sale->journal_id;?>" />
            <input type="hidden" id="currency_id" name="currency_id" value="<?php echo $sale->currency_id;?>" />
            <input type="hidden" id="journal_status" name="journal_status" value="<?php echo $sale->journal_status;?>" />
            <input type="hidden" id="exchange_rate_model" name="exchange_rate_model" value='<?php echo isset($exchange_rate)? json_encode($exchange_rate):"";?>' />

            <div class="box-header with-border">
                <a href="#" id="btn-sale-info"><h3 class="box-title">Sale Info: </h3> <i class="fa fa-plus-circle"></i></a>
                <div class="box-tools pull-right">
                    <!--<button class="btn btn-box-tool " data-widget="collapse"><i class="fa fa-minus"></i></button>-->
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row" id="sale-info">
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label for="room_id">Room</label>
                        <div class="input-group">
                            <select class="select2" id="room_id" name="room_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($sale->room_id!=0){?>
                                    <option value="<?php echo $sale->room_id; ?>" selected="selected"><?php echo $sale->room_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-room" data-toggle="tooltip" title="New Sale Type">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label><span class="text-red">*</span> Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" name="journal_date" id="journal_date" value="<?php echo $sale->journal_date;?>">
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label >Sale No</label>
                        <input type="text" class="form-control" id="journal_no" name="journal_no" placeholder="Enter sale number" value="<?php echo $sale->journal_no; ?>">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Card</label>
                        <input type="password" class="form-control" name="card_number" id="card_number" value="<?php echo isset($sale->card_number)? $sale->card_number:"";?>">
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Age</label>
                        <div class="input-group">
                            <select class="select2" id="age_range_id" name="age_range_id" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($sale->age_range_id!=0){?>
                                    <option value="<?php echo $sale->age_range_id; ?>" selected="selected"><?php echo $sale->age_range_name;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary" id="btn-new-age_range" data-toggle="tooltip" title="New Age Range">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label >Gender</label>
                        <select class="select2" id="gender" name="gender" data-placeholder="--Select--" style="width: 100%;">
                            <option></option>
                            <option value="F" <?php echo isset($sale->gender) && $sale->gender=="F"?'selected="selected"':''; ?> >Female</option>
                            <option value="M" <?php echo isset($sale->gender) && $sale->gender=="M"?'selected="selected"':''; ?> >Male</option>
                            <option value="O" <?php echo isset($sale->gender) && $sale->gender=="O"?'selected="selected"':''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div id="item-search-display"> </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div id="sale-items">
                                    <?php $this->load->view('sale/sale_items', array());?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="box-footer">
                <button type="button" class="btn btn-warning" name="payment" id="btn-payment"><i class="fa fa-dollar"></i> Payment </button>
                <button type="button" class="btn btn-success" name="print" id="btn-print"><i class="fa fa-print"></i> Print </button>
                <button type="button" class="btn btn-primary" name="submit" id="submit"><i class="fa fa-save"></i> Submit </button>
                <a href="#sale" class="btn btn-default"><i class="fa fa-close"></i> Cancel </a>
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

        $("#room_id").select2({
            ajax: {
                url: "<?php echo base_url()?>room/get_combobox_items",
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

        var posting = $.post(
            "<?php echo base_url();?>item/search",
            { ajax: 1 },
            function (data, status, xhr) {
                if (data == 521) {
                    go_to_login();
                }
                else {
                    $("#item-search-display").empty().append(data);

                }
            }
        );

        /*
        $(document).off("keyup", "#card_number");
        $(document).on("keyup",'#card_number', function(e)
        {
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(verify_card, 500);
            $(this).data('timer', wait);

        });
        */

        $(document).off('keydown', "#card_number");
        $(document).on('keydown','#card_number',function (e)
        {
            if(e.keyCode!=13) return;

            var card_number = $('#card_number').val();
            //if(typeof card_number== typeof undefined || card_number=='') return;

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);

            var rate = $("#exchange_rate_model").val();
            var exchange_rate = null;
            if(typeof rate != typeof undefined && rate!='' ) exchange_rate = JSON.parse(rate);

            var discount = $('#discount').val();

            var url = "<?php echo base_url()?>sale/verify_card";

            var posting = $.post(
                url,
                { submit: 1, sale_items:sale_items, delete_sale_items:delete_sale_items, exchange_rate: exchange_rate, discount: discount, card_number:card_number },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else
                    {
                        try
                        {
                            jsonResult = JSON.parse(data);
                            show_message(jsonResult.message, $("#message"));
                        }
                        catch (e)
                        {
                            $("#sale-items").empty().append(data);
                        };

                    }
                }
            );

        });

        $(document).off("click",".btn-choose-item");
        $(document).on("click",".btn-choose-item", function(event){
            event.preventDefault();

            var model = JSON.parse($(this).attr('data-json'));
            //var formData = new FormData();
            //formData.append('submit', 1);

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);

            var rate = $("#exchange_rate_model").val();
            var exchange_rate = null;
            if(typeof rate != typeof undefined && rate!='' ) exchange_rate = JSON.parse(rate);

            var discount = $('#discount').val();

            var url = "<?php echo base_url()?>sale/choose_item";

            var posting = $.post(
                url,
                { submit: 1, item_model : model, sale_items:sale_items, delete_sale_items:delete_sale_items, exchange_rate: exchange_rate, discount: discount },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#sale-items").empty().append(data);
                    }
                }
            );

        });


        $("#sale-info").hide();

        $(document).off("click","#btn-sale-info");
        $(document).on("click","#btn-sale-info", function(event)
        {
            event.preventDefault();

            $("#sale-info").toggle('show');
        });


        //add payment
        $(document).off("click","#btn-payment");
        $(document).on("click","#btn-payment", function(event){
            event.preventDefault();

            var journal_id = $("#journal_id").val();
            var url = "<?php echo base_url();?>payment/add";

            var posting = $.post(
                url,
                { ajax: 1, journal_id: journal_id },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#payment-form").empty().append(data);

                        $("#dialog-payment .modal-title").html("New Payment");
                        $("#payment-form").attr('action', '<?php echo base_url()?>payment/add' );
                        $("#payment_id").val(0);

                        $("#dialog-payment").modal({
                            backdrop: "static" // true | false | "static" => default is true
                        });
                    }
                }
            );
        })

    });
</script>


<script type="text/javascript">

    $(document).ready(function()
    {
        var journal_id = $("#journal_id").val();
        if(journal_id==0 || journal_id=="")
        {
            $("#btn-print").hide();
            $("#btn-payment").hide();
        }


        $(document).off("click","#submit");
        $(document).on("click","#submit", function( event )
        {
            event.preventDefault();

            //alert("click submit"); return;

            var form = $("#sale-form");
            var formData = new FormData(form[0]);
            var url = form.attr('action').toString();

            //var formData = new FormData();
            formData.append('submit', 1);
            // Main magic with files here
            //formData.append('file', $('input[type=file]')[0].files[0]);


            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);
            formData.append('sale_items', JSON.stringify(sale_items));

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);
            formData.append('delete_sale_items', JSON.stringify(delete_sale_items));

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
                    //$('#message').html(data); return;
                    if(data==521){
                        go_to_login();
                    }
                    else
                    {
                        if(data.success===true){
                            $("#journal_id").val(data.model.journal_id);
                            $("#journal_no").val(data.model.journal_no);
                            $("#sale-items-table").attr('data-json', JSON.stringify(data.models));
                            $("#sale-items-table").attr('delete-json', "");

                            $("#btn-print").show();
                            $("#btn-payment").show();
                        }
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


        $(document).off("click", "#btn-print");
        $(document).on("click", "#btn-print", function(event){
            event.preventDefault();

            var data = $("#search-form").serializeArray();
            data.push({name:'submit', value: 1});
            //data.push({name:'report_name', value: $("#report_name").val()});
            //data.push({name:'report_no', value: $("#report_no").val()});
            data.push({name:'journal_id', value: $("#journal_id").val()});
            data.push({name:'print', value: 1});

            var url = '<?php echo base_url()?>sale/print_invoice';

            openWindow(url, data);
        })

    });

    var popup_data;
    function openWindow(url, data) {
        $.post(url, data, function(result) {
            popup_data = result;
            window.open('<?php echo base_url().'application/views/popup.html';?>', 'Print Report','height='+screen.height+', width='+screen.width+', left=-1, top=-1, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');
        });
    }

</script>


<?php $this->load->view('room/new_room'); ?>
<?php $this->load->view('age_range/new_age_range'); ?>


<div id="payment-form">
    <?php //$this->load->view('payment/new_payment'); ?>
</div>





