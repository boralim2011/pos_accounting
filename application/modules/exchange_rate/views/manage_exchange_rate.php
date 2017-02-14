<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Mange Exchange_rate
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage Exchange_rate</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <!-- <div class="box-header">
                   <h3 class="box-title">List of Exchange_rate</h3>
                </div>-->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;" >
                            <a href="#" class="btn btn-primary" id="btn-new-exchange_rate"><i class="fa fa-plus"></i> Add</a>
                            <a href="#exchange_rate" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;">
                            <div class="row">
                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                                        <option value="from_currency_name" <?php echo isset($search_by) && $search_by=="from_currency_name" ? 'selected="selected"':'';?> >From Currency Name</option>
                                        <option value="to_currency_name" <?php echo isset($search_by) && $search_by=="to_currency_name" ? 'selected="selected"':'';?> >To Currency Name</option>
                                        <option value="from_currency_name_kh" <?php echo isset($search_by) && $search_by=="from_currency_name_kh" ? 'selected="selected"':'';?> >From Currency Name KH</option>
                                        <option value="to_currency_name_kh" <?php echo isset($search_by) && $search_by=="to_currency_name_kh" ? 'selected="selected"':'';?> >To Currency Name KH</option>
                                        <option value="bit_rate" <?php echo isset($search_by) && $search_by=="bit_rate" ? 'selected="selected"':'';?> >Bit Rate</option>
                                        <option value="ask_rate" <?php echo isset($search_by) && $search_by=="ask_rate" ? 'selected="selected"':'';?> >Ask Rate</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?php echo isset($search)? $search : "";?>"/>
                                        <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-body">
                    <table id="exchange_rate-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Exchange</th>
                            <th>Bit</th>
                            <th>Ask</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($exchange_rates) && is_array($exchange_rates)){
                            foreach($exchange_rates as $row){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php
                                        echo $exchange = $row->is_inverse==1?
                                            $row->to_currency_name."/".$row->from_currency_name :
                                            $row->from_currency_name."/".$row->to_currency_name
                                        ;
                                        ?></td>
                                    <td><?php echo isset($row->bit_rate)? $row->bit_rate:"";?></td>
                                    <td><?php echo isset($row->ask_rate)? $row->ask_rate:"";?></td>
                                    <td>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-view" data-toggle="tooltip" title="View Detail"> <i class="fa fa-search text-green"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-edit" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>exchange_rate/delete"> <i class="fa fa-trash-o text-red"></i> </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xs-4">
                            <select id="display" name="display"  data-placeholder="10" style="margin-top: 20px; padding: 5px;" >
                                <option value="10" <?php echo $display==10? 'selected="selected"':''; ?>>10</option>
                                <option value="20" <?php echo $display==20? 'selected="selected"':''; ?> >20</option>
                                <option value="30" <?php echo $display==30? 'selected="selected"':''; ?>>30</option>
                                <option value="50" <?php echo $display==50? 'selected="selected"':''; ?>>50</option>
                                <option value="100" <?php echo $display==100? 'selected="selected"':''; ?>>100</option>
                                <option value="200" <?php echo $display==200? 'selected="selected"':''; ?>>200</option>
                                <option value="300" <?php echo $display==300? 'selected="selected"':''; ?>>300</option>
                                <option value="500" <?php echo $display==500? 'selected="selected"':''; ?>>500</option>
                            </select>
                        </div>
                        <div class="col-xs-8">
                            <div id="page-selection" class="pull-right"> </div>
                            <div style="margin-top: 20px; padding: 5px 20px;" class="pull-right">
                                Total:  <span style="font-weight: bold;"> <?php echo $records; ?> </span> records
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div id = "message" >
        <?php if(isset($message)) $this->show_message($message); ?>
    </div>

</section><!-- /.content -->


<script type="text/javascript">

    $(document).ready(function(){

        var t = $('#exchange_rate-table').DataTable( {
            "scrollX": true,
            bFilter: false, //show or hide box filter
            bInfo: false,
            bPaginate: false,
            //"sPaginationType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]//,
//            "createdRow": function ( row, data, index ) {
//
//                var user_id =  0;//row[index].user_id;
//
//                var btn_delete = '<a href="#" onclick="delete_user('+ user_id +')" class="inline-button" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash-o text-red"></i> </a>';
//                var btn_edit = '<a href="#" onclick="edit_user('+user_id+')" class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>';
//
//                $('td', row).eq(5).html(btn_edit + " " + btn_delete);
//                if(data[3] > 1){
//                    $('td', row).eq(3).addClass('blue text-bold');
//                }
//            }
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $('#display').change(function(event){
            event.preventDefault();

            post_search();
        });

        //Pagination
        $('#page-selection').bootpag({
            total: <?php echo $pages;?>,
            page: <?php echo $page;?>,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            //first: '<span aria-hidden="true">&larr;</span>',
            //last: '<span aria-hidden="true">&rarr;</span>',
            first:'<i class="fa fa-fast-backward" style="font-size:10px;"></i>',
            last:'<i class="fa fa-fast-forward" style="font-size:10px;"></i>',
            prev:'<i class="fa fa-backward" style="font-size:10px;"></i>',
            next:'<i class="fa fa-forward" style="font-size:10px;"></i>',
            wrapClass: 'pagination',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            event.preventDefault();

            post_search( num);

            return false;
        });


        $("#btn-search").click(function(event){
            event.preventDefault();

            post_search();
        });

        $('#search').keyup(function (e)
        {
            if(e.keyCode==13) post_search();
        });

        $('#search_by').select2();


        function post_search( page=1)
        {
            var count = $("#display").val();
            var search = $("#search").val();
            var search_by = $("#search_by").val();

            var url = "<?php echo base_url()?>exchange_rate/manage_exchange_rate";

            var posting = $.post(
                url,
                { ajax: 1, search_by:search_by, search: search, page: page, display: (count ? count : 10) },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#display-content").empty().append(data);
                    }
                }
            );
        }

    });

</script>

<script type="text/javascript">

    function display_rate()
    {
        var from = $("#from_currency_id option:selected").text();
        var to = $("#to_currency_id option:selected").text();

        var display = $("#is_inverse").is(':checked') ? to + '/' + from : from + '/' + to;

        var bit_rate = $("#bit_rate").val();
        var ask_rate = $("#ask_rate").val();
        display += ' : ' + bit_rate + " - " + ask_rate;

        $("#rate_display").val(display);
    }

    $(document).ready(function(){

        //view exchange_rate
        $(document).off("click",".btn-view");
        $(document).on("click",".btn-view", function(event){
            event.preventDefault();

            $("#dialog-exchange_rate .modal-title").html("View Exchange_rate");
            $("#exchange_rate-form").attr('action', '<?php echo base_url()?>exchange_rate/view_detail' );

            var model = JSON.parse($(this).attr('data-json'));
            $("#exchange_rate_id").val(model.exchange_rate_id);
            $("#bit_rate").val(model.bit_rate);
            $("#ask_rate").val(model.ask_rate);
            $("#is_inverse").iCheck(model.is_inverse==1?'check':'uncheck');
            $('#from_currency_id').select2("val", model.from_currency_id);
            $('#to_currency_id').select2("val", model.to_currency_id);

            display_rate();

            $("#btn-add-exchange_rate").remove();

            $("#dialog-exchange_rate").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        });

        //edit exchange_rate
        $(document).off("click",".btn-edit");
        $(document).on("click",".btn-edit", function(event){
            event.preventDefault();

            $("#dialog-exchange_rate .modal-title").html("Edit Exchange_rate");
            $("#exchange_rate-form").attr('action', '<?php echo base_url()?>exchange_rate/edit' );

            var model = JSON.parse($(this).attr('data-json'));
            $("#exchange_rate_id").val(model.exchange_rate_id);
            $("#bit_rate").val(model.bit_rate);
            $("#ask_rate").val(model.ask_rate);
            $("#is_inverse").iCheck(model.is_inverse==1?'check':'uncheck');
            $('#from_currency_id').select2("val", model.from_currency_id);
            $('#to_currency_id').select2("val", model.to_currency_id);

            display_rate();

            $("#dialog-exchange_rate").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        });

        $("#is_inverse").on("ifChanged", function(){ display_rate(); });
        $("#from_currency_id").change(function(){ display_rate(); });
        $("#to_currency_id").change(function(){ display_rate(); });
        $("#bit_rate").blur(function(){ display_rate(); });
        $("#ask_rate").blur(function(){ display_rate(); });

        //delete exchange_rate
        $(document).off("click",".btn-delete");
        $(document).on("click",".btn-delete", function(event){
            event.preventDefault();

            var btn = $(this);

            confirm_message('Are you sure to delete');

            $(document).off("click", "#dialog-confirm #btn-ok");
            $(document).on("click", "#dialog-confirm #btn-ok", function(event){
                event.preventDefault();


                $("#dialog-confirm").modal("hide");

                var model = JSON.parse(btn.attr('data-json'));
                var formData = new FormData();
                formData.append('submit', 1);
                formData.append('exchange_rate_id', model.exchange_rate_id);


                $.ajax({
                    //url: '<?php //echo base_url();?>exchange_rate/delete',
                    url: btn.attr("url"),
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
                            lgo_to_login();
                        }
                        else{
                            if(data.success===true)
                            {
                                if ($(".btn-refresh").length) $(".btn-refresh").trigger('click');
                            }
                            show_message(data.message, $("#message"));
                        }
                    },
                    error: function(xhr,status,error){
                        //alert('error'+ error);
                        var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                        show_message(message, $("#message"));
                    },
                    complete: function(xhr,status){
                        //alert(status);
                    },
                    contentType: false,
                    processData: false
                });
            });

        });

    });
</script>

<?php
    //$data['from_currencies'] = $from_currencies;
    //$data['to_currencies'] = $to_currencies;

    //$this->load->view('exchange_rate/new_exchange_rate', $data);
?>