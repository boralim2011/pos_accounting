<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Mange Location
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage Location</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">List of Location</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-xs-12" style="margin-top: 5px;">
                            <a href="#" class="btn btn-primary" id="btn-new-location"><i class="fa fa-plus"></i> Add</a>
                            <a href="#location" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-xs-12" style="margin-top: 5px;">
                            <select id="location_type_1" name="location_type_1" class="form-control select2" data-placeholder="Location Type"  style="width: 100%; display: none;">
                                <option ></option>
                                <option value="1" <?php echo $location->location_type_id==1? 'selected="selected"':''; ?>>Country</option>
                                <option value="2" <?php echo $location->location_type_id==2? 'selected="selected"':''; ?> >Province/City</option>
                                <option value="4" <?php echo $location->location_type_id==4? 'selected="selected"':''; ?>>District/Khan</option>
                                <option value="6" <?php echo $location->location_type_id==6? 'selected="selected"':''; ?>>Commune/Sangkat</option>
                                <option value="8" <?php echo $location->location_type_id==8? 'selected="selected"':''; ?>>Village</option>
                                <option value="9" <?php echo $location->location_type_id==9? 'selected="selected"':''; ?>>Location</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Location Name" value="<?php echo $location->location_name;?>"/>
                                <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="location-table" class="table table-bordered table-hover td-responsive nowrap">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Location Name </th>
                            <th>Name(Khmer) </th>
                            <th>Parent Location </th>
                            <th>Location Type </th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($locations) && is_array($locations)){
                            foreach($locations as $row){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo isset($row->location_name)? $row->location_name:"";?></td>
                                    <td class="font-khmer"><?php echo isset($row->location_name_kh)? $row->location_name_kh:"";?></td>
                                    <td><?php echo isset($row->parent_location)? $row->parent_location:"";?></td>
                                    <td><?php echo isset($row->location_type)? $row->location_type:"";?></td>
                                    <td>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-edit" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>location/delete"> <i class="fa fa-trash-o text-red"></i> </a>
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
                            <select id="display_count" name="display_count"  data-placeholder="10" style="margin-top: 20px; padding: 5px;" >
                                <option value="10" <?php echo $display_count==10? 'selected="selected"':''; ?>>10</option>
                                <option value="20" <?php echo $display_count==20? 'selected="selected"':''; ?> >20</option>
                                <option value="30" <?php echo $display_count==30? 'selected="selected"':''; ?>>30</option>
                                <option value="50" <?php echo $display_count==50? 'selected="selected"':''; ?>>50</option>
                                <option value="100" <?php echo $display_count==100? 'selected="selected"':''; ?>>100</option>
                                <option value="200" <?php echo $display_count==200? 'selected="selected"':''; ?>>200</option>
                                <option value="300" <?php echo $display_count==300? 'selected="selected"':''; ?>>300</option>
                                <option value="500" <?php echo $display_count==500? 'selected="selected"':''; ?>>500</option>
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

        var t = $('#location-table').DataTable( {
            //responsive: true,
            scrollX: true,
            bFilter: false, //show or hide box filter
            bInfo: false,
            bPaginate: false,
            //"sPaginationType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200 , 500]],
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
            var first = <?php echo ($page-1) * $display_count; ?>;
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = first + i + 1;
            } );
        } ).draw();

//        $('[name="location-table_length"]').change(function(){
//            alert($(this).val());
//        });

        $("#location_type_1").select2();

        $("#location_type_1").change(function(){
            event.preventDefault();

            post_search_location();

            return false;
        });


        $('#display_count').change(function(event){
                event.preventDefault();

                post_search_location();
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

            post_search_location( num);

            return false;
        });


        $("#btn-search").click(function(event){
            event.preventDefault();

            post_search_location();
        });

        function post_search_location( page=1)
        {
            var type = $("#location_type_1").val();
            var count = $("#display_count").val();
            var name = $("#search").val();
            var url = "<?php echo base_url()?>location/manage_location/" + (type? type:2) + "/" + (count?count:10) + "/" + (page?page:1)  ;

            var posting = $.post(
                url,
                {ajax : 1, name: name },
                function( data, status, xhr )
                {
                    if(data==521)
                    {
                        go_to_login();
                    }
                    else
                    {
                        $("#display-content").empty().append(data);
                    }
                }
            );

//            $.ajax({
//                url: url,
//                type: 'POST',
//                data: {ajax : 1 },
//                dataType: "",
//
//                success: function (data, status, xhr) {
//                    if(data==521)
//                    {
//                        go_to_login();
//                    }
//                    else
//                    {
//                        $("#display-content").empty().append(data);
//                    }
//                },
//                contentType: false,
//                processData: false
//            });

        }

    });

</script>

<script type="text/javascript">

    $(document).ready(function(){

        //edit use type
        $(document).off("click",".btn-edit");
        $(document).on("click",".btn-edit", function(event){
            event.preventDefault();

            $("#dialog-location .modal-title").html("Edit Location");
            $("#location-form").attr('action', '<?php echo base_url()?>location/edit' );

            var model = JSON.parse($(this).attr('data-json'));
            $("#location_id").val(model.location_id);
            $("#location_name").val(model.location_name);
            $("#location_name_kh").val(model.location_name_kh);
            $("#location_code").val(model.location_code);
            $("#is_deletable").val(model.is_deletable);

            //$('#location_type').empty().append('<option></option><option value="'+ model.location_type_id + '" selected="selected">'+ model.location_type +'</option>');
            $('#location_type').val(model.location_type_id);
            $('#parent_location').empty().append('<option></option><option value="'+ model.parent_location_id +'" selected="selected">'+ model.parent_location +'</option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        });

        //delete user type
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
                formData.append('location_id', model.location_id);

                $.ajax({
                    //url: '<?php //echo base_url();?>location/delete',
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
                                if ($("#btn-search").length) $("#btn-search").trigger('click');
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


<?php $this->load->view('location/new_location'); ?>


