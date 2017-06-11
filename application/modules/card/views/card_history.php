<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Card History
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#card"><i class="fa fa-home"></i> Manage Card</a></li>
    <li class="active">Card History</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">



    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;" >
                            <a href="#card/deposit" class="btn btn-primary" id="btn-new-deposit"><i class="fa fa-plus"></i> Deposit</a>
                            <a href="#card/withdraw" class="btn btn-primary" id="btn-new-withdraw"><i class="fa fa-minus"></i> Withdraw</a>
                            <a href="#card/history" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;">
                            <div class="row">
                                <div class="form-group ccol-lg-6 col-sm-6 col-xs-12">
                                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                                        <option value="card_number" <?php echo isset($search_by) && $search_by=="card_number" ? 'selected="selected"':'';?> >Card Number</option>
                                        <option value="card_name" <?php echo isset($search_by) && $search_by=="card_name" ? 'selected="selected"':'';?> >Card Name</option>
                                        <option value="card_name_kh" <?php echo isset($search_by) && $search_by=="card_name_kh" ? 'selected="selected"':'';?> >Card Name KH</option>
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
                </div><!-- /.box-header -->
                <div class="box-body" id="display-list">

                    <table id="history-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th >No</th>
                            <th>Date</th>
                            <th>Card Name</th>
                            <!--<th>Card Number </th>-->
                            <th>Transaction</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                            $total_deposit = 0;
                            $total_withdraw = 0;

                            if(isset($histories) && is_array($histories))
                            {
                                foreach($histories as $row)
                                {
                                    $total_deposit += $row->is_deposit==1 ?$row->amount_in_company_currency:0;
                                    $total_withdraw += $row->is_deposit==0 ?$row->amount_in_company_currency:0;

                                    ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo isset($row->history_date)? $row->history_date:"";?></td>
                                        <td><?php echo isset($row->card_name)? $row->card_name:"";?></td>
                                        <!--<td><?php //echo isset($row->card_number)? $row->card_number:"";?></td>-->
                                        <td><?php echo isset($row->is_deposit) && $row->is_deposit==1 ? "Deposit":"Withdraw";?></td>
                                        <td><?php echo isset($row->amount_in_company_currency)? $row->amount_in_company_currency:"0";?></td>
                                        <td>
                                            <!--
                                            <a href="#card/view_history" data-json='{"history_id":"<?php echo $row->history_id;?>"}' class="inline-button btn-view" data-toggle="tooltip" title="View Detail"> <i class="fa fa-search text-green"></i> </a>
                                            <a href="#" data-json='{"history_id":"<?php echo $row->history_id;?>"}' class="inline-button btn-edit" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                            -->
                                            <a href="#card/delete_history" data-json='{"history_id":"<?php echo $row->history_id;?>"}' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>card/delete_history"> <i class="fa fa-trash-o text-red"></i> </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                        ?>
                        </tbody>

                    </table>

                    <div class="row">
                        <br>
                        <div class="col-lg-4 col-sm-4 col-xs-12">Total Deposit : <b> <?php echo $total_deposit;?></b>  </div>
                        <div class="col-lg-4 col-sm-4 col-xs-12">Total Withdraw : <b> <?php echo $total_withdraw;?></b></div>
                        <div class="col-lg-4 col-sm-4 col-xs-12">Total Balance : <b> <?php echo $total_deposit - $total_withdraw;?></b></div>
                    </div>


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

</section><!-- /.content -->



<script type="text/javascript">

    $(document).ready(function(){

        var t = $('#history-table').DataTable( {
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
            //"createdRow": function ( row, data, index ) {

            //    var contact_id =  0; row[index].contact_id;

            //    var btn_delete = '<a href="#" onclick="delete_card('+ contact_id +')" class="inline-button" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash-o text-red"></i> </a>';
            //    var btn_edit = '<a href="#" onclick="edit_card('+contact_id+')" class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>';

            //    $('td', row).eq(5).html(btn_edit + " " + btn_delete);
            //    if(data[3] > 1){
            //        $('td', row).eq(3).addClass('blue text-bold');
            //    }
            //}
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();


        $(".select2").select2();

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

        function post_search( page=1)
        {
            var count = $("#display").val();
            var search = $("#search").val();
            var url = "<?php echo base_url()?>card/history";
            var search_by = $("#search_by").val();

            //alert(card_type_id);

            var posting = $.post(
                url,
                { ajax: 1, search_by: search_by, search: search, page: page, display: (count ? count : 10) },
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

    $(document).ready(function(){

        //delete card type
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
                formData.append('history_id', model.history_id);

                $.ajax({
                    //url: '<?php //echo base_url();?>card_group/delete',
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
                            go_to_login();
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

<script type="text/javascript">
    $(document).ready(function(){

        $(document).off('click', '#btn-search');
        $(document).on('click', '#btn-search', function(event){
            event.preventDefault();

            if($('#search-form').length) $('#search-form').submit();
        });


        $(document).off('submit', '#search-form');
        $(document).on('submit', '#search-form', function(event){
            event.preventDefault();

            var form = $(this);
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
                //dataType:"json",
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
                    else{
                        $("#display-content").empty().append(data);
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
</script>
