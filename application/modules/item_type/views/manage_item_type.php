<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Mange Item_type
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage Item_type</li>
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
                   <h3 class="box-title">List of Item_type</h3>
                </div>-->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;" >
                            <a href="#" class="btn btn-primary" id="btn-new-item_type"><i class="fa fa-plus"></i> Add</a>
                            <a href="#item_type" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;">
                            <div class="row">
                                <div class="form-group ccol-lg-6 col-sm-6 col-xs-12">
                                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                                        <option value="item_type_name" <?php echo isset($search_by) && $search_by=="item_type_name" ? 'selected="selected"':'';?> >Item_type Name</option>
                                        <option value="item_type_name_kh" <?php echo isset($search_by) && $search_by=="item_type_name_kh" ? 'selected="selected"':'';?> >Item_type Name KH</option>
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
                    <table id="item_type-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Item_type Name </th>
                            <th>Name KH</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($item_types) && is_array($item_types)){
                            foreach($item_types as $row){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo isset($row->item_type_name)? $row->item_type_name:"";?></td>
                                    <td><?php echo isset($row->item_type_name_kh)? $row->item_type_name_kh:"";?></td>
                                    <td>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-view" data-toggle="tooltip" title="View Detail"> <i class="fa fa-search text-green"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-edit" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>item_type/delete"> <i class="fa fa-trash-o text-red"></i> </a>
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

        var t = $('#item_type-table').DataTable( {
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


        function post_search( page=1)
        {
            var count = $("#display").val();
            var search = $("#search").val();
            var search_by = $("#search_by").val();

            var url = "<?php echo base_url()?>item_type/manage_item_type";

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

    $(document).ready(function(){

        //view item_type
        $(document).off("click",".btn-view");
        $(document).on("click",".btn-view", function(event){
            event.preventDefault();

            $("#dialog-item_type .modal-title").html("View Item_type");
            $("#item_type-form").attr('action', '<?php echo base_url()?>item_type/view_detail' );

            var model = JSON.parse($(this).attr('data-json'));
            $("#item_type_id").val(model.item_type_id);
            $("#item_type_name").val(model.item_type_name);
            $("#item_type_name_kh").val(model.item_type_name_kh);
            $("#manage_stock").iCheck(model.manage_stock==1?'check':'uncheck');
            $('#parent_id').select2("val", model.parent_id);

            $("#btn-add-item_type").remove();

            $("#dialog-item_type").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        });

        //edit item_type
        $(document).off("click",".btn-edit");
        $(document).on("click",".btn-edit", function(event){
            event.preventDefault();

            $("#dialog-item_type .modal-title").html("Edit Item_type");
            $("#item_type-form").attr('action', '<?php echo base_url()?>item_type/edit' );

            var model = JSON.parse($(this).attr('data-json'));
            $("#item_type_id").val(model.item_type_id);
            $("#item_type_name").val(model.item_type_name);
            $("#item_type_name_kh").val(model.item_type_name_kh);
            $("#manage_stock").iCheck(model.manage_stock==1?'check':'uncheck');
            $('#parent_id').select2("val", model.parent_id);

            $("#dialog-item_type").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        });

        //delete item_type
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
                formData.append('item_type_id', model.item_type_id);


                $.ajax({
                    //url: '<?php //echo base_url();?>item_type/delete',
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




<?php $this->load->view('item_type/new_item_type'); ?>
