<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Manage Item
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage Item</li>
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
                            <a href="#item/add_product" class="btn btn-primary" ><i class="fa fa-plus"></i> Product</a>
                            <a href="#item/add_service" class="btn btn-primary" ><i class="fa fa-plus"></i> Service</a>
                            <a href="#item/add_mixed" class="btn btn-primary" ><i class="fa fa-plus"></i> Mixed</a>
<!--                            <a href="#item/add_item_set" class="btn btn-primary" ><i class="fa fa-plus"></i> Item Set</a>-->
                            <a href="#item" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;">
                            <div class="row">
                                <div class="form-group ccol-lg-6 col-sm-6 col-xs-12">
                                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                                        <option value="item_name" <?php echo isset($search_by) && $search_by=="item_name" ? 'selected="selected"':'';?> >Item Name</option>
                                        <option value="item_name_kh" <?php echo isset($search_by) && $search_by=="item_name_kh" ? 'selected="selected"':'';?> >Item Name KH</option>
                                        <option value="item_code" <?php echo isset($search_by) && $search_by=="item_code" ? 'selected="selected"':'';?> >Item Code</option>
                                        <option value="barcode" <?php echo isset($search_by) && $search_by=="barcode" ? 'selected="selected"':'';?> >Barcode</option>
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
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <select id="item_type_id" name="item_type_id" class="form-control select2" data-placeholder="Item type"  style="width: 100%;">
                                <option value="0" <?php echo isset($item_type_id) && $item_type_id==0 ? 'selected="selected"':'';?> >All Item Type</option>
                                <?php if(isset($item_types) && is_array($item_types))
                                    foreach($item_types as $ut){
                                        ?>
                                        <option value="<?php echo $ut->item_type_id;?>" <?php echo isset($item_type_id) && $item_type_id==$ut->item_type_id? 'selected="selected"':'';?> ><?php echo $ut->item_type_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <select id="item_group_id" name="item_group_id" class="form-control select2" data-placeholder="Item group"  style="width: 100%;">
                                <option value="0" <?php echo isset($item_group_id) && $item_group_id==0 ? 'selected="selected"':'';?> >All Item Group</option>
                                <?php if(isset($item_groups) && is_array($item_groups))
                                    foreach($item_groups as $ut){
                                        ?>
                                        <option value="<?php echo $ut->item_group_id;?>" <?php echo isset($item_group_id) && $item_group_id==$ut->item_group_id? 'selected="selected"':'';?> ><?php echo $ut->item_group_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <select id="item_class_id" name="item_class_id" class="form-control select2" data-placeholder="Item group"  style="width: 100%;">
                                <option value="0" <?php echo isset($item_class_id) && $item_class_id==0 ? 'selected="selected"':'';?> >All Item Class</option>
                                <?php if(isset($item_classes) && is_array($item_classes))
                                    foreach($item_classes as $ut){
                                        ?>
                                        <option value="<?php echo $ut->item_class_id;?>" <?php echo isset($item_class_id) && $item_class_id==$ut->item_class_id? 'selected="selected"':'';?> ><?php echo $ut->item_class_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <select id="maker_id" name="maker_id" class="form-control select2" data-placeholder="Maker"  style="width: 100%;">
                                <option value="0" <?php echo isset($maker_id) && $maker_id==0 ? 'selected="selected"':'';?> >All Maker</option>
                                <?php if(isset($makers) && is_array($makers))
                                    foreach($makers as $ut){
                                        ?>
                                        <option value="<?php echo $ut->maker_id;?>" <?php echo isset($maker_id) && $maker_id==$ut->maker_id? 'selected="selected"':'';?> ><?php echo $ut->maker_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body" id="display-list">
                    <table id="item-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($items) && is_array($items)){
                            foreach($items as $row){
                                $edit = $row->parent_id==1?"edit_product":($row->parent_id==2?"edit_service":($row->parent_id==3?"edit_mixed":"edit_item_set"));
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo isset($row->item_name)? $row->item_name:""; ?></td>
                                    <td><?php echo isset($row->item_code)? $row->item_code:"";?></td>
                                    <td>
                                        <a href="#item/<?php echo $edit; ?>/<?php echo $row->item_id;?>" data-json='{"item_id":"<?php echo $row->item_id;?>"}' class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='{"item_id":"<?php echo $row->item_id;?>"}' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>item/delete"> <i class="fa fa-trash-o text-red"></i> </a>
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

</section><!-- /.content -->



<script type="text/javascript">

    $(document).ready(function(){

        var t = $('#item-table').DataTable( {
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

            //    var btn_delete = '<a href="#" onclick="delete_item('+ contact_id +')" class="inline-button" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash-o text-red"></i> </a>';
            //    var btn_edit = '<a href="#" onclick="edit_item('+contact_id+')" class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>';

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

        $("#item_type_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
        });

        $("#item_group_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
        });

        $("#item_class_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
        });

        $("#maker_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
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

    });

    function post_search( page=1)
    {
        var count = $("#display").val();
        var search = $("#search").val();
        var url = "<?php echo base_url()?>item/manage_item";
        var search_by = $("#search_by").val();
        var item_type_id = $("#item_type_id").val();
        var item_group_id = $("#item_group_id").val();
        var item_class_id = $("#item_class_id").val();
        var maker_id = $("#amker_id").val();

        //alert(item_class_id);

        var posting = $.post(
            url,
            { ajax: 1, item_type_id:item_type_id, item_group_id:item_group_id, item_class_id:item_class_id, maker_id:maker_id, search_by: search_by, search: search, page: page, display: (count ? count : 10) },
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

</script>

<script type="text/javascript">

    $(document).ready(function(){

        //delete item type
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
                formData.append('item_id', model.item_id);

                $.ajax({
                    //url: '<?php //echo base_url();?>item_group/delete',
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
                                //if ($(".btn-refresh").length) $(".btn-refresh").trigger('click');
                                post_search();
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
