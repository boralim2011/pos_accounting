<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Mange User
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage User</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header">
                    <!--<h4>Filter info :</h4>-->
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;" >
                            <a href="#user/add" class="btn btn-primary" ><i class="fa fa-plus"></i> Add</a>
                            <a href="#user" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12" style="margin-top: 3px;">
                            <div class="row">
                                <div class="form-group ccol-lg-6 col-sm-6 col-xs-12">
                                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                                        <option value="user_name" <?php echo isset($search_by) && $search_by=="user_name" ? 'selected="selected"':'';?> >User Name</option>
                                        <option value="email" <?php echo isset($search_by) && $search_by=="email" ? 'selected="selected"':'';?> >Email</option>
                                        <option value="phone_number" <?php echo isset($search_by) && $search_by=="phone_number" ? 'selected="selected"':'';?> >Phone Number</option>
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

                    <!-- <form id="search-form" role="form" action="--><?php //echo base_url();?><!--user" method="post" accept-charset="utf-8">-->
                    <div class="row">
                        <!--<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <select id="company_id" name="company_id" class="form-control select2" data-placeholder="Select Company"  style="width: 100%;">
                                    <option></option>
                                    <option value="0" <?php //echo isset($company_id) && $company_id==0 ? 'selected="selected"':'';?> >All</option>
                                    <?php //if(isset($companies) && is_array($companies))
                        //foreach($companies as $comp){
                        ?>
                                            <option value="<?php //echo $comp->comp_id;?>" <?php //echo isset($company_id) && $company_id==$comp->company_id? 'selected="selected"':'';?> ><?php echo $comp->company_name;?></option>
                                            <?php
                        //}
                        ?>
                                </select>
                            </div>-->
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <select id="user_group_id" name="user_group_id" class="form-control select2" data-placeholder="User Group" data-toggle="tooltip" title="Select User Group" style="width: 100%;">
                                <option value="0" <?php echo isset($user_group_id) && $user_group_id==0 ? 'selected="selected"':'';?> >All Groups</option>
                                <?php if(isset($user_groups) && is_array($user_groups))
                                    foreach($user_groups as $group){
                                        ?>
                                        <option value="<?php echo $group->user_group_id;?>" <?php echo isset($user_group_id) && $user_group_id==$group->user_group_id? 'selected="selected"':'';?> ><?php echo $group->user_group_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <select id="user_role_id" name="user_role_id" class="form-control select2" data-placeholder="User Role" data-toggle="tooltip" title="Select User Role"  style="width: 100%;">
                                <option value="0" <?php echo isset($user_role_id) && $user_role_id==0 ? 'selected="selected"':'';?> >All Roles</option>
                                <?php if(isset($user_roles) && is_array($user_roles))
                                    foreach($user_roles as $role){
                                        ?>
                                        <option value="<?php echo $role->user_role_id;?>" <?php echo isset($user_role_id) && $user_role_id==$role->user_role_id? 'selected="selected"':'';?> ><?php echo $role->user_role_name;?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- </form>-->

                </div><!-- /.box-header -->


                <div class="box-body" id="display-list">
                    <table id="user-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>User Name</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($users) && is_array($users)){
                            foreach($users as $row){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo isset($row->user_name)? $row->user_name:"";?></td>
                                    <td><?php echo isset($row->is_active) && $row->is_active==1 ? "Yes":"No";?></td>
                                    <td>
                                        <a href="#user/view_detail/<?php echo $row->user_id;?>" data-json='<?php echo json_encode($row);?>' class="inline-button" data-toggle="tooltip" title="View Detail"> <i class="fa fa-search text-green"></i> </a>
                                        <a href="#user/edit/<?php echo $row->user_id;?>" data-json='<?php echo json_encode($row);?>' class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>user/delete"> <i class="fa fa-trash-o text-red"></i> </a>
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

        var t = $('#user-table').DataTable( {
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

            //    var user_id =  0; row[index].user_id;

            //    var btn_delete = '<a href="#" onclick="delete_user('+ user_id +')" class="inline-button" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash-o text-red"></i> </a>';
            //    var btn_edit = '<a href="#" onclick="edit_user('+user_id+')" class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>';

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

        $("#company_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
        });

        $("#user_group_id").change(function(){
            event.preventDefault();

            post_search();
            return false;
        });

        $("#user_role_id").change(function(){
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


        function post_search( page=1)
        {
            var count = $("#display").val();
            var search = $("#search").val();
            var url = "<?php echo base_url()?>user/manage_user";
            var search_by = $("#search_by").val();

            var user_group_id = $("#user_group_id").val();
            var user_role_id = $("#user_role_id").val();

            var posting = $.post(
                url,
                { ajax: 1, search_by: search_by, user_group_id: user_group_id, user_role_id: user_role_id, search: search, page: page, display: (count ? count : 10) },
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
                formData.append('user_id', model.user_id);

                $.ajax({
                    //url: '<?php //echo base_url();?>user_role/delete',
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
